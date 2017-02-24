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
        
        if ($this->isNewProspect($request->email)) {
            $prospect = $this->createProspect($request);

            if ($prospect->save()) {

                $data = [
                    'success' => true,
                    'prospect' => $prospect
                ];
                return response()->json($data, 200);

            } else {

                $data = [
                    'success' => false,
                    'errors' => ['unknowing' => 'Ocorreu um erro desconhecido, tente novamente']
                ];
                return response()->json($data, 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['duplicate' => 'Você já nos respondeu.']
            ]);
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
        $this->addCustomRules();
        
        $rules = [
            'name' => 'required|alpha_spaces|multiple_words|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|brazilian_phone|max:255',
            'birthday' => 'required|date_format:d/m/Y|max:255',
        ];
        
        $messages = [
            'name.required' => 'Informe o seu nome',
            'name.alpha_spaces' => 'O nome deve conter apenas letras e espaços',
            'name.multiple_words' => 'Informe o seu nome e sobrenome',
            'email.required' => 'Informe o seu e-mail',
            'email.email' => 'Informe um e-mail válido',
            'phone.required' => 'Informe o seu telefone',
            'phone.brazilian_phone' => 'Informe um telefone válido, se for um número de celular verifique se há o 9º dígito',
            'birthday.required' => 'Informe a sua data de nascimento',
            'birthday.date_format' => 'Informe o seu nascimento no formato dd/mm/aaaa',
        ];
        
        return Validator::make($data, $rules, $messages);
    }
    
    /**
     * added new rules to Validator
     */
    private function addCustomRules() {
        
        //Add this custom validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces. 
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value); 

        });
        Validator::extend('multiple_words', function ($attribute, $value) {

            // This will only accept string with multiple words
            $words = explode(" ", $value);
            return count($words) > 1; 

        });
        
        //Add this custom validation rule.
        Validator::extend('brazilian_phone', function ($attribute, $value) {

            // check if a mobile phone have the nine digit or is another phone
            return preg_match('#^\(\d{2}\) (9|)[6789]\d{3}-\d{4}$#', $value) ||
                    preg_match('#^\(\d{2}\) [12345]\d{3}-\d{4}$#', $value); 

        });
    }
    
    /**
     * create a new prospect by the request
     * @param Request $request
     * @return Prospect
     */
    private function createProspect(Request $request) {
        
        $prospect = Prospect::where('email', $request->email)->first();
        if(empty($prospect)) {
            $prospect = new Prospect();
        }
        
        $prospect->name = $request->name;
        $prospect->email = $request->email;
        $prospect->phone = $request->phone;
        $prospect->birthday = Carbon::createFromFormat('d/m/Y', $request->birthday);
        $prospect->calculateTotalScore();
        
        return $prospect;
        
    }
    
    /**
     * edit lead to add the region and unity
     * @param int $id lead id
     * @param Request $request
     * @return json response
     */
    public function edit($id, Request $request) {
        
        $prospect = Prospect::where('id', $id)->first();
        
        $errors = $this->validateRegion($prospect, $request->region, $request->unity);
        
        if(count($errors) === 0) {

            $prospect->region_id = $request->region;
            $prospect->unity_id = $request->unity;
            $prospect->calculateTotalScore();

            if($prospect->save()) {

                $endpointResult = $prospect->sendLead();

                return response()->json([
                    'success' => $endpointResult->success,
                    'prospect' => $prospect, 
                    'endpoint' => $endpointResult
                ]);

            } else {

                return response()->json([
                    'success' => false,
                    'errors' => ['unknowing' => 'Ocorreu um erro desconhecido, tente novamente']
                ]);
            }
        } else {

            return response()->json([
                'success' => false,
                'errors' => $errors
            ]);
        }
    }
    
    /**
     * check if this prospect is new or not
     * @param type $email
     * @return type
     */
    private function isNewProspect($email) {
        
        $prospect = Prospect::where('email', $email)->first();
        // return true if prospect doesn't exists or if he/she doesn't complete the lead
        return empty($prospect) || !$prospect->is_sync;
        
    }
    
    /**
     * check if region and unity have errors
     * @param Prospect $prospect
     * @param int $regionID
     * @param int $unityID
     * @return errors
     */
    private function validateRegion($prospect, $regionID, $unityID) {
        
        if (empty($prospect)){

            return ['prospect' => 'Esse lead não foi encontrado'];

        }
        if (!$this->isNewProspect($prospect->email)){

            return [ 'duplicate' => 'Você já nos respondeu.'];

        }
        
        $region = Region::where('id', $regionID)->first();
        $unity = Unity::where('id', $unityID)->first();
        
        if(empty($region)) {
            return ['region' => ['Informe a sua região']];
        } else {
            if (count($region->unities) > 0 && empty($unity) ) {
                return ['unity' => ['Informe a sua unidade']];
            } elseif (!empty ($unity) && $unity->region_id !== $regionID) {
                return ['unity' => ['Unidade inválida']];
            }
        }
        
        return [];
        
    }
    
}
