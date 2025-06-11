<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\PdPriceUpdateAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PdThresholdExceededMail;
use App\Http\Controllers\WhatsAppController;
use App\Models\Product;

class CheckPdPriceThreshold extends Command
{
    protected $signature = 'check:pd-price-threshold';
    protected $description = 'Check if Product price exceeds threshold and alert users';

    public function handle()
    {
        Log::info("Running check:pd-price-threshold...");

        $materials = Product::where('status', 'active')->get();
        $materialsToNotify = [];

        foreach ($materials as $material) {
            if (is_numeric($material->price) && is_numeric($material->price_threshold)) {
                if ((float)$material->price > (float)$material->price_threshold) {
                    Log::info("Threshold exceeded: {$material->name} (Price: {$material->price}, Threshold: {$material->price_threshold})");
                    $materialsToNotify[] = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'pdcode' => $material->pdcode,
                        'price' => $material->price,
                        'threshold' => $material->price_threshold,
                        'store_id' => $material->store_id,
                    ];
                }
            }
        }

        if (empty($materialsToNotify)) {
            Log::info("No threshold breaches found.");
            return;
        }

        $whatsapp = new WhatsAppController();
        $groupedByStore = collect($materialsToNotify)->groupBy('store_id');

        foreach ($groupedByStore as $storeId => $storeMaterials) {
            $users = User::where('store_id', $storeId)->get();

            if ($users->isEmpty()) {
                Log::warning("No users found for store ID: {$storeId}");
                continue;
            }

            foreach ($users as $user) {
                $channel = 'email';

                try {
                    Mail::to($user->email)->send(new PdThresholdExceededMail($storeMaterials->toArray()));
                    Log::info("Email sent to {$user->email} for store ID {$storeId}");
                } catch (\Exception $e) {
                    Log::error("Email error for {$user->email} (store {$storeId}): " . $e->getMessage());
                }

                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';
                    try {
                        $message = "🔔 *Product Price Threshold Alert*\n";
                        foreach ($storeMaterials as $m) {
                            $message .= "{$m['name']} (Code: {$m['pdcode']}) - ₹{$m['price']} > ₹{$m['threshold']}\n";
                        }

                        $whatsapp->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp sent to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("WhatsApp error to {$user->whatsapp_number}: " . $e->getMessage());
                        $channel = 'email';
                    }
                }

                PdPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'product_ids' => $storeMaterials->pluck('id')->toArray(),
                    'alerted_at' => now(),
                    'channel' => $channel,
                    'alert_type' => 'Price Threshold',
                ]);
            }
        }

        $this->info('Threshold check completed.');
    }
}
