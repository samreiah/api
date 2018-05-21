<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Category extends Model implements SluggableInterface
{
    use SluggableTrait;
	
	protected $dates 	= ['created_at', 'updated_at'];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];
	
	/**
     * Scope to retrive based on active.
     *
     */
	public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
	
	/**
     * Scope to retrive based on inactive.
     *
     */
	public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
	
	public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }   
	
	// recursive, loads all descendants
	public function childrenRecursive()
	{
	   return $this->children()->with('childrenRecursive');
	   // which is equivalent to:
	   // return $this->hasMany('Category', 'parent')->with('childrenRecursive);
	}
	
	// all ascendants
	public function parentRecursive()
	{
	   return $this->parent()->with('parentRecursive');
	}
	
	public function products()
    {
        return $this->belongsToMany('App\Product', 'product_categories');
    }
	
	public function filterGroups() {
		
		return $this->belongsToMany('App\FilterGroup', 'category_filters');
		
	} 
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
