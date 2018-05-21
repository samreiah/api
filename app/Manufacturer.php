<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
	
	protected $dates 	= ['created_at', 'updated_at'];
	
	public function products() {
		return $this->hasMany('App\Product');
	}
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
