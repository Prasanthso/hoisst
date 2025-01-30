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
            pm.price AS S_MRP,
            SUM(rfr.quantity * rm.price/rmst.Output) AS RM_Cost,
            SUM(((rfr.quantity * rm.price/rmst.Output)*100)/pm.price) AS RM_perc,
            SUM(pfr.quantity * pkm.price/rmst.Output) AS PM_Cost,
            SUM(((pfr.quantity * pkm.price/rmst.Output)*100)/pm.price) AS PM_perc,
            SUM(ofr.quantity * oh.price/rmst.Output) AS OH_Cost,
            SUM((((rfr.quantity * rm.price/rmst.Output)+(pfr.quantity * pkm.price/rmst.Output))*(ofr.quantity * oh.price/rmst.Output))/100) AS OH_perc,
            SUM((rfr.quantity * rm.price/rmst.Output)+(pfr.quantity * pkm.price/rmst.Output)) AS TOTAL,
            SUM((((rfr.quantity * rm.price/rmst.Output)+(pfr.quantity * pkm.price/rmst.Output))*100)/pm.price) AS Total_perc,
            SUM((rfr.quantity * rm.price/rmst.Output)+(pfr.quantity * pkm.price/rmst.Output)+((((rfr.quantity * rm.price/rmst.Output)+(pfr.quantity * pkm.price/rmst.Output))*(ofr.quantity * oh.price/rmst.Output))/100)) AS COST,
            SUM(pm.price*0.75) AS Selling_Cost,
            SUM(((pm.price*0.75)*100)/(100+18)) AS Before_tax,
            SUM((pm.price*0.75)-(((pm.price*0.75)*100)/(100+18))) AS Margin,
            SUM((((pm.price*0.75)-(((pm.price*0.75)*100)/(100+18)))/(((pm.price*0.75)*100)/(100+18)))*100) AS Margin_perc,
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
            GROUP BY 
                pm.id, pm.name, pm.price, rmst.Output
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
