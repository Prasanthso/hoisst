<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OverAllCostingController extends Controller
{
    public function create(){
        return view('addoverallcosting');
    }

}
