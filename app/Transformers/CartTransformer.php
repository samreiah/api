<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\CustomerCart;
use App\Transformers\ProductTransformer;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product'
    ];

    protected $defaultIncludes = [
        'product'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(CustomerCart $customerCart)
  	{
  	    return [
			      'cart_id'			=> $customerCart->id,
            'product_id'       	=> $customerCart->product_id,
            'quantity'     		=> $customerCart->quantity,
            'customer_id'       => $customerCart->customer_id,
            'product_option_id' => $customerCart->product_option_id,
            'option_value_id' => $customerCart->option_value_id,
			      'added'				=> $customerCart->created_at,
			      'updated'			=> $customerCart->updated_at,
  	    ];
  	}

    public function includeProduct(CustomerCart $customerCart)
    {
        $product = $customerCart->Product;
		if($product == null) {
			return null;
		}
		
        return $this->item($product, new ProductTransformer);
    }

}
