<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateAdminRequest;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\User;
use App\Admin;
use App\UserRole;
use App\Country;
use App\Transformers\AdminTransformer;

class AdminController extends ApiController
{
    public function authenticate(LoginRequest $request) {
    	$data = ['email'=>$request->input('email'),'password'=>$request->input('password')];
        if(Auth::attempt($data)) {
        	Auth::login(Auth::user());
			
			if(Auth::user()->Admin == null) {
				Auth::logout(Auth::user());
				return $this->respondInternalError('LOGIN_ERROR','You are not an Admin!');
			}
			
            return $this->respondWithSuccess('USER_LOGGED',"User logged In Successfully.", $this->createData(new Item(Auth::user()->Admin, new AdminTransformer)));
		} else {
			return $this->respondInternalError('LOGIN_ERROR','Invalid Username and Password!');
		}
    }

    public function logout() {
        if(Auth::logout(Auth::user())) {
            return $this->respondWithSuccess('USER_LOGOUT',"User logged Out Successfully.");
        } else {
            return $this->respondWithError('NOUSER_LOGGED','No user logged in yet.');
        }
    }
	
	public function create(CreateAdminRequest $request) {
		
		$user 		= $this->createUser($request);
		if($user) {
			$affiliate 	= $this->createAdmin($request, $user);
		}
		if(!($this->isRoleExists($user->id, 1))) {
			$this->createAdminRole($user->id, 1);
		}
    	return $this->respondWithSuccess(
						'ADMIN_CREATED', 'Admin created successfully now you can login',
						$this->createData(new Item($affiliate, new AdminTransformer)
		));
    }
	
	
	private function createUser($request) {
		
		$user 			= new User();
		$user->email 	= $request->input('email');
		$user->password = bcrypt($request->input('password'));
		$user->save();
		return $user;
	}
	
	private function createAdmin($request, $user) {
		$data 			= ['firstname' => $request->input('firstname'),  'lastname' => $request->input('lastname'), 'email' => $request->input('email'), 'mobile' => $request->input('mobile'), 'telephone' => $request->input('telephone'), 'user_id' => $user->id];
        $admin 			= Admin::create($data);
		return $admin;
	}
	
	private function createAdminRole($user_id, $role_id) {
		$data 		= ['user_id' => $user_id, 'role_id' =>$role_id];
		$user_role  = UserRole::create($data);
	}

	
	private function isUserExists($request) {
		$user = User::where('email', '=', $request->input('email'))->first();
		if ($user === null) {
		   return false;
		} else {
			return true;
		}
	}
	
	private function isAdminExists($request) {
		$affiliate = Admin::where('email', '=', $request->input('email'))->first();
		if ($affiliate === null) {
		   return false;
		} else {
			return true;
		}
	}
	
	private function isRoleExists($user_id, $role_id) {
		$role_exists = UserRole::where('user_id', $user_id)->where('role_id', $role_id)->first();
		if ($role_exists === null) {
		   return false;
		} else {
			return true;
		}
	}
	
	public function getAdmin($admin_id) {
		
		$admin 		= Admin::findOrFail($admin_id);

		return $this->respondWithSuccess(
            'ADMIN_FOUND', NULL,
			$this->createData(new Item($admin, new AdminTransformer))
        );
	}
	
		
	public function updateAdmin(UpdateAdminRequest $request) {
		
		$affiliate = Affiliate::findOrFail($request->id);
		$affiliate->save($request);
		
		return $this->respondWithSuccess(
            'AFFILIATE_UPDATED', NULL,
            $this->createData($affiliate, new AffiliateTransformer)
        );
	}
	
}
