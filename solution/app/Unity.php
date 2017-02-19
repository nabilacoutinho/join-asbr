<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unity extends Model
{
    
    /**
     * Get the region that unity is in it.
     */
    public function region()
    {
        return $this->belongsTo('App\Region');
    }
    
    /**
     * Get the prospects for the region.
     */
    public function prospects()
    {
        return $this->hasMany('App\Prospects');
    }
}
