<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PdPriceUpdateMail;
use App\Models\Product;
use App\Models\User;
use App\Models\PdPriceUpdateAlert;

use Carbon\Carbon;
use App\Http\Controllers\WhatsAppController;

class CheckPdPriceUpdates extends Command
{
    protected $signature = 'check:pd-price-updates';
    protected $description = 'Check price update frequency and notify users via email';

    public function handle()
    {
        Log::info("Running check:pd-price-updates command...");

        $storeId = session('store_id');

        $products = Product::where('status', 'active')
            ->where('store_id', $storeId)
            ->get();
        $now = Carbon::now();
        $materialsToNotify = [];

        if ($products->isEmpty()) {
            Log::warning("No active products found.");
            return;
        }

        foreach ($products as $product) {
            Log::info("Checking product: {$product->name}, ID: {$product->id}");

            // Get the last price update date
            $lastUpdate = DB::table('pd_price_histories')
                ->where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $lastUpdateDate = $lastUpdate ? Carbon::parse($lastUpdate->created_at) : Carbon::parse($product->created_at);
            $updateFrequency = strtolower(trim($product->update_frequency));
            $priceUpdateFrequency = (int) $product->price_update_frequency;
            $shouldNotify = false;

            if (!in_array($updateFrequency, ['days', 'weeks', 'monthly', 'yearly'])) {
                Log::warning("Invalid update_frequency for {$product->name}: {$updateFrequency}");
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
            Log::info("Product: {$product->name}, ID: {$product->id}");
            Log::info(" - Last update: {$lastUpdateDate->toDateTimeString()}");
            Log::info(" - Expected update interval: {$updateFrequency} {$priceUpdateFrequency}");
            Log::info(" - Threshold date for notification: {$checkDate->toDateTimeString()}");
            Log::info(" - Should notify? " . ($lastUpdateDate->lt($checkDate) ? 'Yes' : 'No'));

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$product->name}");
                $productsToNotify[] = [
                    'name' => $product->name,
                    'id' => $product->id,
                    'pdcode' => $product->pdcode,
                ];
            }
        }

        if (!empty($productsToNotify)) {
            $users = User::all();

            if ($users->isEmpty()) {
                Log::warning("No users found to notify.");
                return;
            }
            $whatsappController = new WhatsAppController();

            foreach ($users as $user) {
                $channel = 'email';
                Log::info("Sending email to: {$user->email}");

                try {
                    Mail::to($user->email)->send(new PdPriceUpdateMail($productsToNotify));
                    Log::info("Email successfully sent to {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
                }

                if ($user->whatsapp_enabled && $user->whatsapp_number) {
                    $channel = 'both';
                    Log::info("Sending WhatsApp message to: {$user->whatsapp_number}");

                    try {
                        $message = "Price Alert\n";
                        foreach ($productsToNotify as $product) {
                            $message .= "Product: {$product['name']} (Code: {$product['pdcode']})\n";
                        }
                        $message .= "\nPlease update the prices accordingly.";

                        $whatsappController->sendMessage($user->whatsapp_number, $message, 'whatsapp');
                        Log::info("WhatsApp message sent successfully to {$user->whatsapp_number}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send WhatsApp message to {$user->whatsapp_number}: " . $e->getMessage());
                        $channel = 'email'; // fallback
                    }
                }

                // ✅ Save alert (always)
                $pdIds = collect($productsToNotify)->pluck('id')->toArray();

                PdPriceUpdateAlert::create([
                    'user_id' => $user->id,
                    'product_ids' => $pdIds,
                    'alerted_at' => now(),
                    'channel' => $channel,
                ]);
            }
        } else {
            Log::info("No price update alerts needed.");
        }

        $this->info('Product price update frequency alerts checked successfully.');
    }
}
