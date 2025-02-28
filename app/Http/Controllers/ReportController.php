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
                pm.tax As tax,
                oc.suggested_mrp AS S_MRP,

                -- Get Raw Material IDs
                GROUP_CONCAT(DISTINCT rfr.raw_material_id ORDER BY rfr.raw_material_id ASC SEPARATOR ', ') AS RM_IDs,

                -- Get Raw Material Names based on RM IDs
                GROUP_CONCAT(DISTINCT rm.name ORDER BY rm.name ASC SEPARATOR ', ') AS RM_Names,

                -- Get Raw Material IDs
                GROUP_CONCAT(DISTINCT pfr.packing_material_id ORDER BY pfr.packing_material_id ASC SEPARATOR ', ') AS PM_IDs,

                -- Get Raw Material Names based on RM IDs
                GROUP_CONCAT(DISTINCT pkm.name ORDER BY pkm.name ASC SEPARATOR ', ') AS PM_Names,

                -- Raw Material Cost
                SUM(DISTINCT COALESCE(rfr.quantity, 0) * COALESCE(rm.price, 0) / COALESCE(rmst.Output, 1)) AS RM_Cost,

                -- Packing Material Cost
                SUM(DISTINCT COALESCE(pfr.quantity, 0) * COALESCE(pkm.price, 0) / COALESCE(rmst.Output, 1)) AS PM_Cost,
                
                -- Overhead Cost (excluding mofr.quantity from multiplication)
                SUM(
                    DISTINCT COALESCE(ofr.quantity, 0) * COALESCE(oh.price, 0) / COALESCE(rmst.Output, 1) +
                    COALESCE(mofr.price, 0) / COALESCE(rmst.Output, 1)
                ) AS OH_Cost,

                rmst.Output 
            FROM 
                product_master pm 
            JOIN 
                recipe_master rmst ON pm.id = rmst.product_id 
            LEFT JOIN 
                rm_for_recipe rfr ON rmst.product_id = rfr.product_id 
            LEFT JOIN 
                raw_materials rm ON rfr.raw_material_id = rm.id 
            LEFT JOIN 
                pm_for_recipe pfr ON rmst.product_id = pfr.product_id
            LEFT JOIN
                packing_materials pkm ON pfr.packing_material_id = pkm.id
            LEFT JOIN
                oh_for_recipe ofr ON rmst.product_id = ofr.product_id
            LEFT JOIN
                overheads oh ON ofr.overheads_id = oh.id
            LEFT JOIN 
                moh_for_recipe mofr ON rmst.product_id = mofr.product_id
            LEFT JOIN 
                overall_costing oc ON pm.id = oc.productId AND oc.status = 'active'
            WHERE 
                rmst.status = 'active' AND oc.suggested_mrp IS NOT NULL
            GROUP BY 
                pm.id, pm.name, pm.price, pm.tax, oc.suggested_mrp, rmst.Output, ofr.quantity
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
