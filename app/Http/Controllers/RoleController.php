<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = DB::table('roles')->where('status', 'active')->get();
        return view('role.role', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.addrole');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            // Debugging: Log input data
            Log::info('Storing role with data: ', $validated);

            // Create new role
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            // Debugging: Confirm successful creation
            if (!$role) {
                Log::error('role creation failed.');
                return redirect()->back()->with('error', 'Failed to create role.')->withInput();
            }

            Log::info('role created successfully: ', ['id' => $role->id]);

            return redirect()->route('role.index')->with('success', 'role created successfully.');
        } catch (ValidationException $e) {
            // Log validation errors
            Log::warning('Validation failed: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log general exception
            Log::error('Exception in role Store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Could not save data.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function addPermissionToRole(string $id)
    {
        $role = DB::table('roles')->where('id', $id)->first();
        $permission_category = DB::table('permission_category')->where('status', 'active')->get();
        $permission_menu = DB::table('permission_menu')->where('status', 'active')->get();
        return view('role.addRolePermission', compact('permission_category', 'permission_menu', 'role'));
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
