<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('permission.permission');
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
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:permissions,name',
                    function ($attribute, $value, $fail) {
                        // Convert input to lowercase and remove spaces
                        $formattedValue = strtolower(str_replace(' ', '', $value));
                        // Fetch existing names from the database (case-insensitive)
                        $existingNames = Permission::pluck('name')->map(function ($name) {
                            return strtolower(str_replace(' ', '', $name));
                        })->toArray();
                        // Check if the formatted input already exists
                        if (in_array($formattedValue, $existingNames)) {
                            $fail('This name is duplicate. Please choose a different one.');
                        }
                    }
                ],  //'name' => 'required|string|max:255|unique:raw_materials,name',
            ]);

            try {
                Permission::create([
                    'name' => $request->name,
                    'guard_name' => 'web',
                ]);
            } catch (\Exception $e) {
                // \Log::error('Error inserting data: ' . $e->getMessage());
                dd($e->getMessage());
            }
            return redirect()->route('rawMaterials.index')->with('success', 'Raw Material created successfully.');
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
