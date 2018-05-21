<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class Affiliate extends Model implements SluggableInterface
{
	use SluggableTrait;
    
    protected $fillable = ['user_id', 'firstname', 'lastname', 'email', 'mobile', 'telephone', 'company_name', 'slug', 'website', 'addr_line1', 'addr_line2'];

    protected $dates 	= ['created_at', 'updated_at'];

    protected $sluggable = [
        'build_from' => 'company_name',
        'save_to'    => 'slug',
    ];

	public function Country() {
		
    	return $this->belongsTo('App\Country');
    }
	
	 /**
	 * Scope to retrive based on approved state.
	 *
     */
	public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }
	
	/**
     * Scope to retrive based on active.
     *
     */
	public function scopeActive($query)
    {
        return $query->where('affiliate_state', 1);
    }
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
