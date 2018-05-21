<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $dates 	= ['created_at', 'updated_at'];
	
	public function attributes()
    {
        return $this->belongsToMany('App\Attribute', 'product_attributes');
    }
	
	public function categories()
    {
        return $this->belongsToMany('App\Category', 'product_categories');
    }
	
	public function filters()
    {
        return $this->belongsToMany('App\Filter', 'product_filters');
    }
	
	public function  firstImage()
	{
		return $this->hasOne('App\ProductImage');
	}
	
	public function images()
    {
        return $this->hasMany('App\ProductImage');
    }
	
	public function options() {
		return $this->hasMany('App\ProductOption');
	}
	
	public function reviews()
	{
		return $this->hasMany('App\ProductReview');
	}
		
	public function manufacturer()
    {
        return $this->belongsTo('App\Manufacturer');
    }
	
	public function activeDiscount() {
		return $this->hasOne('App\ProductDiscount')->whereDate('date_start','<=', date('Y-m-d'))->whereDate('date_end','>=', date('Y-m-d'));
	}
	
	public function weightClass()
    {
        return $this->belongsTo('App\WeightClass');
    }
	
	public function lengthClass()
    {
        return $this->belongsTo('App\LengthClass');
    }
	
	
	
}
