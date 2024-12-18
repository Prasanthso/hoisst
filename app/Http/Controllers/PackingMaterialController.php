<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryItems;
use App\Models\PackingMaterial;
use App\Models\UniqueCode;

class PackingMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('packingMaterial');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packingMaterialCategories = CategoryItems::getPackingMaterialCategory();// Fetch all category data
        return view('addPackingMaterial', compact('packingMaterialCategories')); // Match view name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'uom' => 'required|string|in:Ltr,Kgs',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categoryitems,id',
            'price' => 'required|string',
            'price_update_frequency' => 'required|string',
            'price_threshold' => 'required|string'
        ]);

        $categoryIds = $request->category_ids;

        $pmCode = UniqueCode::generatePmCode();

        try {
            PackingMaterial::create([
                'name' => $request->name,
                'pmcode' => $pmCode,
                'uom' => $request->uom,
                'category_id1' => $categoryIds[0] ?? null,
                'category_id2' => $categoryIds[1] ?? null,
                'category_id3' => $categoryIds[2] ?? null,
                'category_id4' => $categoryIds[3] ?? null,
                'category_id5' => $categoryIds[4] ?? null,
                'price' => $request->price,
                'price_update_frequency' => $request->price_update_frequency,
                'price_threshold' => $request->price_threshold,
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error inserting data: ' . $e->getMessage());
            dd($e->getMessage());
        }


        return redirect()->back()->with('success', 'Packing Material created successfully.');
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
