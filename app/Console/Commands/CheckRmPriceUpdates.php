<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RmPriceUpdateMail;
use App\Models\RawMaterial;
use App\Models\User;
use Carbon\Carbon;

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
            Log::warning("No raw materials found.");
            return;
        }

        foreach ($rawMaterials as $material) {
            Log::info("Checking raw material: {$material->name}, ID: {$material->id}");

            $lastUpdate = DB::table('rm_price_histories')
                ->where('raw_material_id', $material->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $lastUpdateDate = $lastUpdate ? Carbon::parse($lastUpdate->created_at) : Carbon::parse($material->created_at);
            $updateFrequency = strtolower($material->update_frequency);
            $priceUpdateFrequency = (int) $material->price_update_frequency;
            $shouldNotify = false;

            $checkDate = clone $now;

            if (!in_array($updateFrequency, ['days', 'weeks', 'monthly', 'yearly'])) {
                Log::warning("Invalid update_frequency for {$material->name}: {$updateFrequency}");
                continue;
            }

            switch ($updateFrequency) {
                case 'days':
                    $shouldNotify = $lastUpdateDate->lt($checkDate->subDays($priceUpdateFrequency));
                    break;
                case 'weeks':
                    $shouldNotify = $lastUpdateDate->lt($checkDate->subWeeks($priceUpdateFrequency));
                    break;
                case 'monthly':
                    $shouldNotify = $lastUpdateDate->lt($checkDate->subMonths($priceUpdateFrequency));
                    break;
                case 'yearly':
                    $shouldNotify = $lastUpdateDate->lt($checkDate->subYears($priceUpdateFrequency));
                    break;
            }

            if ($shouldNotify) {
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

            foreach ($users as $user) {
                Log::info("Sending single email to: {$user->email}");

                try {
                    Mail::to($user->email)->send(new RmPriceUpdateMail($materialsToNotify));
                    Log::info("Email successfully sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
                }
            }
        } else {
            Log::info("No price update alerts needed.");
        }

        $this->info('Price update frequency alerts checked successfully.');
    }
}
