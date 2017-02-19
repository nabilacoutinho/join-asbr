<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Region;

class RegionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // the routes to this controller are in routes/api
        $this->middleware('api');
    }
    
    public function listRegions(){
        
        $regions = Region::all(['id', 'name']);
        
        return response()->toJson(['data' => $regions]);
        
    }
    
    
}
