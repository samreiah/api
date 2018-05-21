<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\OrderProduct;
use App\Transformers\ProductTransformer;
use App\Transformers\OrderTransformer;
use App\Transformers\ProductOptionTransformer;
use App\Transformers\OptionValueTransformer;
use App\Transformers\AffiliateTransformer;
use App\Transformers\OrderTrackingTransformer;

class OrderProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product', 'order', 'orderproductoption', 'orderproductoptionvalue', 'affiliate', 'orderTracking'
    ];

    protected $defaultIncludes = [
        'product', 'order', 'orderproductoption', 'orderproductoptionvalue', 'affiliate', 'orderTracking'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(OrderProduct $order_product)
  	{
  	    return [
          'order_product_id'			 => $order_product->id,
          'quantity'     		       => $order_product->quantity,
          'purchased_for'          => $order_product->purchased_for,
  	    ];
  	}

    public function includeProduct(OrderProduct $order_product)
    {
        $product = $order_product->product;
    		if($product == null) {
    			return null;
    		}
		
        return $this->item($product, new ProductTransformer);
    }

    public function includeOrder(OrderProduct $order_product) {

        $order = $order_product->order;
        if($order == null) {
          return null;
        }
    
        return $this->item($order, new OrderTransformer);
    }

    public function includeOrderproductoption(OrderProduct $order_product) {

        $product_option = $order_product->ProductOption;
        if($product_option == null) {
          return null;
        }
    
        return $this->item($product_option, new ProductOptionTransformer);
    }


    public function includeOrderproductoptionvalue(OrderProduct $order_product) {

        $option = $order_product->OptionValue;
        if($option == null) {
          return null;
        }
    
        return $this->item($option, new OptionValueTransformer);
    }

    public function includeAffiliate(OrderProduct $order_product) {
        $affilate = $order_product->affiliate;
        if($affilate  == null) {
            return null;
        }

        return $this->item($affilate, new AffiliateTransformer);
    }

     public function includeOrderTracking(OrderProduct $order_product)
    {
        $orderTracking = $order_product->orderTracking;
        if($orderTracking == null) {
            return null;
        }

        return $this->Collection($orderTracking, new OrderTrackingTransformer);
    }

}
