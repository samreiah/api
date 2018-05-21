<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Auth;
use App\Product;
use App\Transformers\ProductTransformer;
use App\Transformers\AttributeTransformer;
use App\Transformers\CategoryTransformer;
use App\Transformers\FilterTransformer;
use App\Transformers\ProductImageTransformer;
use App\Transformers\ProductReviewTransformer;
use App\Transformers\ProductDiscountTransformer;

class ProductController extends ApiController
{
    public function getProductById($productId) {
		$productObject  	= Product::findorFail($productId);
		
		$product 		= $this->createData(new Item($productObject, new ProductTransformer));
		$attributes 	= $this->createCollectionData($productObject->attributes, new AttributeTransformer);
		$categories		= $this->createCollectionData($productObject->categories, new CategoryTransformer);
		$filters		= $this->createCollectionData($productObject->filters, new FilterTransformer);
		$images			= $this->createCollectionData($productObject->images, new ProductImageTransformer);
		$reviews		= $this->createCollectionData($productObject->reviews, new ProductReviewTransformer);
		
		$result 				= [];
		$result['product'] 		= $product['data'];
		$result['attributes'] 	= $attributes['data'];
		$result['categories'] 	= $categories['data'];
		$result['filters'] 		= $filters['data'];
		$result['images'] 		= $images['data'];
		$result['reviews'] 		= $reviews['data'];
		return $this->respondWithSuccess('PRODUCT_FOUND',"Product found.", $result);
	}
	
	public function getProductReviews($productId) {
		
		$productObject  	= Product::findorFail($productId);
		if(empty($productObject->reviews->toArray())) {
			return $this->respondWithError('No Reviews Found');
		}
		$reviews			= $this->createCollectionData($productObject->reviews, new ProductReviewTransformer);
		return $this->respondWithSuccess('REVIEWS_FOUND',"Product reviews found.", $reviews);
	}
}
