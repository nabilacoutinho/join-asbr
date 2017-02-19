<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * Get the unities for the region.
     */
    public function unities()
    {
        return $this->hasMany('App\Unity');
    }
    
    /**
     * Get the prospects for the region.
     */
    public function prospects()
    {
        return $this->hasMany('App\Prospects');
    }
}
