<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CategoryItems;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\OverallCosting;
use App\Models\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $categoryitems = CategoryItems::pdCategoryItem($storeid);
        $selectedCategoryIds = $request->input('category_ids', []);
        $searchValue = $request->input('pdText', '');
        $statusValue = $request->input('statusValue', 'active');

        // $costs = $this->getAllProductsABCcosts($storeid, $statusValue);
        // $unitcost = where('store_id', $storeid)->value('id');
        if ($request->ajax()) {
            if (!empty($searchValue)) {
                $product = DB::table('product_master as pd')
                    ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
                    ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
                    ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
                    ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
                    ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
                     ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
                  ->leftJoin('rm_for_recipe as rmr', 'pd.id', '=', 'rmr.product_id')
                ->leftJoin('pm_for_recipe as pmr', 'pd.id', '=', 'pmr.product_id')
                ->leftJoin('oh_for_recipe as ohr', 'pd.id', '=', 'ohr.product_id')
                ->leftJoin('moh_for_recipe as moh', 'pd.id', '=', 'moh.product_id')
                ->leftJoin('overall_costing as oc', function($join) {
                    $join->on('pd.id', '=', 'oc.productId')
                        ->where('oc.status', '=', 'active'); // assuming 1 means active
                })
            // ->leftJoin('overall_costing as oc', 'pd.id', '=', 'oc.productId')
                ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
                ->leftJoin('raw_materials as rm', 'rmr.raw_material_id', '=', 'rm.id')
                        ->leftJoin('packing_materials as pm', 'pmr.packing_material_id', '=', 'pm.id')
                        ->leftJoin('overheads as oh', 'ohr.overheads_id', '=', 'oh.id')
                    ->select(
                        'pd.id',
                        'pd.name',
                        'pd.pdcode',
                        'pd.price',
                        'pd.uom',
                        'c1.itemname as category_name1',
                        'c2.itemname as category_name2',
                        'c3.itemname as category_name3',
                        'c4.itemname as category_name4',
                        'c5.itemname as category_name5',
                        'c6.itemname as category_name6',
                        'c7.itemname as category_name7',
                        'c8.itemname as category_name8',
                        'c9.itemname as category_name9',
                        'c10.itemname as category_name10',
                        'pd.status',

                    DB::raw('
                (
                    (
                        (
                            (
                                SUM(DISTINCT rmr.quantity * rm.price) +
                                SUM(DISTINCT pmr.quantity * pm.price) +
                                COALESCE(SUM(DISTINCT ohr.quantity * oh.price), SUM(DISTINCT moh.price))
                            ) / rp.Output
                        ) *
                        (1 + (oc.margin / 100))
                    ) *
                    (1 + (pd.tax / 100))
                ) *
                (1 + (oc.discount / 100))
                AS pdCost
            ')
                    )
                    // ->where('pd.status', $statusValue) // Filter by active status
                    ->where('pd.store_id', $storeid)
                    ->where('pd.name', 'LIKE', "{$searchValue}%")
                    ->groupBy('pd.id', 'pd.name', 'pd.pdcode', 'pd.price', 'pd.uom','pd.status','c1.itemname','c2.itemname','c3.itemname','c4.itemname','c5.itemname','c6.itemname','c7.itemname','c8.itemname','c9.itemname','c10.itemname','pd.tax','rp.Output','oc.margin','oc.discount')
                    ->get();
                // Return filtered raw materials as JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => count($product) > 0 ? 'Products found' : 'No products found',
                    'product' => $product
                ]);
            }

            if(!empty($selectedCategoryIds)) {
                $selectedCategoryIds = explode(',', $selectedCategoryIds);
                $selectedCategoryIds = array_filter($selectedCategoryIds, fn($id) => is_numeric($id) && $id > 0);

                // // If no categories are selected, return all products
                // if (empty($selectedCategoryIds)) {
                //     return response()->json([
                //         'status' => 'success',
                //         'message' => 'No category IDs provided',
                //         'product' => []
                //     ]);
                // }
                // Fetch PRODUCTS filtered by the selected category IDs
                $product = DB::table('product_master as pd')
                    ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
                    ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
                    ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
                    ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
                    ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
                    ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
                   ->leftJoin('rm_for_recipe as rmr', 'pd.id', '=', 'rmr.product_id')
                ->leftJoin('pm_for_recipe as pmr', 'pd.id', '=', 'pmr.product_id')
                ->leftJoin('oh_for_recipe as ohr', 'pd.id', '=', 'ohr.product_id')
                ->leftJoin('moh_for_recipe as moh', 'pd.id', '=', 'moh.product_id')
                 ->leftJoin('overall_costing as oc', function($join) {
                    $join->on('pd.id', '=', 'oc.productId')
                        ->where('oc.status', '=', 'active'); // assuming 1 means active
                })
            // ->leftJoin('overall_costing as oc', 'pd.id', '=', 'oc.productId')
                ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
                ->leftJoin('raw_materials as rm', 'rmr.raw_material_id', '=', 'rm.id')
                        ->leftJoin('packing_materials as pm', 'pmr.packing_material_id', '=', 'pm.id')
                        ->leftJoin('overheads as oh', 'ohr.overheads_id', '=', 'oh.id')
                    ->select(
                        'pd.id',
                        'pd.name',
                        'pd.pdcode',
                        'pd.price',
                        'pd.uom',
                        'c1.itemname as category_name1',
                        'c2.itemname as category_name2',
                        'c3.itemname as category_name3',
                        'c4.itemname as category_name4',
                        'c5.itemname as category_name5',
                        'c6.itemname as category_name6',
                        'c7.itemname as category_name7',
                        'c8.itemname as category_name8',
                        'c9.itemname as category_name9',
                        'c10.itemname as category_name10',
                        'pd.status',
                    DB::raw('
                (
                    (
                        (
                            (
                                SUM(DISTINCT rmr.quantity * rm.price) +
                                SUM(DISTINCT pmr.quantity * pm.price) +
                                COALESCE(SUM(DISTINCT ohr.quantity * oh.price), SUM(DISTINCT moh.price))
                            ) / rp.Output
                        ) *
                        (1 + (oc.margin / 100))
                    ) *
                    (1 + (pd.tax / 100))
                ) *
                (1 + (oc.discount / 100))
                AS pdCost
            ')
                    )
                        ->where(function ($query) use ($selectedCategoryIds) {
                        $query->whereIn('c1.id', $selectedCategoryIds)
                            ->orWhereIn('c2.id', $selectedCategoryIds)
                            ->orWhereIn('c3.id', $selectedCategoryIds)
                            ->orWhereIn('c4.id', $selectedCategoryIds)
                            ->orWhereIn('c5.id', $selectedCategoryIds)
                            ->orWhereIn('c6.id', $selectedCategoryIds)
                            ->orWhereIn('c7.id', $selectedCategoryIds)
                            ->orWhereIn('c8.id', $selectedCategoryIds)
                            ->orWhereIn('c9.id', $selectedCategoryIds)
                            ->orWhereIn('c10.id', $selectedCategoryIds);
                    })
                    // ->where('pd.status', $statusValue) // Filter by active status
                    ->where('pd.store_id', $storeid)
                     ->groupBy('pd.id', 'pd.name', 'pd.pdcode', 'pd.price', 'pd.uom','pd.status','c1.itemname','c2.itemname','c3.itemname','c4.itemname','c5.itemname','c6.itemname','c7.itemname','c8.itemname','c9.itemname','c10.itemname','pd.tax','rp.Output','oc.margin','oc.discount')
                    ->orderBy('pd.name', 'asc')
                    ->get();
                // Return filtered raw materials as JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => count($product) > 0 ? 'Products found' : 'No products found',
                    'product' => $product
                ]);
            }
              if (!empty($statusValue)) {

                $product = DB::table('product_master as pd')
                    ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
                    ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
                    ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
                    ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
                    ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
                    ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
                    ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
                    ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
                    ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
                    ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
                ->leftJoin('rm_for_recipe as rmr', 'pd.id', '=', 'rmr.product_id')
                ->leftJoin('pm_for_recipe as pmr', 'pd.id', '=', 'pmr.product_id')
                ->leftJoin('oh_for_recipe as ohr', 'pd.id', '=', 'ohr.product_id')
                ->leftJoin('moh_for_recipe as moh', 'pd.id', '=', 'moh.product_id')
               ->leftJoin('overall_costing as oc', function($join) {
                    $join->on('pd.id', '=', 'oc.productId')
                        ->where('oc.status', '=', 'active'); // assuming 1 means active
                })
            // ->leftJoin('overall_costing as oc', 'pd.id', '=', 'oc.productId')
                ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
                ->leftJoin('raw_materials as rm', 'rmr.raw_material_id', '=', 'rm.id')
                        ->leftJoin('packing_materials as pm', 'pmr.packing_material_id', '=', 'pm.id')
                        ->leftJoin('overheads as oh', 'ohr.overheads_id', '=', 'oh.id')
                    ->select(
                        'pd.id',
                        'pd.name',
                        'pd.pdcode',
                        'pd.price',
                        'pd.uom',
                        'c1.itemname as category_name1',
                        'c2.itemname as category_name2',
                        'c3.itemname as category_name3',
                        'c4.itemname as category_name4',
                        'c5.itemname as category_name5',
                        'c6.itemname as category_name6',
                        'c7.itemname as category_name7',
                        'c8.itemname as category_name8',
                        'c9.itemname as category_name9',
                        'c10.itemname as category_name10',
                        'pd.status',

                    DB::raw('
                (
                    (
                        (
                            (
                                SUM(DISTINCT rmr.quantity * rm.price) +
                                SUM(DISTINCT pmr.quantity * pm.price) +
                                COALESCE(SUM(DISTINCT ohr.quantity * oh.price), SUM(DISTINCT moh.price))
                            ) / rp.Output
                        ) *
                        (1 + (oc.margin / 100))
                    ) *
                    (1 + (pd.tax / 100))
                ) *
                (1 + (oc.discount / 100))
                AS pdCost
            ')
                )
                    ->where('pd.status', $statusValue) // Filter by active status
                    ->where('pd.store_id', $storeid)
                     ->groupBy('pd.id', 'pd.name', 'pd.pdcode', 'pd.price', 'pd.uom','pd.status','c1.itemname','c2.itemname','c3.itemname','c4.itemname','c5.itemname','c6.itemname','c7.itemname','c8.itemname','c9.itemname','c10.itemname','pd.tax','rp.Output','oc.margin','oc.discount')
                    ->orderBy('pd.name', 'asc')
                    // ->where('pd.name', 'LIKE', "{$searchValue}%")
                    ->get();
                // Return filtered raw materials as JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => count($product) > 0 ? 'Products found' : 'No products found',
                    'product' => $product
                ]);
            }
        }

        // Default view, return all raw materials and category items
        $product = DB::table('product_master as pd')
            ->leftJoin('categoryitems as c1', 'pd.category_id1', '=', 'c1.id')
            ->leftJoin('categoryitems as c2', 'pd.category_id2', '=', 'c2.id')
            ->leftJoin('categoryitems as c3', 'pd.category_id3', '=', 'c3.id')
            ->leftJoin('categoryitems as c4', 'pd.category_id4', '=', 'c4.id')
            ->leftJoin('categoryitems as c5', 'pd.category_id5', '=', 'c5.id')
            ->leftJoin('categoryitems as c6', 'pd.category_id6', '=', 'c6.id')
            ->leftJoin('categoryitems as c7', 'pd.category_id7', '=', 'c7.id')
            ->leftJoin('categoryitems as c8', 'pd.category_id8', '=', 'c8.id')
            ->leftJoin('categoryitems as c9', 'pd.category_id9', '=', 'c9.id')
            ->leftJoin('categoryitems as c10', 'pd.category_id10', '=', 'c10.id')
                ->leftJoin('rm_for_recipe as rmr', 'pd.id', '=', 'rmr.product_id')
            ->leftJoin('pm_for_recipe as pmr', 'pd.id', '=', 'pmr.product_id')
            ->leftJoin('oh_for_recipe as ohr', 'pd.id', '=', 'ohr.product_id')
            ->leftJoin('moh_for_recipe as moh', 'pd.id', '=', 'moh.product_id')
            ->leftJoin('overall_costing as oc', function($join) {
                    $join->on('pd.id', '=', 'oc.productId')
                        ->where('oc.status', '=', 'active'); // assuming 1 means active
                })
            // ->leftJoin('overall_costing as oc', 'pd.id', '=', 'oc.productId')
            ->leftJoin('recipe_master as rp', 'pd.id', '=', 'rp.product_id')
            ->leftJoin('raw_materials as rm', 'rmr.raw_material_id', '=', 'rm.id')
            ->leftJoin('packing_materials as pm', 'pmr.packing_material_id', '=', 'pm.id')
            ->leftJoin('overheads as oh', 'ohr.overheads_id', '=', 'oh.id')
            ->select(
                'pd.id',
                'pd.name',
                'pd.pdcode',
                'pd.price',
                'pd.uom',
                'c1.itemname as category_name1',
                'c2.itemname as category_name2',
                'c3.itemname as category_name3',
                'c4.itemname as category_name4',
                'c5.itemname as category_name5',
                'c6.itemname as category_name6',
                'c7.itemname as category_name7',
                'c8.itemname as category_name8',
                'c9.itemname as category_name9',
                'c10.itemname as category_name10',
                'pd.status',

                    DB::raw('
                (
                    (
                        (
                            (
                                SUM(DISTINCT rmr.quantity * rm.price) +
                                SUM(DISTINCT pmr.quantity * pm.price) +
                                COALESCE(SUM(DISTINCT ohr.quantity * oh.price), SUM(DISTINCT moh.price))
                            ) / rp.Output
                        ) *
                        (1 + (oc.margin / 100))
                    ) *
                    (1 + (pd.tax / 100))
                ) *
                (1 + (oc.discount / 100))
                AS pdCost
            ')
            )
            // ->where('pd.status', $statusValue) // Filter by active status
            ->where('pd.store_id', $storeid)
             ->groupBy('pd.id', 'pd.name', 'pd.pdcode', 'pd.price', 'pd.uom','pd.status','c1.itemname','c2.itemname','c3.itemname','c4.itemname','c5.itemname','c6.itemname','c7.itemname','c8.itemname','c9.itemname','c10.itemname','pd.tax','rp.Output','oc.margin','oc.discount')
        // ,'rmr.quantity','pmr.quantity','ohr.quantity','rm.price','pm.price','oh.price','moh.price'
             ->orderBy('pd.name', 'asc')
            ->paginate(10);

        return view('product.products', compact('product', 'categoryitems'));
    }


