<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PmPriceUpdateMail;
use App\Models\PackingMaterial;
use App\Models\PmPriceUpdateAlert;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\WhatsAppController;

class CheckPmPriceUpdates extends Command
{
    protected $signature = 'check:Pm-price-updates';
    protected $description = 'Check price update frequency and notify users via email';

    public function handle()
    {
        Log::info("Running check:pm-price-threshold...");

        $activeMaterials = PackingMaterial::where('status', 'active')->get();
        $alerts = [];

        // Check each material for price threshold breach
        foreach ($activeMaterials as $material) {
            $price = (float) $material->price;
            $threshold = (float) $material->price_threshold;

            if (is_numeric($material->price) && is_numeric($material->price_threshold) && $price > $threshold) {
                Log::info("Threshold exceeded: {$material->name} (Price: ₹{$price}, Threshold: ₹{$threshold})");

                $alerts[] = [
                    'id'        => $material->id,
                    'name'      => $material->name,
                    'pmcode'    => $material->pmcode,
                    'price'     => $price,
                    'threshold' => $threshold,
                    'store_id'  => $material->store_id,
                ];
            }
        }

        if (empty($alerts)) {
            Log::info("No packing material threshold breaches found.");
            return;
        }

        $groupedAlerts = collect($alerts)->groupBy('store_id');
        $whatsapp = new WhatsAppController();

        foreach ($groupedAlerts as $storeId => $materials) {
            $users = User::where('store_id', $storeId)->get();

            if ($users->isEmpty()) {
                Log::warning("No users found for store ID: {$storeId}");
                continue;
            }

            foreach ($users as $user) {
                $channel = 'email';

                // Send Email
                try {
                    Mail::to($user->email)->send(new PmThresholdExceededMail($materials->toArray()));
                    Log::info("Email sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
                }

                // Send WhatsApp
                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';

                    try {
                        $message = "🔔 *Packing Material Price Threshold Alert*\n";
                        foreach ($materials as $m) {
                            $message .= "{$m['name']} (Code: {$m['pmcode']}) - ₹{$m['price']} > ₹{$m['threshold']}\n";
                        }

                        $whatsapp->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp sent to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send WhatsApp to {$user->whatsapp_number}: " . $e->getMessage());
                        $channel = 'email';
                    }
                }

                // Store alert log
                PmPriceUpdateAlert::create([
                    'user_id'              => $user->id,
                    'store_id'             => $storeId,
                    'packing_material_ids' => $materials->pluck('id')->toArray(),
                    'alerted_at'           => now(),
                    'channel'              => $channel,
                    'alert_type'           => 'threshold',
                ]);
            }
        }

        $this->info('Packing material threshold check completed successfully.');
    }
}
