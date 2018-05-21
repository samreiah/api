<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\CustomerWishlist;
use App\Transformers\ProductTransformer;

class WishlistTransformer extends TransformerAbstract
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

  	public function transform(CustomerWishlist $customerWishlist)
  	{
  	    return [
			'cart_id'			=> $customerWishlist->id,
            'product_id'       	=> $customerWishlist->product_id,
            'quantity'     		=> $customerWishlist->quantity,
            'customer_id'       => $customerWishlist->customer_id,
			'added'				=> $customerWishlist->created_at,
			'updated'			=> $customerWishlist->updated_at,
  	    ];
  	}

    public function includeProduct(CustomerWishlist $customerWishlist)
    {
        $product = $customerWishlist->Product;
		if($product == null) {
			return null;
		}
		
        return $this->item($product, new ProductTransformer);
    }

}
