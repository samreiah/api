<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	protected $fillable = array('user_id', 'role_id');
	
	protected $dates 	= ['created_at', 'updated_at'];
	
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
