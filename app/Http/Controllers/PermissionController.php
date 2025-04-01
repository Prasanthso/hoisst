<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = DB::table('permissions')->where('status', 'active')->get();
        return view('permission.permission', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permission.addPermission');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
            ]);

            // Debugging: Log input data
            Log::info('Storing permission with data: ', $validated);

            // Create new permission
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            // Debugging: Confirm successful creation
            if (!$permission) {
                Log::error('Permission creation failed.');
                return redirect()->back()->with('error', 'Failed to create permission.')->withInput();
            }

            Log::info('Permission created successfully: ', ['id' => $permission->id]);

            return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
        } catch (ValidationException $e) {
            // Log validation errors
            Log::warning('Validation failed: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log general exception
            Log::error('Exception in Permission Store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Could not save data.')->withInput();
        }
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