/*
   public function getAllProductsABCcosts($storeId, $statusValue)
{
    $products = Product::where('store_id', $storeId)
        ->where('status', $statusValue)
        ->get();

    $results = [];

    foreach ($products as $product) {
        $productId = $product->id;

        $totalRmCost = DB::table('rm_for_recipe')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->sum('amount');

        $totalPmCost = DB::table('pm_for_recipe')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->sum('amount');

        $totalOhCost = DB::table('oh_for_recipe')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->sum('amount');

        if (empty($totalOhCost)) {
            $totalOhCost = DB::table('moh_for_recipe')
                ->where('product_id', $productId)
                ->where('store_id', $storeId)
                ->sum('price');
        }

        $totalCost = $totalRmCost + $totalPmCost + $totalOhCost;

        $recipe = DB::table('recipe_master')
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->where('status', $statusValue)
            ->first();

      $outputQty = isset($recipe->rpoutput) ? $recipe->rpoutput : 1;

        $unitCost = $outputQty > 0 ? ($totalCost / $outputQty) : 0;

        // Get margin and discount for product
        $costing = DB::table('overall_costing')
            ->where('productId', $productId)
            ->where('store_id', $storeId)
            ->first();

        $margin = isset($costing) && isset($costing->margin) ? $costing->margin : 0;
        $discount = isset($costing) && isset($costing->discount) ? $costing->discount : 0;

        $unitCostWithMargin = $unitCost + ($unitCost * $margin / 100);
        $finalUnitCost = $unitCostWithMargin - ($unitCostWithMargin * $discount / 100);

        $results[] = [
            'product_id' => $productId,
            'product_name' => $product->name,
            'unit_cost' => round($unitCost, 2),
            'margin' => $margin,
            'discount' => $discount,
            'final_cost' => round($finalUnitCost, 2),
        ];
    }

    return $results;
}
    */

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $product = CategoryItems::pdCategoryItem($storeid);
        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();
        return view('product.addProduct', compact('product', 'itemtype')); // Match view name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    // 'unique:product_master,name',
                    function ($attribute, $value, $fail) use ($storeid) {
                        // Convert input to lowercase and remove spaces
                        $formattedValue = strtolower(str_replace(' ', '', $value));
                        // Fetch existing names from the database (case-insensitive)
                        $existingNames = Product::where('store_id', $storeid)->pluck('name')->map(function ($name) {
                            return strtolower(str_replace(' ', '', $name));
                        })->toArray();
                        if (in_array($formattedValue, $existingNames)) {
                            $fail('This name is duplicate. Please choose a different one.');
                        }
                    }
                ],   //'name' => 'required|string|max:255',
                'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
                'category_ids' => 'required|array',
                'category_ids.*' => 'integer|exists:categoryitems,id',
                'price' => 'required|string',
                // 'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
                // 'price_update_frequency' => 'required|string',
                // 'price_threshold' => 'required|string',
                'hsnCode' => 'required|string',
                // 'itemType_id' => 'integer|exists:item_type,id',
                'itemWeight' => 'required|string',
                'tax' => 'required|string',
            ]);

            $categoryIds = $request->category_ids;

            $pdCode = UniqueCode::generatePdCode();
            $stocks = ' ';
            $val = ' ';
            try {
                Product::create([
                    'name' => $request->name,
                    'pdcode' => $pdCode,
                    'hsnCode' => $request->hsnCode,
                    'uom' => $request->uom,
                    'itemWeight' => $request->itemWeight,
                    'category_id1' => $categoryIds[0] ?? null,
                    'category_id2' => $categoryIds[1] ?? null,
                    'category_id3' => $categoryIds[2] ?? null,
                    'category_id4' => $categoryIds[3] ?? null,
                    'category_id5' => $categoryIds[4] ?? null,
                    'category_id6' => $categoryIds[5] ?? null,
                    'category_id7' => $categoryIds[6] ?? null,
                    'category_id8' => $categoryIds[7] ?? null,
                    'category_id9' => $categoryIds[8] ?? null,
                    'category_id10' => $categoryIds[9] ?? null,
                    'itemType_id' => $request->itemType_id ?? null,
                    'purcCost' => $request->purcCost ?? null,
                    'margin' => $request->margin,
                    'price' => $request->price,
                    'tax' => $request->tax,
                    'update_frequency' => !empty($request->update_frequency) ? $request->update_frequency : $val,
                    'price_update_frequency' => !empty($request->price_update_frequency) ? $request->price_update_frequency : $val,
                    'price_threshold' => !empty($request->price_threshold) ? $request->price_threshold : $val,
                    'minimum_stock_unit' => $stocks,
                    'minimum_stock_qty' => $stocks,
                    'store_id' => $storeid,
                ]);
            } catch (\Exception $e) {
                // \Log::error('Error inserting data: ' . $e->getMessage());
                dd($e->getMessage());
            }
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong! Could not save data.')
                ->withInput();
        }
    }

    public function updatePrices(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $validatedData = $request->validate([
            'updatedMaterials' => 'required|array',
            'updatedMaterials.*.id' => 'required|exists:product_master,id',
            'updatedMaterials.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validatedData,$storeid) {
                foreach ($validatedData['updatedMaterials'] as $material) {
                    // Fetch the current material
                    $currentMaterial = Product::where('store_id', $storeid)->where('id', $material['id'])->first();

                    // Check if the price has changed
                    if ($currentMaterial->price != $material['price']) {
                        // Log the price update in the rm_price_histories table
                        DB::table('pd_price_histories')->insert([
                            'product_id' => $currentMaterial->id,
                            'old_price' => $currentMaterial->price,
                            'new_price' => $material['price'],
                            'updated_by' => 1, // Ensure user is authenticated
                            'store_id' => $storeid,
                            'updated_at' => now(),
                        ]);

                        // Update the raw material price
                        $currentMaterial->update(['price' => $material['price']]);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Prices updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating prices.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function getPdPriceHistory(Request $request, $id)
    {
        $storeid = $request->session()->get('store_id');
        $priceHistory = DB::table('pd_price_histories')
            ->where('product_id', $id)
            ->where('store_id', $storeid)
            ->orderBy('updated_at', 'desc') // Replace 'id' with the column you want to sort by
            ->get();
        return response()->json(['priceDetails' => $priceHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        // Fetch all categories
        // $productCategories = DB::table('categoryitems')->get();
        $storeid = $request->session()->get('store_id');
        $productCategories = CategoryItems::pdCategoryItem($storeid);
        $itemtype = DB::table('item_type')->where('status', '=', 'active')->where('store_id', 0)->get();
        $selectedItemType = $product->itemType_id ?? null;
        // Fetch the specific raw material by its ID
        $product = DB::table('product_master')->where('store_id', $storeid)->where('id', $id)->first(); // Fetch the single raw material entry

        // Return the view with raw material data and categories
        return view('product.editProduct', compact('product', 'productCategories', 'itemtype', 'selectedItemType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $storeid = $request->session()->get('store_id');
        // $product = Product::findOrFail($id);
        $product = Product::where('store_id', $storeid)
                            ->where('id', $id)
                            ->firstOrFail();
        try {
            // for duplicate
            $strName = strtolower(preg_replace('/\s+/', '', $request->name));
            // $strHsnCode = strtolower(preg_replace('/\s+/', '', $request->hsnCode));

            // Check for existing product with the same normalized name or HSN code
            $existingProduct = Product::where(function ($query) use ($strName) {
                $query->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", [$strName]);
                // ->orWhereRaw("LOWER(REPLACE(hsnCode, ' ', '')) = ?", [$strHsnCode]);
            })
                ->where('id', '!=', $product->id) // Exclude the current product
                ->where('store_id', $storeid)
                ->first();

            if ($existingProduct) {
                // if ($strName == strtolower(preg_replace('/\s+/', '', $existingProduct->name)) &&
                //     $strHsnCode == strtolower(preg_replace('/\s+/', '', $existingProduct->hsnCode))) {
                //     return redirect()->back()->with('error', 'Both Product Name and HSN Code already exist.');
                // }
                if ($strName == strtolower(preg_replace('/\s+/', '', $existingProduct->name))) {
                    return redirect()->back()->with('error', 'Product Name already exists.');
                }
                // elseif ($strHsnCode == strtolower(preg_replace('/\s+/', '', $existingProduct->hsnCode))) {
                //     return redirect()->back()->with('error', 'HSN Code already exists.');
                // }
            }
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255',
                'uom' => 'required|string|in:Ltr,Kgm,Gm,Nos',
                'category_ids' => 'required|array',
                'category_ids.*' => 'integer|exists:categoryitems,id',
                'price' => 'required|string',
                // 'update_frequency' => 'required|string|in:Days,Weeks,Monthly,Yearly',
                // 'price_update_frequency' => 'required|string',
                // 'price_threshold' => 'required|string',
                'hsnCode' => 'required|string',
                // 'itemType_id' => 'integer|exists:item_type,id',
                'itemWeight' => 'required|string',
                'tax' => 'required|string',
            ]);

            $categoryIds = $request->category_ids;
            $val = ' ';
            if ($product->price != $request->price) {
                DB::table('pd_price_histories')->insert([
                    'product_id' => $product->id,
                    'old_price' => $product->price, // Correct way to get the old price
                    'new_price' => $request->price,
                    'updated_by' => 1, // Ensure user is authenticated
                    'store_id' => $storeid,
                    'updated_at' => now(),
                ]);
            }

            try {
                // Update the raw material record
                $product->update([
                    'name' => $request->name,
                    'hsnCode' => $request->hsnCode,
                    'uom' => $request->uom,
                    'itemWeight' => $request->itemWeight,
                    'category_id1' => $categoryIds[0] ?? null,
                    'category_id2' => $categoryIds[1] ?? null,
                    'category_id3' => $categoryIds[2] ?? null,
                    'category_id4' => $categoryIds[3] ?? null,
                    'category_id5' => $categoryIds[4] ?? null,
                    'category_id6' => $categoryIds[5] ?? null,
                    'category_id7' => $categoryIds[6] ?? null,
                    'category_id8' => $categoryIds[7] ?? null,
                    'category_id9' => $categoryIds[8] ?? null,
                    'category_id10' => $categoryIds[9] ?? null,
                    'itemType_id' => $request->itemType_id ?? null,
                    'purcCost' => $request->purcCost ?? null,
                    'margin' => $request->margin,
                    'price' => $request->price,
                    'tax' => $request->tax,
                    'update_frequency' => $request->update_frequency ?? $val,
                    'price_update_frequency' => $request->price_update_frequency ?? $val,
                    'price_threshold' => $request->price_threshold ?? $val,
                    'status' => $request->status,
                    'store_id' => $storeid,
                ]);
            } catch (\Exception $e) {
                // Handle the error gracefully (e.g., log it and show an error message)
                // \Log::error('Error updating raw material: ' . $e->getMessage());
                return redirect()->back()->with('error', 'There was an issue updating the.');
            }
            // Return a success message and redirect back
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong! Could not update data.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteConfirmation(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'

            // $updatedCount = Product::whereIn('id', $ids)
            // ->whereNotExists(function ($query) {
            //     $query->select(DB::raw(1))
            //         ->from('recipedetails')
            //         ->whereColumn('recipedetails.product_id', 'product_master.id'); // Ensure correct column name
            // })
            // ->update(['status' => 'inactive']);

            $updatedCount = Product::where('store_id',$storeid)
            ->whereIn('id', $ids)
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('recipedetails')
                        ->where('store_id',$storeid)->where('status', '=', 'active')
                        ->whereColumn('recipedetails.product_id', 'product_master.id');
                })
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('recipe_master')
                        ->where('store_id',$storeid)->where('status', '=', 'active')
                        ->whereColumn('recipe_master.product_id', 'product_master.id');
                })
                ->update(['status' => 'inactive']);

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0 ? 'Products marked as inactive successfully.' : 'No Products were updated.',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Products: ' . $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $ids = $request->input('ids'); // Get the 'ids' array from the request

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No valid IDs provided.']);
        }

        try {
            // Update the status of raw materials to 'inactive'
            $itemsToDelete = Product::where('store_id',$storeid)
                ->whereIn('id', $ids)
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('recipedetails')
                        ->where('store_id',$storeid)->where('status', '=', 'active')
                        ->whereColumn('recipedetails.product_id', 'product_master.id');
                })
                ->whereNotExists(function ($query) use ($storeid) {
                    $query->select(DB::raw(1))
                        ->from('recipe_master')
                        ->where('store_id',$storeid)->where('status', '=', 'active')
                        ->whereColumn('recipe_master.product_id', 'product_master.id');
                })
                ->get();

            // If there are items that can be deleted, return a confirmation message
            if ($itemsToDelete->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'confirm' => true,
                    'message' => 'Are you want to delete this item of Products. Do you want to proceed?',
                    // 'items' => $itemsToDelete, // Send the list of items for confirmation
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Products item can be deleted. They might be in use.',
                ]);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => 'Error updating Products: ' . $e->getMessage()]);
        }
    }


    // import excel data to db
    public function importExcel(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $file = $request->file('excel_file');

        // Load spreadsheet
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // ✅ Define Expected Headers
        $expectedHeaders = [
            'sno',
            'name',
            'uom',
            'hsncode',
            'itemweight',
            'category_id1',
            'category_id2',
            'category_id3',
            'category_id4',
            'category_id5',
            'category_id6',
            'category_id7',
            'category_id8',
            'category_id9',
            'category_id10',
            'purcCost',
            'margin',
            'price',
            'tax',
            'update_frequency',
            'price_update_frequency',
            'price_threshold',
            'itemType'
        ];

        // ✅ Get Headers from First Row
        $fileHeaders = array_map('trim', $rows[0]); // Trim spaces from headers

        // ✅ Check missing headers
        $missingHeaders = array_diff($expectedHeaders, $fileHeaders);
        $extraHeaders = array_diff($fileHeaders, $expectedHeaders);

        if (!empty($missingHeaders)) {
            return back()->with('error', 'Missing headers: ' . implode(', ', $missingHeaders));
        }

        if (!empty($extraHeaders)) {
            return back()->with('error', 'Extra headers found: ' . implode(', ', $extraHeaders));
        }
        // ✅ Check if headers match exactly (Order & Case-Sensitive Check)
        if ($fileHeaders !== $expectedHeaders) {
            return back()->with('error', ' Invalid column order! Please ensure the headers are exactly: ' . implode(', ', $expectedHeaders));
        }

        $duplicateNames = [];
        $importedCount = 0;
        // Loop through rows and insert into database
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip the header row

            $normalizedName = str_replace(' ', '', strtolower(trim($row[1])));
            // check for duplicates based on 'name' and 'hsnCode'
            $existingProduct = Product::whereRaw("
                    REPLACE(LOWER(TRIM(name)), ' ', '') = ?
                ", [$normalizedName])
                ->where('store_id', $storeid)
                // ->where('hsnCode', $row[3])
                ->first();

            // $existingProduct = Product::whereRaw("
            //     REPLACE(LOWER(TRIM(name)), ' ', '') = ?
            // ", [str_replace(' ', '', strtolower(trim($row[1])))])
            // ->where('hsnCode', $row[3])
            // ->first();

            if ($existingProduct) {
                $duplicateNames[] = $row[1];
                continue; // Skip duplicate row
            }
                // $prod_categoryId = DB::table('categories')
                // ->whereRaw("REPLACE(LOWER(TRIM(categoryname)), ' ', '') = ?", ['products']) // removes all spaces
                // ->value('id');

                $categoryIds = [];
                $name = trim($row[1] ?? '');
                $uom = trim($row[2] ?? '');
                $hsncode = trim($row[3] ?? '');
                $itemwgt = trim($row[4] ?? '');
                $price = trim($row[17] ?? '');
                $ptax = trim($row[18] ?? '');
                $frequency = trim($row[19] ?? '');
                $priceupdatefrequency = trim($row[20] ?? '');
                $thershold = trim($row[21] ?? '');
                $itemType = trim($row[22] ?? '');
                $categoryIds['id1'] = trim($row[5] ?? '');

                /*
                for ($i = 1; $i <= 10; $i++) {
                    $itemNameRaw = $row[$i + 4] ?? null;

                    $name = trim($row[1] ?? '');
                    $uom = trim($row[2] ?? '');
                    $hsncode = trim($row[3] ?? '');
                    $itemwgt = trim($row[4] ?? '');
                    $price = trim($row[15] ?? '');
                    $ptax = trim($row[16] ?? '');
                    $frequency = trim($row[17] ?? '');
                    $priceupdatefrequency = trim($row[18] ?? '');
                    $thershold = trim($row[19] ?? '');
                    $itemType = trim($row[20] ?? '');

                    if (!empty($itemNameRaw)) {
                        $itemName = trim(strtolower($itemNameRaw));

                        // Try to find the ID with the normalized comparison
                        $itemId = DB::table('categoryitems')
                            ->where('categoryId', $prod_categoryId)
                            ->where('status', 'active')
                            ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [$itemName])
                            ->value('id');

                        // If not found, insert a new record
                        if (!$itemId) {
                            $itemId = DB::table('categoryitems')->insertGetId([
                                'categoryId' => $prod_categoryId,
                                'itemname' => $itemNameRaw, // use original casing and spacing
                                'description' => 'none',
                                'status' => 'active',       // assuming default status is 'active'
                                'created_at' => now(),      // optional timestamps
                                'updated_at' => now()
                            ]);
                        }

                        $categoryIds["id$i"] = $itemId;
                    } else {
                        $categoryIds["id$i"] = null;
                    }
                } */

            if (
                empty($name) ||
                empty($uom) ||
                (empty($hsncode) || strlen($hsncode) > 8) ||
                empty($itemwgt) ||
                empty($price) ||
                empty($ptax) ||
                empty($frequency) ||
                empty($priceupdatefrequency) ||
                empty($thershold) ||
                empty($itemType) ||
                empty($categoryIds['id1']) // category_id1 must not be null
            ) {
                $skippedRows[] = "Row ".($index + 1)." skipped: missing required fields (name/uom/hsncode/itemwgt/price/tax/updatefrequency/priceupdatefrequency/threshold/itemType/category_id1).";
                continue;
            }

            $pdCode = UniqueCode::generatePdCode();
            $stocks = ' ';

            for ($i = 1; $i <= 10; $i++) {
                $categoryIds["id$i"] = !empty($row[$i + 4]) // Adjusting index to match $row[4] for category_id1
                    ? DB::table('categoryitems')
                    ->where('categoryId', 4)
                    ->where('status', 'active')
                    ->where('store_id', $storeid)
                    // ->where('itemname', $row[$i + 3])
                    ->whereRaw("REPLACE(LOWER(TRIM(itemname)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')", [trim(strtolower($row[$i + 4]))])
                    ->value('id')
                    : null;            }
            $itemtype_id = DB::table('item_type')->where('itemtypename', $row[22])->where('status', 'active')->where('store_id', 0)->value('id');
                $stocks =' ';
            Product::create([
                'name' => $row[1] ?? null,
                'pdcode' => $pdCode ?? null,
                'uom' => $row[2] ?? null,
                'hsnCode' => $row[3] ?? null,
                'itemWeight' => $row[4] ?? null,
                'category_id1' => $categoryIds['id1'] ?? null,
                'category_id2' => $categoryIds['id2'] ?? null,
                'category_id3' => $categoryIds['id3'] ?? null,
                'category_id4' => $categoryIds['id4'] ?? null,
                'category_id5' => $categoryIds['id5'] ?? null,
                'category_id6' => $categoryIds['id6'] ?? null,
                'category_id7' => $categoryIds['id7'] ?? null,
                'category_id8' => $categoryIds['id8'] ?? null,
                'category_id9' => $categoryIds['id9'] ?? null,
                'category_id10' => $categoryIds['id10'] ?? null,
                'purcCost' =>  $row[15] ?? null,
                'margin' => $row[16],
                'price' => $row[17],
                'tax' => $row[18],
                'update_frequency' => $row[19],
                'price_update_frequency' => $row[20],
                'price_threshold' => $row[21],
                'minimum_stock_unit' => $stocks,
                'minimum_stock_qty' => $stocks,
                'itemType_id' => $row[22] , //$itemtype_id,
                'store_id' => $storeid
            ]);
            $importedCount++;
        }

            $message = $importedCount . ' row(s) imported successfully.';
            if (!empty($duplicateNames)) {
                $message .= ' Skipped duplicates: ' . implode(', ', $duplicateNames);
            }
            if (!empty($skippedRows)) {
                $message .= ' Skipped rows: ' . implode(' | ', $skippedRows);
            }
            return back()->with('success',  $message);
    }

    public function exportAll(Request $request)
    {
        $storeid = $request->session()->get('store_id');
        $statusValue = $request->input('statusValue', '');
        $categories = \App\Models\CategoryItems::where('store_id', $storeid)->pluck('itemname', 'id');

        $query = \App\Models\Product::select([
            'id',
            'name',
            'pdcode',
            'price',
            'uom',
            'category_id1',
            'category_id2',
            'category_id3',
            'category_id4',
            'category_id5',
            'category_id6',
            'category_id7',
            'category_id8',
            'category_id9',
            'category_id10',
             'status'
        ])
            // ->where('status', 'active')  // Filter active records
            ->where('store_id', $storeid);

            if ($statusValue !== null && $statusValue !== '') {
            $query->where('status', $statusValue);
        }
        $products = $query->orderBy('name', 'asc')->get();
            // ->orderBy('name', 'asc')     // Sort by name ASC
            // ->get();


        $productsWithNames = $products->map(function ($item) use ($categories) {
            $categoryNames = [];

            for ($i = 1; $i <= 10; $i++) {
                $field = 'category_id' . $i;
                if (!empty($item->$field) && isset($categories[$item->$field])) {
                    $categoryNames[] = $categories[$item->$field];
                }
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'pdcode' => $item->pdcode,
                'price' => $item->price,
                'uom' => $item->uom,
                'status' => $item->status,
                'categories' => implode(', ', $categoryNames), // Comma-separated for easier frontend use
            ];
        });

        return response()->json($productsWithNames);
    }
}
