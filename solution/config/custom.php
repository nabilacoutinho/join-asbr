<?php
// custom configs

return [
    
    'api_token' => 'b1dbcd38a162923736c344e234b2c70f', // token to send data for endpoint
    
    'prospect_initial_score' => 10, // initial score to calculate prospect (lead) score
    
    // scores baseaded by age
    'birthday_child_score' => -5, // A partir de 100 ou menor que 18: -5 pontos
    'birthday_young_adult_score' => 0, // Entre 40 e 99: -3 pontos
    'birthday_adult_score' => -3, // Entre 18 e 39: não modifica
    
    'actual_date_string' => '01/11/2016', // used to calculate the prospect's birthday score
    
];