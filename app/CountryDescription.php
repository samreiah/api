<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryDescription extends Model
{

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'iso_2'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	
	protected $dates 	= ['created_at', 'updated_at'];


    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
