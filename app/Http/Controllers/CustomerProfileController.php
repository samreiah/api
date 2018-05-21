<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\EditAddressRequest;
use App\Http\Requests\AddAddressRequest;
use App\Http\Requests\ChangePasswordRequest;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Auth;
use App\User;
use App\Customer;
use App\CustomerAddress;
use App\Transformers\CustomerTransformer;
use App\Transformers\CustomerAddressTransformer;

class CustomerProfileController extends ApiController
{

    public function editProfilePost(EditCustomerRequest $request) {
		
		$customer 				= Customer::findOrFail($request->customerId);
		$customer->firstname 	= $request->firstname;
		$customer->lastname 	= $request->lastname;
		$customer->mobile 		= $request->mobile;
		$customer->telephone 	= $request->telephone;
		$customer->updated_by 	= $request->updated_by;
		$customer->save();
				
		return $this->respondWithSuccess(
            'CUSTOMER_UPDATED', 'Customer Data updated Successfully',
            $this->createData(new Item($customer, new CustomerTransformer))
        );
	}
	
	public function passwordResetPost(ChangePasswordRequest $request) {
		
		$user					= User::findorFail($request->userId);
		$user->password 		= bcrypt($request->input('password'));
		$user->save();
				
		return $this->respondWithSuccess(
            'PASSWORD_UPDATED', 'Password Updated Successfully',[]
        );
	}
	
	public function getAddressById($addressId) {
		$address = CustomerAddress::findorFail($addressId);
		return $this->respondWithSuccess(
            'ADDRESS_FOUND', 'customer Address found',
            $this->createData(new Item($address, new CustomerAddressTransformer))
        );
	}
	
	public function getAddressByCId($customerId) {
		$addresses = CustomerAddress::where('customer_id', $customerId)->get();
		
		return $this->respondWithSuccess(
            'COUNTRIES_FOUND', NULL,
            $this->createCollectionData($addresses, new CustomerAddressTransformer)
        );
	}
	
	public function makeAddressDefault($customerId, $addressId) {
		
		$makeDefault 			= CustomerAddress::findorFail($addressId);
		
		$makezero 				= CustomerAddress::where('customer_id', 1)
								->update(['default' => 0]);
									
		$makeDefault->default 	= 1;
		$makeDefault->save();
		
		return $this->respondWithSuccess('ADDRESS_MADE_DEFAULT', 'Requested Address made default', []);
	
	}
	
	public function updateAddress(EditAddressRequest $request) {
		
		$address				= CustomerAddress::findorFail($request->addressId);
		$address->firstname		= $request->address_name;
		$address->addr_line1	= $request->street1;
		$address->addr_line2	= $request->street2;
		$address->mobile		= $request->mobile;
		$address->telephone		= $request->telephone;
		$address->country_id	= $request->country;
		$address->postcode		= $request->postcode;
		$address->updated_by	= $request->updated_by;
		$address->save();
		
		return $this->respondWithSuccess('ADDRESS_UPDATED', 'Address Updated Successfully', ['address_id' => $address->id]);
	}
	
	public function addAddress(AddAddressRequest $request) {
		
		$address				= new CustomerAddress();
		$address->firstname		= $request->address_name;
		$address->addr_line1	= $request->street1;
		$address->addr_line2	= $request->street2;
		$address->mobile		= $request->mobile;
		$address->telephone		= $request->telephone;
		$address->country_id	= $request->country;
		$address->postcode		= $request->postcode;
		$address->customer_id	= $request->customer_id;
		$address->save();
		
		return $this->respondWithSuccess('ADDRESS_UPDATED', 'Address Added Successfully', ['address_id' => $address->id]);
	}
	
	public function deleteAddress($customerId, $addressId) {
		
		$deleteAddress 			= CustomerAddress::findorFail($addressId);
		if($customerId == $deleteAddress->customer_id) {
			
			$deleteAddress->forceDelete();
			return $this->respondWithSuccess('ADDRESS_DELETED', 'Address Deleted Succssfully', []);
		} else {
			return $this->respondWithError('INTERNAL_ERROR', 'Seems the address you are deletiing is not your address', []);
		}		
	}
    
}
