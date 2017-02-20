<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Prospect extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'birthday'
    ];
    
    // Relationships
    /**
     * Get the region informed by prospect.
     */
    public function region()
    {
        return $this->belongsTo('App\Region');
    }
    
    
    /**
     * Get the unity informed by prospect.
     */
    public function unity()
    {
        return $this->belongsTo('App\Unity');
    }
    
    // custom methods
    /**
     * calculate the total score for this prospect baseaded in he/she data
     * @return void 
     */
    public function calculateTotalScore() {
        
        $initialScore = config('custom.prospect_initial_score'); // get it from custom configs
        
        $regionScore = (!empty($this->region_id)) ? $this->calculateRegionScore() : 0;
        $birthdayScore = (!empty($this->birthday)) ? $this->calculateBirthdayScore() : 0;
        
        // the scores was stored as negative numbers
        $this->total_score = $initialScore + $regionScore + $birthdayScore;
        
    }
    
    /**
     * calculates the birthday score by the prospect region and unity
     * @return int score calulated
     */
    private function calculateRegionScore() {
        
        $score = $this->region->score;
        
        if (!empty($this->unity) && $this->unity->has_custom_score) {
            $score = (!empty($this->unity->custom_score)) ? $this->unity->custom_score : $this->region->score;
        }
        
        return $score;
        
    }
    
    /**
     * calculates the birthday score by the prospect birthday
     * @return int score calulated
     */
    private function calculateBirthdayScore() {
        
        $score = 0;
        
        $actualDateString = config('custom.actual_date_string'); // get it from custom configs
        $actualDate = Carbon::createFromFormat('d/m/Y', $actualDateString);
        
        $age = $actualDate->diffInYears($this->birthday);
        
        // get it from config
        if ($age < 18 || $age >= 100) {
            $score = config('custom.birthday_child_score'); // A partir de 100 ou menor que 18: -5 pontos
        } elseif($age >= 40) {
            $score = config('custom.birthday_adult_score'); // Entre 40 e 99: -3 pontos
        }  else { // it isn't needed
            $score = config('custom.birthday_young_adult_score'); // Entre 18 e 39: não modifica
        } 
        
        return $score;
        
    }
    
    
    public function sendLead(){
        
        $url = config('custom.api_url');
        $token = config('custom.api_token');
                
        $postData = [
            'nome' => $this->name,
            'email' => $this->name,
            'telefone' => $this->name,
            'data_nascimento' => $this->name,
            'score' => $this->name,
            'regiao' => $this->region->name,
            'unidade' => (!empty($this->unity_id)) ? $this->unity->name : 'INDISPONÍVEL',
            'token' => $token
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        return $response;
        
    }
    
    
}
