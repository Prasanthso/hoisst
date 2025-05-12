<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PackingMaterial;
use App\Models\User;
use App\Models\PmPriceUpdateAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PmThresholdExceededMail;
use App\Http\Controllers\WhatsAppController;

class CheckPmPriceThreshold extends Command
{
    protected $signature = 'check:pm-price-threshold';
    protected $description = 'Check if packing material price exceeds threshold and alert users';

    public function handle()
    {
        Log::info("Running check:pm-price-threshold...");

        $materials = PackingMaterial::where('status', 'active')->get();
        $materialsToNotify = [];

        foreach ($materials as $material) {
            if (is_numeric($material->price) && is_numeric($material->price_threshold)) {
                if ((float)$material->price > (float)$material->price_threshold) {
                    Log::info("Threshold exceeded: {$material->name} (Price: {$material->price}, Threshold: {$material->price_threshold})");

                    $materialsToNotify[] = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'pmcode' => $material->pmcode,
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

        $groupedByStore = collect($materialsToNotify)->groupBy('store_id');
        $whatsapp = new WhatsAppController();

        foreach ($groupedByStore as $storeId => $storeMaterials) {
            $users = User::where('store_id', $storeId)->get();

            if ($users->isEmpty()) {
                Log::warning("No users found for store ID: {$storeId}");
                continue;
            }

            foreach ($users as $user) {
                $channel = 'email';

                try {
                    Mail::to($user->email)->send(new PmThresholdExceededMail($storeMaterials->toArray()));
                    Log::info("Email sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Email error: " . $e->getMessage());
                }

                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';
                    try {
                        $message = "🔔 *Packing Material Price Threshold Alert*\n";
                        foreach ($storeMaterials as $m) {
                            $message .= "{$m['name']} (Code: {$m['pmcode']}) - ₹{$m['price']} > ₹{$m['threshold']}\n";
                        }

                        $whatsapp->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp sent to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("WhatsApp error: " . $e->getMessage());
                        $channel = 'email'; // fallback
                    }
                }

                // Store alert
                PmPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'packing_material_ids' => $storeMaterials->pluck('id')->toArray(),
                    'alerted_at' => now(),
                    'channel' => $channel,
                    'alert_type' => 'threshold',
                ]);
            }
        }

        $this->info('Packing material threshold check completed.');
    }
}
