<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCart extends Model
{
	
	protected $dates 	= ['created_at', 'updated_at'];

	public function Customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function Product() {

    	return $this->belongsTo('App\Product' );
    }
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
