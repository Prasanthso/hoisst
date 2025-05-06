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

class CheckRmPriceThreshold extends Command
{
    protected $signature = 'check:pd-price-threshold';
    protected $description = 'Check if Product price exceeds threshold and alert users';

    public function handle()
    {
        Log::info("Running check:pd-price-threshold...");

        $storeId = session('store_id');

        $materials = Product::where('status', 'active')
            ->where('store_id', $storeId)
            ->get();
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
                    ];
                }
            }
        }

        if (empty($materialsToNotify)) {
            Log::info("No threshold breaches found.");
            return;
        }

        $users = User::all();
        $whatsapp = new WhatsAppController();

        foreach ($users as $user) {
            $channel = 'email';

            try {
                Mail::to($user->email)->send(new PdThresholdExceededMail($materialsToNotify));
                Log::info("Email sent to {$user->email}");
            } catch (\Exception $e) {
                Log::error("Email error: " . $e->getMessage());
            }

            if ($user->whatsapp_enabled && $user->whatsapp_number) {
                $channel = 'both';
                try {
                    $message = "🔔 *Price Threshold Alert*\n";
                    foreach ($materialsToNotify as $m) {
                        $message .= "{$m['name']} (Code: {$m['rmcode']}) - ₹{$m['price']} > ₹{$m['threshold']}\n";
                    }

                    $whatsapp->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                    Log::info("WhatsApp sent to {$user->whatsapp_number}");
                } catch (\Exception $e) {
                    Log::error("WhatsApp error: " . $e->getMessage());
                    $channel = 'email'; // fallback
                }
            }

            // Store alert
            PdPriceUpdateAlert::create([
                'user_id' => $user->id,
                'product_ids' => collect($materialsToNotify)->pluck('id')->toArray(),
                'alerted_at' => now(),
                'channel' => $channel,
                'alert_type' => 'threshold',
            ]);
        }

        $this->info('Threshold check completed.');
    }
}
