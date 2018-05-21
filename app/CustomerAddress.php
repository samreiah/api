<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
	protected $fillable = ['firstname', 'addr_line1', 'addr_line2', 'mobile', 'telephone', 'postcode'];

    protected $dates 	= ['created_at', 'updated_at'];
	
    public function Customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function Country() {

    	return $this->belongsTo('App\Country' );
    }
}
