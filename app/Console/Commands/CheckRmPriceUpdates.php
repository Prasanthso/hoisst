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

            $lastUpdate = DB::table('rm_price_histories')
                ->where('raw_material_id', $material->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $lastUpdateDate = $lastUpdate ? Carbon::parse($lastUpdate->created_at) : Carbon::parse($material->created_at);
            $updateFrequency = strtolower(trim($material->update_frequency));
            $priceUpdateFrequency = (int) $material->price_update_frequency;

            if (!in_array($updateFrequency, ['days', 'weeks', 'monthly', 'yearly'])) {
                Log::warning("Invalid update_frequency for {$material->name}: {$updateFrequency}");
                continue;
            }

            $checkDate = (clone $now);

            switch ($updateFrequency) {
                case 'days':
                    $checkDate->subDays($priceUpdateFrequency);
                    break;
                case 'weeks':
                    $checkDate->subWeeks($priceUpdateFrequency);
                    break;
                case 'monthly':
                    $checkDate->subMonths($priceUpdateFrequency);
                    break;
                case 'yearly':
                    $checkDate->subYears($priceUpdateFrequency);
                    break;
            }

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$material->name}");
                $materialsToNotify[] = [
                    'name' => $material->name,
                    'id' => $material->id,
                    'rmcode' => $material->rmcode,
                    'store_id' => $material->store_id,
                ];
            }
        }

        if (empty($materialsToNotify)) {
            Log::info("No price update alerts needed.");
            return;
        }

        $whatsappController = new WhatsAppController();

        // Group by store
        $groupedByStore = collect($materialsToNotify)->groupBy('store_id');

        foreach ($groupedByStore as $storeId => $storeMaterials) {
            $storeUsers = User::where('store_id', $storeId)->get();

            if ($storeUsers->isEmpty()) {
                Log::warning("No users found for store ID: {$storeId}");
                continue;
            }

            foreach ($storeUsers as $user) {
                Log::info("Sending notifications to user {$user->email} for store ID {$storeId}");

                $channel = 'email';

                try {
                    Mail::to($user->email)->send(new RmPriceUpdateMail($storeMaterials->toArray()));
                    Log::info("Email sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Email error to {$user->email}: " . $e->getMessage());
                }

                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';
                    try {
                        $message = "🔔 *Price Update Alert*\n";
                        foreach ($storeMaterials as $m) {
                            $message .= "{$m['name']} (Code: {$m['rmcode']})\n";
                        }
                        $message .= "\nPlease update the prices accordingly.";

                        $whatsappController->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp sent to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("WhatsApp error to {$user->whatsapp_number}: " . $e->getMessage());
                        $channel = 'email';
                    }
                }

                RmPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'raw_material_ids' => $storeMaterials->pluck('id')->toArray(),
                    'alerted_at' => now(),
                    'channel' => $channel,
                ]);
            }
        }

        $this->info('Raw material price update frequency alerts checked successfully.');
    }
}
