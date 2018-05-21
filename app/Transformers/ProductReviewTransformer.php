<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ProductReview;
use App\Transformers\CustomerdetailsTransformer;
use App\Transformers\ProductTransformer;
//use App\Transformers\ManufacturerTransformer;

class ProductReviewTransformer extends TransformerAbstract
{
	protected $availableIncludes = [
        'customerdetails'
    ];

    protected $defaultIncludes = [
        'customerdetails'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ProductReview $review)
  	{
  	    return [
            'review_id'   		  => $review->id,
            'customer_id' 		 => $review->customer_id,
            'value' 			     => $review->value,
            'quality' 			=> $review->quality,
            'price' 			=> $review->price,
            'title' 			=> $review->title,
            'description' 		=> $review->description,
  	    ];
  	}
	
	public function includeCustomerdetails(ProductReview $review)
    {
        $customer = $review->customer;
		if($customer == null) {
			return null;
		}
		
        return $this->item($customer, new CustomerdetailsTransformer);
  }


  public function includeProduct(ProductReview $review)
  {
      $product = $review->product;
      if($product == null) {
        return null;
      }
      return $this->item($product, new ProductTransformer);
  }
}
