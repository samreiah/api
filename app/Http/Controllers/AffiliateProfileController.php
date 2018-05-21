<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

use App\Http\Requests\EditAffiliateRequest;
use App\Http\Requests\EditAffiliateCompanyRequest;
use App\Http\Requests\ChangePasswordRequest;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Auth;
use App\User;
use App\Affiliate;
use App\Transformers\CustomerTransformer;
use App\Transformers\CustomerAddressTransformer;

class AffiliateProfileController extends ApiController
{

    public function editProfilePost(EditAffiliateRequest $request) {
		
		$affiliate 				= Affiliate::findOrFail($request->affiliate_id);
		$affiliate->firstname 	= $request->firstname;
		$affiliate->lastname 	= $request->lastname;
		$affiliate->mobile 		= $request->mobile;
		$affiliate->telephone 	= $request->telephone;
		$affiliate->updated_by 	= $request->updated_by;
		$affiliate->save();
				
		return $this->respondWithSuccess(
            'AFFILIATE_UPDATED', 'Affiliate profile updated Successfully', []
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
	
	public function editCompanyProfilePost(EditAffiliateCompanyRequest $request) {
		
		$affiliate 				= Affiliate::findOrFail($request->affiliate_id);
		$affiliate->website 	= $request->website;
		$affiliate->addr_line1 	= $request->street1;
		$affiliate->addr_line2 	= $request->street2;
		$affiliate->city 		= $request->city;
		$affiliate->postcode 	= $request->postcode;
		$affiliate->country_id 	= $request->country;
		if(isset($request->active)) {
			$affiliate->affiliate_state 	= 1;
		} else  {
			$affiliate->affiliate_state 	= 0;
		}
		$affiliate->save();
				
		return $this->respondWithSuccess(
            'AFFILIATE_UPDATED', 'Affiliate Company profile updated Successfully', []
        );
	}
    
}
