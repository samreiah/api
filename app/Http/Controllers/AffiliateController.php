<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateAffiliateRequest;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\User;
use App\Affiliate;
use App\UserRole;
use App\Country;
use App\Transformers\AffiliateTransformer;

class AffiliateController extends ApiController
{
    public function authenticate(LoginRequest $request) {
    	$data = ['email'=>$request->input('email'),'password'=>$request->input('password')];
        if(Auth::attempt($data)) {
        	Auth::login(Auth::user());
			
			if(Auth::user()->Affiliate == null) {
				$request->request->add(['name' => ' ', 'company_name' => ' ', 'email' => $request->input('email')]);
				$affiliate 	= $this->createAffiliate($request, Auth::user());
				if(!($this->isRoleExists( Auth::user()->id, 2))) {
					$this->createAffiliateRole( Auth::user()->id, 2);
				}
			}
			
            return $this->respondWithSuccess('USER_LOGGED',"User logged In Successfully.", $this->createData(new Item(Auth::user()->Affiliate, new AffiliateTransformer)));
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
	
	public function create(CreateAffiliateRequest $request) {
		
		$user 		= $this->createUser($request);
		if($user) {
			$affiliate 	= $this->createAffiliate($request, $user);
		}
		if(!($this->isRoleExists($user->id, 2))) {
			$this->createAffiliateRole($user->id, 2);
		}
    	return $this->respondWithSuccess(
						'AFFILIATE_CREATED', 'Affiliate created successfully now you can login',
						$this->createData(new Item($affiliate, new AffiliateTransformer)
		));
    }
	
	
	private function createUser($request) {
		
		$user 			= new User();
		$user->email 	= $request->input('email');
		$user->password = bcrypt($request->input('password'));
		$user->save();
		return $user;
	}
	
	private function createAffiliate($request, $user) {
		$data 			= ['firstname' => $request->input('name'),  'email' => $request->input('email'), 'company_name' => $request->input('company_name'), 'user_id' => $user->id];
        $affiliate 		= Affiliate::create($data);
		return $affiliate;
	}
	
	private function createAffiliateRole($user_id, $role_id) {
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
	
	private function isAffiliateExists($request) {
		$affiliate = Affiliate::where('email', '=', $request->input('email'))->first();
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
	
	public function getAffiliate($affiliate_id) {
		
		$affiliate 		= Affiliate::findOrFail($affiliate_id);

		return $this->respondWithSuccess(
            'AFFILIATE_FOUND', NULL,
			$this->createData(new Item($affiliate, new AffiliateTransformer))
        );
	}
	
	
	public function getAffiliates() {
		$affiliates 	= Affiliate::orderBy('firstname', 'asc')->paginate($this->limit);
		
		return $this->respondWithSuccess(
            'AFFILIATES_FOUND', NULL,
            $this->createPaginatedData($affiliates, new AffiliateTransformer)
        );
	}
	
	public function updateAffiliate(UpdateAffiliateRequest $request) {
		
		$affiliate = Affiliate::findOrFail($request->id);
		$affiliate->save($request);
		
		return $this->respondWithSuccess(
            'AFFILIATE_UPDATED', NULL,
            $this->createData($affiliate, new AffiliateTransformer)
        );
	}
	
}
