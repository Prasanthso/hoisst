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

        // Get all raw materials
        $rawMaterials = RawMaterial::all();
        $now = Carbon::now();

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

            // Determine last update date
            $lastUpdateDate = $lastUpdate ? Carbon::parse($lastUpdate->created_at) : Carbon::parse($material->created_at);
            $updateFrequency = strtolower($material->update_frequency);
            $priceUpdateFrequency = (int) $material->price_update_frequency;
            $shouldNotify = false;

            // Clone $now to prevent modification issues
            $checkDate = clone $now;

            // Validate update frequency
            if (!in_array($updateFrequency, ['days', 'weeks', 'monthly', 'yearly'])) {
                Log::warning("Invalid update_frequency for {$material->name}: {$updateFrequency}");
                continue;
            }

            // Check price update frequency
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

                // Get all users (modify if you need specific users)
                $users = User::all();

                if ($users->isEmpty()) {
                    Log::warning("No users found to notify.");
                    continue;
                }

                foreach ($users as $user) {
                    Log::info("Sending email to: {$user->email}");

                    // Sending email
                    try {
                        Mail::to($user->email)->send(new RmPriceUpdateMail($material, $material->price));
                        Log::info("Email successfully sent to {$user->email}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
                    }
                }
            } else {
                Log::info("No email needed for: {$material->name}");
            }
        }

        $this->info('Price update frequency alerts checked successfully.');
    }
}
