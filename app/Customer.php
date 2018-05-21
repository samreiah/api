<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['user_id', 'firstname', 'lastname', 'email', 'mobile', 'telephone', 'image', 'cart', 'wish_list'];

    protected $dates 	= ['created_at', 'updated_at'];

	public function addresses()
    {
        return $this->hasMany('App\CustomerAddress');
    }
	
	public function reviews() {
        return $this->hasMany('App\ProductReview', 'user_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
