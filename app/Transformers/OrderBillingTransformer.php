<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\OrderBilling;
use App\Transformers\ProductTransformer;

class OrderBillingTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'paymentAddress', 'shippingAddress'
    ];

    protected $defaultIncludes = [
        'paymentAddress', 'shippingAddress'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(OrderBilling $orderbilling)
  	{
  	    return [
          'billing_id'			      => $orderbilling->id,
          'name'       	          => $orderbilling->name,
          'mobile'     		        => $orderbilling->mobile,
          'tax'                   => $orderbilling->tax,
          'subtotal'				      => $orderbilling->subtotal,
          'wallet_discounts'			=> $orderbilling->wallet_discounts,
          'shipping_charge'       => $orderbilling->shipping_charge,
          'is_paid'               => $orderbilling->is_paid,
  	    ];
  	}

    public function includePaymentAddress(OrderBilling $orderbilling)
    {
        $paymentAddress = $orderbilling->paymentAddress;
    		if($paymentAddress == null) {
    			return null;
    		}
		
        return $this->item($paymentAddress, new CustomerAddressTransformer);
    }

    public function includeShippingAddress(OrderBilling $orderbilling)
    {
        $shippingAddress = $orderbilling->shippingAddress;
        if($shippingAddress == null) {
          return null;
        }
    
        return $this->item($shippingAddress, new CustomerAddressTransformer);
    }

}
