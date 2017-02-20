<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Prospect;
use App\Region;
use App\Unity;
use Carbon\Carbon;

class ProspectController extends Controller
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
     * create a new prospect
     * @param Request $request
     * @return json data
     */
    public function store(Request $request) {
        
        $validator = $this->validator($request->all());
        
        if ($validator->fails()){
            $data = [
                'success' => false,
                'errors' => $validator->messages()
            ];
            return response()->json($data, 200);
        }
        
        $prospect = $this->createProspect($request);
        
        if ($prospect->save()) {
            
            $data = [
                'success' => true,
                'data' => ['prospect' => $prospect]
            ];
            return response()->json($data, 200);
            
        } else {
            
            $data = [
                'success' => false,
                'errors' => ['unknowing' => 'Ocorreu um erro desconhecido, tente novamente']
            ];
            return response()->json($data, 200);
        }
        
    }
    
    /**
     * Get a validator for an incoming prospecting request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
    {
        
        return Validator::make($data, [
            'name' => 'required|alpha_spaces|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255', // add validator to phone
            'birthday' => 'required|date_format:d/m/Y|max:255',
        ]);
    }
    
    /**
     * create a new prospect by the request
     * @param Request $request
     * @return Prospect
     */
    private function createProspect(Request $request) {
        
        $prospect = new Prospect();
        
        $prospect->name = $request->name;
        $prospect->email = $request->email;
        $prospect->phone = $request->phone;
        $prospect->birthday = Carbon::createFromFormat('d/m/Y', $request->birthday);
        $prospect->calculateTotalScore();
        
        return $prospect;
        
    }
    
    public function edit($id, Request $request) {
        
        $prospect = Prospect::where('id', $id)->first();
        
        if (empty($prospect)){
            
            return response()->json([
                'success' => false,
                'errors' => [
                    'prospect' => 'Esse lead não foi encontrado'
                ]
            ]);
            
        }
        
        $region = Region::where('id', $request->region)->first();
        $unity = Unity::where('id', $request->unity)->first();
       
        $prospect->region = $region;
        $prospect->unity = $unity;
        $prospect->calculateTotalScore();
        
        if($prospect->save()) {
            
            $endpointResult = $prospect->sendLead();
            
            return response()->json([
                'success' => true,
                'data' => ['prospect' => $prospect, 'endpoint' => $endpointResult]
            ]);
            
        } else {
            
            return response()->json([
                'success' => false,
                'errors' => ['unknowing' => 'Ocorreu um erro desconhecido, tente novamente']
            ]);
        }
        
    }
    
    
}
