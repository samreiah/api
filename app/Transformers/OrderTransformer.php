<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Order;
use App\Transformers\ShippingMethodTransformer;
use App\Transformers\PaymentMethodTransformer;
use App\Transformers\OrderStatusTransformer;
use App\Transformers\OrderBillingTransformer;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'shippingMethod', 'paymentMethod', 'orderStatus', 'orderbilling'
    ];

    protected $defaultIncludes = [
        'shippingMethod', 'paymentMethod', 'orderStatus', 'orderbilling'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Order $order)
  	{
  	    return [
			       'order_id'			   => $order->id,
             'customer_id'     => $order->customer_id,
             'invoice_prefix'  => $order->invoice_prefix,
             'order_updated'   => $order->updated_at,
             'order_created'   => $order->order_created,
             'order_created_d' => $order->order_created->diffInDays(),

  	    ];
  	}

    public function includeShippingMethod(Order $order)
    {
        $shippingMethod = $order->shippingMethod;
    		if($shippingMethod == null) {
    			return null;
    		}
    		
            return $this->item($shippingMethod, new ShippingMethodTransformer);
    }

    public function includePaymentMethod(Order $order)
    {
        $paymentMethod = $order->paymentMethod;
        if($paymentMethod == null) {
          return null;
        }
        
            return $this->item($paymentMethod, new PaymentMethodTransformer);
    }

    public function includeOrderStatus(Order $order)
    {
        $orderstatus = $order->orderStatus;
        if($orderstatus == null) {
          return null;
        }
        
            return $this->item($orderstatus, new OrderStatusTransformer);
    }

    public function includeOrderBilling(Order $order) 
    {

        $orderbilling = $order->orderBilling;
        if($orderbilling == null) {
            return null;
        }

        return $this->item($orderbilling, new OrderBillingTransformer);
    }
}
