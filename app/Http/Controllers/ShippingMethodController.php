<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\ShippingMethod;
use App\Transformers\ShippingMethodTransformer;


class ShippingMethodController extends ApiController
{
	
	public function getActiveShippingMethods() {

		$shipping_methods = $this->createCollectionData(ShippingMethod::where('is_active', 1)->get(), new ShippingMethodTransformer);
		return $this->respondWithSuccess('SHIPPING_METHODS_FOUND',"Active shipping methods found.", $shipping_methods);
	}

	public function getShippingMethodById($shippingMethodId) {

		$shipping_method 	= ShippingMethod::findorFail($shippingMethodId);
		$shipping_method 	= $this->createData(new Item($shipping_method, new ShippingMethodTransformer));
		return $this->respondWithSuccess('SHIPPING_METHOD_FOUND',"shipping method found.", $shipping_method);
	}
}
