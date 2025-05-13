<?php

namespace App\Http\Controllers;

use App\Models\PmPriceUpdateAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\PdPriceUpdateAlert;
use App\Models\RmPriceUpdateAlert;
use App\Models\Product;
use App\Models\PackingMaterial;
use App\Models\User;
use App\Models\RawMaterial;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $combinedAlerts = $this->getFilteredAlerts($request);

        // Paginate the collection manually
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = new LengthAwarePaginator(
            $combinedAlerts->forPage($currentPage, $perPage),
            $combinedAlerts->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('alert', ['alerts' => $pagedData]);
    }


    private function getFilteredAlerts(Request $request)
    {
        $storeId = session('store_id');
        $userIds = User::where('store_id', $storeId)->pluck('id');

        $pdAlertHistory = PdPriceUpdateAlert::whereIn('user_id', $userIds)->get();
        $pmAlertHistory = PmPriceUpdateAlert::whereIn('user_id', $userIds)->get();
        $rmAlertHistory = RmPriceUpdateAlert::whereIn('user_id', $userIds)->get();

        $products = Product::all()->keyBy('id'); // for PD
        $packingMaterials = PackingMaterial::all()->keyBy('id'); // for PM
        $rawMaterials = RawMaterial::all()->keyBy('id'); // for RM

        $combinedAlerts = collect();

        foreach ($pdAlertHistory as $alert) {
            foreach ($alert->product_ids as $id) {
                if (isset($products[$id])) {
                    $combinedAlerts->push([
                        'name' => $products[$id]->name,
                        'code' => $products[$id]->pdcode,
                        'alert_type' => ucfirst($alert->alert_type),
                        'channel' => ucfirst($alert->channel),
                        'alerted_at' => $alert->alerted_at,
                    ]);
                }
            }
        }

        foreach ($pmAlertHistory as $alert) {
            foreach ($alert->packing_material_ids as $id) {
                if (isset($packingMaterials[$id])) {
                    $combinedAlerts->push([
                        'name' => $packingMaterials[$id]->name,
                        'code' => $packingMaterials[$id]->pmcode,
                        'alert_type' => ucfirst($alert->alert_type),
                        'channel' => ucfirst($alert->channel),
                        'alerted_at' => $alert->alerted_at,
                    ]);
                }
            }
        }

        foreach ($rmAlertHistory as $alert) {
            foreach ($alert->raw_material_ids as $id) {
                if (isset($rawMaterials[$id])) {
                    $combinedAlerts->push([
                        'name' => $rawMaterials[$id]->name,
                        'code' => $rawMaterials[$id]->rmcode,
                        'alert_type' => ucfirst($alert->alert_type),
                        'channel' => ucfirst($alert->channel),
                        'alerted_at' => $alert->alerted_at,
                    ]);
                }
            }
        }

        if ($request->filled('alert_type')) {
            $combinedAlerts = $combinedAlerts->filter(function ($alert) use ($request) {
                return strtolower($alert['alert_type']) === strtolower($request->alert_type);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $from = Carbon::parse($request->start_date)->startOfDay();
            $to = Carbon::parse($request->end_date)->endOfDay();

            $combinedAlerts = $combinedAlerts->filter(function ($alert) use ($from, $to) {
                return $alert['alerted_at'] >= $from && $alert['alerted_at'] <= $to;
            });
        }

        return $combinedAlerts->sortByDesc('alerted_at')->values();
    }

    public function export(Request $request)
    {
        $alerts = $this->getFilteredAlerts($request);

        return response()->json([
            'data' => $alerts
        ]);
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
