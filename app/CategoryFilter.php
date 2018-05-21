<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends Model implements SluggableInterface
{
	
	protected $dates 	= ['created_at', 'updated_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
	
	public function flterGroups() {
		return $this->belongsTo('App\FilterGroup');
	}
}
