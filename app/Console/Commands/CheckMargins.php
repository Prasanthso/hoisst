<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\LowMarginAlert as LowMarginAlertMail;
use App\Models\LowMarginAlert;
use App\Models\User;
use App\Http\Controllers\WhatsAppController;

class CheckMargins extends Command
{
    protected $signature = 'check:margins';
    protected $description = 'Check product margins and send notifications if low';

    public function handle()
    {
        $reports = DB::select("
            SELECT
                pm.id AS SNO,
                pm.name AS Product_Name,
                pm.price AS P_MRP,
                pm.tax AS tax,
                pm.margin AS margin,
                pm.store_id AS store_id,
                oc.suggested_mrp AS S_MRP,
                oc.discount AS discount,

                rm_total.RM_IDs,
                rm_total.RM_Names,
                pm_total.PM_IDs,
                pm_total.PM_Names,

                COALESCE(rm_total.RM_Cost, 0) / COALESCE(rmst.Output, 1) AS RM_Cost,
                COALESCE(pm_total.PM_Cost, 0) / COALESCE(rmst.Output, 1) AS PM_Cost,
                COALESCE(oh_total.Overhead_Cost, 0) / COALESCE(rmst.Output, 1) AS OH_Cost,
                COALESCE(moh_total.MOH_Cost, 0) / COALESCE(rmst.Output, 1) AS MOH_Cost,

                rmst.Output

            FROM
                product_master pm
            JOIN
                recipe_master rmst ON pm.id = rmst.product_id

            LEFT JOIN (
                SELECT
                    rfr.product_id,
                    GROUP_CONCAT(DISTINCT rfr.raw_material_id ORDER BY rfr.raw_material_id ASC SEPARATOR ', ') AS RM_IDs,
                    GROUP_CONCAT(DISTINCT rm.name ORDER BY rm.name ASC SEPARATOR ', ') AS RM_Names,
                    SUM(COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0)) AS RM_Cost
                FROM rm_for_recipe rfr
                JOIN raw_materials rm ON rfr.raw_material_id = rm.id
                GROUP BY rfr.product_id
            ) AS rm_total ON pm.id = rm_total.product_id

            LEFT JOIN (
                SELECT
                    pfr.product_id,
                    GROUP_CONCAT(DISTINCT pfr.packing_material_id ORDER BY pfr.packing_material_id ASC SEPARATOR ', ') AS PM_IDs,
                    GROUP_CONCAT(DISTINCT pkm.name ORDER BY pkm.name ASC SEPARATOR ', ') AS PM_Names,
                    SUM(COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0)) AS PM_Cost
                FROM pm_for_recipe pfr
                JOIN packing_materials pkm ON pfr.packing_material_id = pkm.id
                GROUP BY pfr.product_id
            ) AS pm_total ON pm.id = pm_total.product_id

            LEFT JOIN (
                SELECT
                    ofr.product_id,
                    SUM(COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0)) AS Overhead_Cost
                FROM oh_for_recipe ofr
                JOIN overheads oh ON ofr.overheads_id = oh.id
                GROUP BY ofr.product_id
            ) AS oh_total ON pm.id = oh_total.product_id

            LEFT JOIN (
                SELECT
                    product_id,
                    SUM(COALESCE(price, 0)) AS MOH_Cost
                FROM moh_for_recipe
                GROUP BY product_id
            ) AS moh_total ON pm.id = moh_total.product_id

            LEFT JOIN
                overall_costing oc ON pm.id = oc.productId AND oc.status = 'active'
            WHERE
                rmst.status = 'active' AND oc.suggested_mrp IS NOT NULL
            ORDER BY
                pm.name ASC;
        ");

        $whatsappController = new WhatsAppController();
        $storeUsers = User::whereNotNull('store_id')->get()->groupBy('store_id');

        $lowMarginProducts = [];

        foreach ($reports as $report) {
            $rm_perc = $report->RM_Cost * 100 / $report->P_MRP;
            $pm_perc = $report->PM_Cost * 100 / $report->P_MRP;
            $total = $report->RM_Cost + $report->PM_Cost;
            $cost = $total + $report->OH_Cost + $report->MOH_Cost;
            $sellingRate = ($report->P_MRP * 100) / (100 + $report->discount);
            $beforeTax = ($sellingRate * 100) / (100 + $report->tax);
            $MARGINAMOUNT = $beforeTax - $cost;
            $marginPerc = ($MARGINAMOUNT / $beforeTax) * 100;

            if ($marginPerc < $report->margin) {
                $lowMarginProducts[] = [
                    'id' => $report->SNO,
                    'name' => $report->Product_Name,
                    'margin' => round($marginPerc, 2),
                    'threshold' => $report->margin,
                    'store_id' => $report->store_id,
                ];
            }
        }

        $lowMarginProductsByStore = collect($lowMarginProducts)->groupBy('store_id');

        foreach ($lowMarginProductsByStore as $storeId => $products) {
            $users = $storeUsers[$storeId] ?? collect();

            foreach ($users as $user) {
                foreach ($products as $product) {
                    // Email
                    try {
                        Mail::to($user->email)->send(new LowMarginAlertMail([$product]));
                        Log::info("Low margin email sent to {$user->email}");
                    } catch (\Exception $e) {
                        Log::error("Error sending email to {$user->email}: " . $e->getMessage());
                    }

                    // WhatsApp
                    $channel = 'email';
                    if ($user->whatsapp_enabled && $user->whatsapp_number) {
                        $msg = "⚠️ Low Margin Alert:\n{$product['name']}: Margin {$product['margin']}% (Threshold: {$product['threshold']}%)\n\nPlease review pricing.";
                        try {
                            $whatsappController->sendMessage($user->whatsapp_number, $msg, 'whatsapp');
                            Log::info("WhatsApp message sent to {$user->whatsapp_number}");
                            $channel = 'both';
                        } catch (\Exception $e) {
                            Log::error("Error sending WhatsApp to {$user->whatsapp_number}: " . $e->getMessage());
                            $channel = 'email';
                        }
                    }

                    // Save alert history
                    LowMarginAlert::create([
                        'product_id' => $product['id'],
                        'user_id' => $user->id,
                        'store_id' => $product['store_id'],
                        'calculated_margin' => $product['margin'],
                        'threshold_margin' => $product['threshold'],
                        'alerted_at' => now(),
                        'channel' => $channel,
                    ]);
                }
            }
        }

        $this->info('Margin check completed.');
    }
}
