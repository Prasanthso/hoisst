<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RmPriceUpdateMail;
use App\Models\RawMaterial;
use App\Models\RmPriceUpdateAlert;

use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\WhatsAppController;

class CheckRmPriceUpdates extends Command
{
    protected $signature = 'check:rm-price-updates';
    protected $description = 'Check price update frequency and notify users via email';

    public function handle()
    {
        Log::info("Running check:rm-price-updates command...");

        $rawMaterials = RawMaterial::where('status', 'active')->get();
        $now = Carbon::now();
        $materialsToNotify = [];

        if ($rawMaterials->isEmpty()) {
            Log::warning("No active raw materials found.");
            return;
        }

        foreach ($rawMaterials as $material) {
            Log::info("Checking raw material: {$material->name}, ID: {$material->id}");

            // Get the last price update date
            $lastUpdate = DB::table('rm_price_histories')
                ->where('raw_material_id', $material->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $lastUpdateDate = $lastUpdate ? Carbon::parse($lastUpdate->created_at) : Carbon::parse($material->created_at);
            $updateFrequency = strtolower(trim($material->update_frequency));
            $priceUpdateFrequency = (int) $material->price_update_frequency;
            $shouldNotify = false;

            if (!in_array($updateFrequency, ['days', 'weeks', 'monthly', 'yearly'])) {
                Log::warning("Invalid update_frequency for {$material->name}: {$updateFrequency}");
                continue;
            }

            // Clone $now before modifying it to avoid issues
            $checkDate = (clone $now);

            switch ($updateFrequency) {
                case 'days':
                    $checkDate = $checkDate->subDays($priceUpdateFrequency);
                    break;
                case 'weeks':
                    $checkDate = $checkDate->subWeeks($priceUpdateFrequency);
                    break;
                case 'monthly':
                    $checkDate = $checkDate->subMonths($priceUpdateFrequency);
                    break;
                case 'yearly':
                    $checkDate = $checkDate->subYears($priceUpdateFrequency);
                    break;
            }

            // Debugging logs
            Log::info("Raw Material: {$material->name}, ID: {$material->id}");
            Log::info(" - Last update: {$lastUpdateDate->toDateTimeString()}");
            Log::info(" - Expected update interval: {$updateFrequency} {$priceUpdateFrequency}");
            Log::info(" - Threshold date for notification: {$checkDate->toDateTimeString()}");
            Log::info(" - Should notify? " . ($lastUpdateDate->lt($checkDate) ? 'Yes' : 'No'));

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$material->name}");
                $materialsToNotify[] = [
                    'name' => $material->name,
                    'id' => $material->id,
                    'rmcode' => $material->rmcode,
                ];
            }
        }

        if (!empty($materialsToNotify)) {
            $users = User::all();

            if ($users->isEmpty()) {
                Log::warning("No users found to notify.");
                return;
            }

            $whatsappController = new WhatsAppController();

            foreach ($users as $user) {
                Log::info("Sending email to: {$user->email}");

                try {
                    Mail::to($user->email)->send(new RmPriceUpdateMail($materialsToNotify));
                    Log::info("Email successfully sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
                }

                $channel = 'email';
                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both'; // updated below if error happens
                    try {
                        $message = "Price Alert\n";
                        foreach ($materialsToNotify as $material) {
                            $message .= "Material: {$material['name']} (Code: {$material['rmcode']})\n";
                        }
                        $message .= "\nPlease update the prices accordingly.";

                        $whatsappController->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp message sent successfully to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send WhatsApp message to {$user->whatsapp_number}: " . $e->getMessage());
                        $channel = 'email';
                    }
                }

                $rmIds = collect($materialsToNotify)->pluck('id')->toArray();

                RmPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'raw_material_ids' => $rmIds,
                    'alerted_at' => now(),
                    'channel' => $channel,
                ]);
            }
        } else {
            Log::info("No price update alerts needed.");
        }

        $this->info('Raw material price update frequency alerts checked successfully.');
    }
}
