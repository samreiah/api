<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Admin extends Model
{
    
    protected $fillable = ['user_id', 'firstname', 'lastname', 'email', 'mobile', 'telephone', 'ip', 'addr_line1', 'addr_line2'];

    protected $dates 	= ['created_at', 'updated_at'];

	
	/**
     * Scope to retrive based on active.
     *
     */
	public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
