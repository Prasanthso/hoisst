<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = DB::select("
            SELECT 
    pm.id AS SNO, 
    pm.name AS Product_Name, 
    pm.price AS P_MRP,
    pm.tax AS tax,
    pm.margin AS margin,
    oc.suggested_mrp AS S_MRP,

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

        return view('report', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
