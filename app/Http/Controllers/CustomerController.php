<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CreateCustomerRequest;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Auth;
use App\User;
use App\Customer;
use App\UserRole;
use App\Transformers\CustomerTransformer;

class CustomerController extends ApiController
{

    public function authenticate(LoginRequest $request) {
    	$data = ['email'=>$request->input('email'),'password'=>$request->input('password')];
        if(Auth::attempt($data)) {
        	Auth::login(Auth::user());
			
			if(Auth::user()->Customer == null) {
				$request->request->add(['name' => ' ', 'email' => $request->input('email')]);
				$affiliate 	= $this->createCustomer($request, Auth::user());
				if(!($this->isRoleExists( Auth::user()->id, 3))) {
					$this->createCustomerRole( Auth::user()->id, 3);
				}
			}
			
            return $this->respondWithSuccess('USER_LOGGED',"User logged In Successfully.", $this->createData(new Item(Auth::user()->Customer, new CustomerTransformer)));
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


    public function create(CreateCustomerRequest $request) {
		
		$user 		= $this->createUser($request);
		if($user) {
			$customer 	= $this->createCustomer($request, $user);
		}
		if(!($this->isRoleExists($user->id, 3))) {
			$this->createCustomerRole($user->id, 3);
		}
    	return $this->respondWithSuccess(
						'CUSTOMER_CREATED', 'Customer Created Successfully now you can login',
						$this->createData(new Item($customer, new CustomerTransformer)
		));
    }
	
	
	private function createUser($request) {
		
		$user 			= new User();
		$user->email 	= $request->input('email');
		$user->password = bcrypt($request->input('password'));
		$user->save();
		return $user;
	}
	
	private function createCustomer($request, $user) {
		$data 			= ['firstname' => $request->input('name'),  'email' => $request->input('email'), 'user_id' => $user->id];
        $customer 		= Customer::create($data);
		return $customer;
	}
	
	private function createCustomerRole($user_id, $role_id) {
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
	
	private function isCustomerExists($request) {
		$affiliate = Customer::where('email', '=', $request->input('email'))->first();
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
	
	public function getCustomer($customer_id) {
		
		$customer 		= Customer::findOrFail($customer_id)
									->orderBy('firstname', 'asc')
									->first();
		
		return $this->respondWithSuccess(
            'CUSTOMER_FOUND', NULL,
			$this->createData(new Item($customer, new CustomerTransformer))
        );
	}
	
	
	public function getCustomers() {
		$customers 	= Customer::orderBy('firstname', 'asc')->paginate($this->limit);
		
		return $this->respondWithSuccess(
            'CUSTOMERS_FOUND', NULL,
            $this->createPaginatedData($customers, new CustomerTransformer)
        );
	}
    
}
