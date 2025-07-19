<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\PackingMaterial;
use App\Models\Overhead;
use App\Models\RecipeMaster;
use App\Models\UniqueCode;

class BulkRecipe extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('RecipePricing.bulkRecipe');
    }

    public function importBulkRecipe(Request $request)
    {
        $storeId = (int) ($request->session()->get('store_id') ?? 1);
        $file = $request->file('file');

        if (!$file) {
            return back()->with('error', 'No file uploaded.');
        }

        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file)->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $skippedCodes = [];
        $importedRecipeIds = [];
        $currentRecipe = null;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                // Check for recipe header (PDxxxx)
                if (
                    isset($row['B'], $row['C'], $row['E']) &&
                    preg_match('/^PD\d+$/', trim($row['C']))
                ) {
                    $pdcode = trim($row['C']);
                    $output = $row['E'];

                    $product = \App\Models\Product::where([
                        ['pdcode',   $pdcode],
                        ['store_id', $storeId],
                        ['status',   'active'],
                    ])->first();

                    if (!$product) {
                        $skippedCodes[] = $pdcode . ' - ' . ($row['B'] ?? 'Unknown Product') . ' (product not found)';
                        $currentRecipe = null;
                        continue;
                    }

                    $existingRecipe = \App\Models\RecipeMaster::where([
                        ['product_id', $product->id],
                        ['status',     'active'],
                    ])->first();

                    if ($existingRecipe) {
                        $skippedCodes[] = $pdcode . ' - ' . $product->name . ' (recipe already exists)';
                        $currentRecipe = null;
                        continue;
                    }

                    $rpCode = UniqueCode::generateRpCode();

                    $currentRecipe = \App\Models\RecipeMaster::create([
                        'product_id' => $product->id,
                        'rpcode'     => $rpCode,
                        'Output'     => $output,
                        'uom'        => $product->uom,
                        'totalCost'  => 0,
                        'singleCost' => 0,
                        'status'     => 'active',
                        'store_id'   => $storeId,
                    ]);

                    $product->update(['recipe_created_status' => 'yes']);
                    $importedRecipeIds[] = $currentRecipe->id;

                    continue;
                }

                // Material rows: skip if not inside a valid recipe block or invalid format
                if (
                    !$currentRecipe ||
                    !isset($row['C']) ||
                    !preg_match('/^(RM|PM|OH)\d+$/', trim($row['C']))
                ) {
                    continue;
                }

                $code  = trim($row['C']);
                $qty   = (float) $row['D'];
                $uom   = trim($row['E']);
                $rate  = (float) $row['F'];
                $amt   = (float) $row['G'];

                switch (substr($code, 0, 2)) {
                    case 'RM':
                        $raw = \App\Models\RawMaterial::where([
                            ['rmcode',   $code],
                            ['store_id', $storeId],
                            ['status',   'active'],
                        ])->first();

                        if ($raw) {
                            DB::table('rm_for_recipe')->insert([
                                'raw_material_id' => $raw->id,
                                'product_id'      => $currentRecipe->product_id,
                                'quantity'        => $qty,
                                'code'            => $code,
                                'uom'             => $uom,
                                'price'           => $rate,
                                'amount'          => $amt,
                                'store_id'        => $storeId,
                                'created_at'      => now(),
                                'updated_at'      => now(),
                            ]);
                        } else {
                            $rawInactive = \App\Models\RawMaterial::where('rmcode', $code)->first();
                            $name = $rawInactive ? $rawInactive->name : 'Unknown';
                            $skippedCodes[] = $code . ' - ' . $name . ' (raw material not found)';
                        }
                        break;

                    case 'PM':
                        $pm = \App\Models\PackingMaterial::where([
                            ['pmcode',   $code],
                            ['store_id', $storeId],
                            ['status',   'active'],
                        ])->first();

                        if ($pm) {
                            DB::table('pm_for_recipe')->insert([
                                'packing_material_id' => $pm->id,
                                'product_id'          => $currentRecipe->product_id,
                                'quantity'            => $qty,
                                'code'                => $code,
                                'uom'                 => $uom,
                                'price'               => $rate,
                                'amount'              => $amt,
                                'store_id'            => $storeId,
                                'created_at'          => now(),
                                'updated_at'          => now(),
                            ]);
                        } else {
                            $pmInactive = \App\Models\PackingMaterial::where('pmcode', $code)->first();
                            $name = $pmInactive ? $pmInactive->name : 'Unknown';
                            $skippedCodes[] = $code . ' - ' . $name . ' (packing material not found)';
                        }
                        break;

                    case 'OH':
                        $oh = \App\Models\Overhead::where([
                            ['ohcode',   $code],
                            ['store_id', $storeId],
                            ['status',   'active'],
                        ])->first();

                        if ($oh) {
                            DB::table('oh_for_recipe')->insert([
                                'overheads_id' => $oh->id,
                                'product_id'   => $currentRecipe->product_id,
                                'quantity'     => $qty,
                                'code'         => $code,
                                'uom'          => $uom,
                                'price'        => $rate,
                                'amount'       => $amt,
                                'store_id'     => $storeId,
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]);
                        } else {
                            $ohInactive = \App\Models\Overhead::where('ohcode', $code)->first();
                            $name = $ohInactive ? $ohInactive->name : 'Unknown';
                            $skippedCodes[] = $code . ' - ' . $name . ' (overhead not found)';
                        }
                        break;
                }
            }

            DB::commit();

            $importedRecipes = \App\Models\RecipeMaster::with('product')
                ->whereIn('id', $importedRecipeIds)
                ->get();

            return view('RecipePricing.bulkRecipe', [
                'recipes' => $importedRecipes,
                'skippedCodes' => array_unique($skippedCodes),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
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
