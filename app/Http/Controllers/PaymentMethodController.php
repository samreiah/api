<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\PaymentMethod;
use App\Transformers\PaymentMethodTransformer;


class PaymentMethodController extends ApiController
{
	
	public function getActivePaymentMethods() {

		$payment_methods = $this->createCollectionData(PaymentMethod::where('is_active', 1)->get(), new PaymentMethodTransformer);
		return $this->respondWithSuccess('PAYMENT_METHODS_FOUND',"Active payment methods found.", $payment_methods);
	}

	public function getPaymentMethodById($paymentMethodId) {

		$payment_method 	= PaymentMethod::findorFail($paymentMethodId);
		$payment_method 	= $this->createData(new Item($payment_method, new PaymentMethodTransformer));
		return $this->respondWithSuccess('PAYMENT_METHOD_FOUND',"payment method found.", $payment_method);
	}
}
