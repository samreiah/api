<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\ShippingRule;
use App\Transformers\ShippingRuleTransformer;


class ShippingRuleController extends ApiController
{
	
	public function getActiveShippingRules() {
		$shipping_rules = ShippingRule::where('is_active', 1)->first();

		if($shipping_rules == null) {

			return $this->respondWithSuccess('SHIPPING_RULES_FOUND',"Active shipping rules found.", []);

		} else {

			$shipping_rules = $this->createData(new Item($shipping_rules, new ShippingRuleTransformer));
			return $this->respondWithSuccess('SHIPPING_RULES_FOUND',"Active shipping rules found.", $shipping_rules);

		}

	}
}
