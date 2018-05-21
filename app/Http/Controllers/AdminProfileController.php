<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

use App\Http\Requests\EditAdminRequest;
use App\Http\Requests\ChangePasswordRequest;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Auth;
use App\User;
use App\Admin;

class AdminProfileController extends ApiController
{

    public function editProfilePost(EditAdminRequest $request) {
		
		$admin 					= Admin::findOrFail($request->admin_id);
		$admin->firstname 		= $request->firstname;
		$admin->lastname 		= $request->lastname;
		$admin->mobile 			= $request->mobile;
		$admin->telephone 		= $request->telephone;
		$admin->updated_by 		= $request->updated_by;
		$admin->save();
				
		return $this->respondWithSuccess(
            'ADMIN_UPDATED', 'Admin profile updated Successfully', []
        );
	}
	
	public function passwordResetPost(ChangePasswordRequest $request) {
		
		$user					= User::findorFail($request->userId);
		$user->password 		= bcrypt($request->input('password'));
		$user->save();
		
		return $this->respondWithSuccess(
            'PASSWORD_UPDATED', 'Password Updated Successfully', []
        );
	}    
}
