<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\User;
use App\Admin;
use App\Transformers\CustomerAddressTransformer;

class AdminTransformer extends TransformerAbstract
{
    
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Admin $admin)
  	{
  	    return [
            'admin_id'       		=> $admin->id,
            'user_id'           	=> $admin->user_id,
            'email'             	=> $admin->email,
            'firstname'             => $admin->firstname,
			'lastname'				=> $admin->lastname,
            'mobile'            	=> $admin->mobile,
            'telephone'         	=> $admin->telephone,
            'avatar'            	=> $admin->image,
			'company_name'			=> $admin->ip,
			'active'				=> $admin->slug,
			'member_since'			=> $admin->created_at,
  	    ];
  	}
}
