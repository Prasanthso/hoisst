<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function lowMarginAlert()
    {
        $reports = DB::select("
            SELECT
                pm.id AS SNO,
                pm.name AS Product_Name,
                pm.pdcode AS pdcode,
                pm.price AS P_MRP,
                pm.tax AS tax,
                pm.margin AS margin,
                oc.suggested_mrp AS S_MRP,
                oc.discount AS discount,

                -- Get Raw Material IDs
                rm_total.RM_IDs,

                -- Get Raw Material Names
                rm_total.RM_Names,

                -- Get Packing Material IDs
                pm_total.PM_IDs,

                -- Get Packing Material Names
                pm_total.PM_Names,

                -- Raw Material Cost (Divided by Output)
                COALESCE(rm_total.RM_Cost, 0) / COALESCE(rmst.Output, 1) AS RM_Cost,

                -- Packing Material Cost (Divided by Output)
                COALESCE(pm_total.PM_Cost, 0) / COALESCE(rmst.Output, 1) AS PM_Cost,

                -- Overhead Cost (Divided by Output)
                COALESCE(oh_total.Overhead_Cost, 0) / COALESCE(rmst.Output, 1) AS OH_Cost,

                -- Manufacturing Overhead Cost (Divided by Output)
                COALESCE(moh_total.MOH_Cost, 0) / COALESCE(rmst.Output, 1) AS MOH_Cost,

                -- Output
                rmst.Output

            FROM
                product_master pm
            JOIN
                recipe_master rmst ON pm.id = rmst.product_id

            -- Aggregate Raw Material Cost Separately
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

            -- Aggregate Packing Material Cost Separately
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

            -- Aggregate Overhead Cost Separately
            LEFT JOIN (
                SELECT
                    ofr.product_id,
                    SUM(COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0)) AS Overhead_Cost
                FROM oh_for_recipe ofr
                JOIN overheads oh ON ofr.overheads_id = oh.id
                GROUP BY ofr.product_id
            ) AS oh_total ON pm.id = oh_total.product_id

            -- Aggregate Manufacturing Overhead Cost Separately
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
                    'id' => $report->SNO, // or use $report->id if your alias is different
                    'name' => $report->Product_Name,
                    'pdcode' => $report->pdcode,
                    'margin' => round($marginPerc, 2),
                    'threshold' => $report->margin,
                ];
            }
        }
        $lowMarginProducts = collect($lowMarginProducts);
        return view('notification.lowmarginNotification', compact('lowMarginProducts'));
    }

    /**
     * Show Product Alert Notification 
     */
    public function productAlertNotification()
    {
        $product = Product::where('status', 'active')->get();
        $productPriceThreshold = [];

        foreach ($product as $product) {
            if (is_numeric($product->price) && is_numeric($product->price_threshold)) {
                if ((float)$product->price > (float)$product->price_threshold) {
                    Log::info("Threshold exceeded: {$product->name} (Price: {$product->price}, Threshold: {$product->price_threshold})");
                    $productPriceThreshold[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'pdcode' => $product->pdcode,
                        'price' => $product->price,
                        'threshold' => $product->price_threshold,
                    ];
                }
            }
        }

        $products = Product::where('status', 'active')->get();

        $now = Carbon::now();

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

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$product->name}");
                $productPriceAlert[] = [
                    'name' => $product->name,
                    'id' => $product->id,
                    'pdcode' => $product->pdcode,
                ];
            }
        }

        $productPriceThresholdCollection = collect($productPriceThreshold);

        $productPriceAlertCollection = collect($productPriceAlert);

        return view('notification.productNotification', compact('productPriceThresholdCollection', 'productPriceAlertCollection'));
    }

    /**
     * Show Raw Material Alert Notification 
     */
    public function rawmaterialAlertNotification()
    {
        $rawMaterials = RawMaterial::where('status', 'active')->get();
        $rawMaterialsPriceThreshold = [];

        foreach ($rawMaterials as $rawMaterial) {
            if (is_numeric($rawMaterial->price) && is_numeric($rawMaterial->price_threshold)) {
                if ((float)$rawMaterial->price > (float)$rawMaterial->price_threshold) {
                    Log::info("Threshold exceeded: {$rawMaterial->name} (Price: {$rawMaterial->price}, Threshold: {$rawMaterial->price_threshold})");
                    $rawMaterialsPriceThreshold[] = [
                        'id' => $rawMaterial->id,
                        'name' => $rawMaterial->name,
                        'rmcode' => $rawMaterial->rmcode,
                        'price' => $rawMaterial->price,
                        'threshold' => $rawMaterial->price_threshold,
                    ];
                }
            }
        }

        $rawMaterials = RawMaterial::where('status', 'active')->get();
        $now = Carbon::now();

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

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$material->name}");
                $rawMaterialsPriceAlert[] = [
                    'name' => $material->name,
                    'id' => $material->id,
                    'rmcode' => $material->rmcode,
                ];
            }
        }

        $rawMaterialsPriceThresholdCollection = collect($rawMaterialsPriceThreshold);

        $rawMaterialsPriceAlertCollection = collect($rawMaterialsPriceAlert);

        return view('notification.rawmaterialNotification', compact('rawMaterialsPriceThresholdCollection', 'rawMaterialsPriceAlertCollection'));
    }

    /**
     * Show Packing Material Alert Notification 
     */
    public function packingmaterialAlertNotification()
    {
        $packingMaterials = PackingMaterial::where('status', 'active')->get();
        $packingMaterialsPriceThreshold = [];

        foreach ($packingMaterials as $packingMaterial) {
            if (is_numeric($packingMaterial->price) && is_numeric($packingMaterial->price_threshold)) {
                if ((float)$packingMaterial->price > (float)$packingMaterial->price_threshold) {
                    Log::info("Threshold exceeded: {$packingMaterial->name} (Price: {$packingMaterial->price}, Threshold: {$packingMaterial->price_threshold})");
                    $packingMaterialsPriceThreshold[] = [
                        'id' => $packingMaterial->id,
                        'name' => $packingMaterial->name,
                        'pmcode' => $packingMaterial->pmcode,
                        'price' => $packingMaterial->price,
                        'threshold' => $packingMaterial->price_threshold,
                    ];
                }
            }
        }

        $packingMaterials = PackingMaterial::where('status', 'active')->get();
        $now = Carbon::now();

        if ($packingMaterials->isEmpty()) {
            Log::warning("No raw materials found.");
            return;
        }

        foreach ($packingMaterials as $material) {
            Log::info("Checking packing material: {$material->name}, ID: {$material->id}");

            // Get the last update date
            $lastUpdate = DB::table('pm_price_histories')
                ->where('packing_material_id', $material->id)
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

            if ($lastUpdateDate->lt($checkDate)) {
                Log::info("Price update alert needed for: {$material->name}");
                $packingMaterialsPriceAlert[] = [
                    'name' => $material->name,
                    'id' => $material->id,
                    'pmcode' => $material->pmcode,
                ];
            }
        }

        $packingMaterialsPriceThresholdCollection = collect($packingMaterialsPriceThreshold);

        $packingMaterialsPriceAlertCollection = collect($packingMaterialsPriceAlert);

        return view('notification.packingmaterialNotification', compact('packingMaterialsPriceThresholdCollection', 'packingMaterialsPriceAlertCollection'));
    }


}
