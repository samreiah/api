<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use Carbon;

use App\Coupon;
use App\CustomerCoupon;
use App\CustomerWallet;

use App\Http\Requests\RedeemCouponRequest;

use App\Transformers\CustomerCouponTransformer;

class RewardsController extends ApiController
{
	
	public function customerRewards(Request $request) {
		
		$cartObject 		= $this->createCollectionData(CustomerCoupon::where('customer_id', $request->customer_id)->get(), new CustomerCouponTransformer);
		return $this->respondWithSuccess('CUSTOMER_COUPONS_FOUND',"Customer Coupons found.", $cartObject['data']);
	}
	
	public function customerRewardPoints(Request $request) {
		
		$creditReceved 		= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_received');
		$creditDeducted 	= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_deducted');
		$total_credits		= ['total_credits' => ($creditReceved - $creditDeducted)];

		return $this->respondWithSuccess('TOTAL_CREDITS',"Total credits.", $total_credits);
	}
	
	public function redeemCoupon(RedeemCouponRequest $request){
		 
		$coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
		//check for coupon code exists
		if($coupon === null) {
			return $this->respondBadRequest('Invalid coupon code, Pleach check onceagain !');
		}
		// check for valid date
		if(!starts_with($coupon['valid_from']->toDateString(), '-0001') && !starts_with($coupon['valid_from']->toDateString(), '-0001')) {
			$valid_from = new Carbon($coupon->valid_from);
			$valid_till = new Carbon($coupon->valid_till);
			if(!$this->isValidDate($valid_from, $valid_till)) {
				return $this->respondBadRequest('This coupon Expired');
			}
		}
		
		//check if coupon is redeemed
		if($coupon->is_redeemed == 1) {
			return $this->respondBadRequest('This coupon is redeemed!');
		}
		
		//check coupon belongs to this customer
		if($coupon->user_id != 0) {
			if($coupon->user_id != $request->customer_id) {
				return $this->respondBadRequest('This coupon Not belongs to you!');
			}
		}
		
		//check whether coupon redeemed for maximum times
		if($coupon->valid_for != 0) {
			if($coupon->redeemed_for >= $coupon->valid_for) {
				return $this->respondBadRequest('This coupon is redeemed for maximum number of times!');
			}
		}
		
		//Redeem Coupon here
		$this->forceRedeemCoupon($coupon, $request);
		return $this->respondWithSuccess('COUPON REDEEMED', 'Coupon Redeemed Successfully', []);
	}
	 
	private function isValidDate($fromDate, $toDate) {
		$today = Carbon::today();
		if($today->between($fromDate, $toDate) === true) {
			return true;
		} else {
			return false;
		}
	}
	
	
	private function forceRedeemCoupon($couponObject, $request){
		
		$customerCoupon = new CustomerCoupon();
		$customerWallet = new CustomerWallet();
		
		$customerCoupon->customer_id		= $request->customer_id;
		$customerCoupon->coupon_id			= $couponObject->id;
		$customerCoupon->credit_received	= $couponObject->points;
		$customerCoupon->updated_by			= $request->updated_by;
		
		$customerWallet->customer_id		= $request->customer_id;
		$customerWallet->credit_received	= $couponObject->points;
		$customerWallet->credit_deducted	= 0;
		$customerWallet->reference			= 'CREDITED from coupon '.$couponObject->coupon_code.' with ID'.$couponObject->id;
		$customerCoupon->updated_by			= $request->updated_by;
		
		//check coupon belongs to this customer & check number of usage
		if($couponObject->user_id != 0) {
			//check if this user can use multiple attempts
			if($couponObject->valid_for > 0){
				$couponObject->redeemed_for = $couponObject->redeemed_for + 1;
				// set is_redeemed if coupon assigned to user
				if($couponObject->redeemed_for >= $couponObject->valid_for) {
					$couponObject->is_redeemed = 1;
				}
			}
		}
		
		//check number of usage based on priority (eg : first 100 customers)
		if($couponObject->valid_for > 0){
			$couponObject->redeemed_for = $couponObject->redeemed_for + 1;
			// set is_redeemed if coupon assigned to user
			if($couponObject->redeemed_for >= $couponObject->valid_for) {
				$couponObject->is_redeemed = 1;
			}
		}
		
		$couponObject->updated_by = $request->updated_by;
		
		$customerCoupon->save();
		$customerWallet->save();
		$couponObject->save();
		
		return true;		
	}
}
