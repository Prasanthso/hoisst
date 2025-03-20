<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\LowMarginAlert;
use App\Models\Report;

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


        foreach ($reports as $report) {
            $rm_perc = $report->RM_Cost * 100 / $report->S_MRP;
            $pm_perc = $report->PM_Cost * 100 / $report->S_MRP;
            $total = $report->RM_Cost + $report->PM_Cost;
            $cost = $total + $report->OH_Cost + $report->MOH_Cost;
            $sellingRate = ($report->S_MRP * 100) / (100 + $report->discount);
            $beforeTax = ($sellingRate * 100) / (100 + $report->tax);
            $MARGINAMOUNT = $beforeTax - $cost;
            $marginPerc = ($MARGINAMOUNT / $beforeTax) * 100;

            Log::info("Checking margin for rm+pm=$total,beforeTax= $beforeTax,marginamount=$MARGINAMOUNT,oh=$report->OH_Cost, moh=$report->MOH_Cost, s.mrp =$report->S_MRP, marginPerc=$marginPerc, {$report->Product_Name}: Margin Percentage = $marginPerc, Threshold = {$report->margin}");

            if ($marginPerc < $report->margin) {
                Log::info("Sending email for product: {$report->Product_Name}");
                Mail::to('praswanth124@gmail.com')->send(new LowMarginAlert($report->Product_Name, $marginPerc));
            }
        }

        $this->info('Margin check completed.');
    }
}
