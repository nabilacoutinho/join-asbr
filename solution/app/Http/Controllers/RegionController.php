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
    
    /**
     * List all regions registred in database
     * @return json response
     */
    public function listRegions(){
        
        $regions = Region::all(['id', 'name']);
        
        return response()->json(['data' => $regions]);
        
    }
    
    /**
     * List all unities for one region
     * @param int $id
     * @return json response
     */
    public function listUnities($id){
        
        $region = Region::where('id', $id)->first();
        // find by name
        //$region = Region::where('name', $id)->first();
        
        if(empty($region)) {
            
            return response()->json(['data' => []]);
            
        }
        
        $unities = $this->getUnities($region);
        
        return response()->json(['data' => $unities]);
        
    }
    
    /**
     * get formatted array of the unities to this region
     * @param Region $region
     * @return [] array of all unities
     */
    private function getUnities(Region $region) {
        
        $unitiesArray = [];
        
        $unities = $region->unities;
        
        
        foreach ($unities as $unity) {
            $unitiesArray[] = [
                'id' => $unity->id,
                'name' => $unity->name
            ];
        }
            
        return $unitiesArray;
        
    }
    
    
}
