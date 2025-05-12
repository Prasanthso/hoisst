<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RawMaterial;
use App\Models\User;
use App\Models\RmPriceUpdateAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RmThresholdExceededMail;
use App\Http\Controllers\WhatsAppController;

class CheckRwmPriceThreshold extends Command
{
    protected $signature = 'check:rwm-price-threshold';
    protected $description = 'Check if raw material price exceeds threshold and alert users';

    public function handle()
    {
        Log::info("Running check:rm-price-threshold...");

        $materials = RawMaterial::where('status', 'active')->get();
        $materialsToNotify = [];

        // Step 1: Check for threshold violations
        foreach ($materials as $material) {
            if (is_numeric($material->price) && is_numeric($material->price_threshold)) {
                if ((float)$material->price > (float)$material->price_threshold) {
                    Log::info("Threshold exceeded: {$material->name} (Price: {$material->price}, Threshold: {$material->price_threshold})");
                    $materialsToNotify[] = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'rmcode' => $material->rmcode,
                        'price' => $material->price,
                        'threshold' => $material->price_threshold,
                        'store_id' => $material->store_id,
                    ];
                }
            }
        }

        // Step 2: Exit early if no notifications
        if (empty($materialsToNotify)) {
            Log::info("No threshold breaches found.");
            return;
        }

        $whatsapp = new WhatsAppController();

        // Step 3: Group materials by store_id
        $groupedByStore = collect($materialsToNotify)->groupBy('store_id');

        // Step 4: Loop through each store group and notify only that store's users
        foreach ($groupedByStore as $storeId => $materialsInStore) {
            // Get users only for this store
            $storeUsers = User::where('store_id', $storeId)->get();

            if ($storeUsers->isEmpty()) {
                Log::warning("No users found for store ID: {$storeId}");
                continue;
            }

            foreach ($storeUsers as $user) {
                $channel = 'email';

                // Email Notification
                try {
                    Mail::to($user->email)->send(new RmThresholdExceededMail($materialsInStore->toArray()));
                    Log::info("Email sent to {$user->email} for store {$storeId}");
                } catch (\Exception $e) {
                    Log::error("Email error for {$user->email} (store {$storeId}): " . $e->getMessage());
                }

                // WhatsApp Notification
                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';
                    try {
                        $message = "🔔 *Price Threshold Alert*\n";
                        foreach ($materialsInStore as $m) {
                            $message .= "{$m['name']} (Code: {$m['rmcode']}) - ₹{$m['price']} > ₹{$m['threshold']}\n";
                        }

                        $whatsapp->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp sent to {$user->whatsapp_number} for store {$storeId}");
                    } catch (\Exception $e) {
                        Log::error("WhatsApp error for {$user->whatsapp_number} (store {$storeId}): " . $e->getMessage());
                        $channel = 'email'; // fallback
                    }
                }

                // Store alert record
                RmPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'raw_material_ids' => $materialsInStore->pluck('id')->toArray(),
                    'alerted_at' => now(),
                    'channel' => $channel,
                ]);
            }
        }

        $this->info('Raw material price threshold alerts processed successfully.');
    }
}
