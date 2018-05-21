<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\PaymentMethod;

class PaymentMethodTransformer extends TransformerAbstract
{
	
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(PaymentMethod $Paymentmethod)
  	{
  	    return [
            'payment_method_id'    => $Paymentmethod->id,
            'payment_method_type'  => $Paymentmethod->type,
            'is_active'            => $Paymentmethod->is_active,
            'code'                 => $Paymentmethod->code,
  	    ];
  	}
}
