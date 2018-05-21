<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\ProductReview;
use App\OrderProduct;
use App\Transformers\ProductReviewTransformer;
use App\Http\Requests\WriteReviewRequest;

class ReviewController extends ApiController
{
	
	 public function allReviews($customerId) {
		
		$reviews = ProductReview::where('customer_id', $customerId)->get();
		$reviews = $this->createCollectionData($reviews, new ProductReviewTransformer(['product']));
		return $this->respondWithSuccess('REVIEWS_FOUND', 'your reviews found', $reviews);
	}

	public function is_valid($customerId, $order_product_id) {
		$is_valid 		= OrderProduct::where('customer_id', $customerId)->where('id', $order_product_id)->first();
		if($is_valid == null || empty($is_valid)) {
			return $this->respondWithError('NOT_FOUND', 'Seems you have not purchased this product');
		}
		
		//check if user reviewed for sa,me product
		$is_reviewed 	= ProductReview::where('customer_id', $customerId)->where('product_id', $is_valid->product_id)->first();
		if($is_reviewed != null || !empty($is_reviewed)) {
			return $this->respondWithError('ALREADY REVIEWED', 'Seems you have reviewed this product, an user is allowed to review a product only once');
		}
			
		$result = ['product_id' => $is_valid->product_id];
		return $this->respondWithSuccess('CAN REVIEW', '', $result);
	}

	public function writeReview(WriteReviewRequest $request) {

			$productreview 					= new ProductReview();
			$productreview->product_id 		= $request->product_id;
			$productreview->customer_id 	= $request->customer_id;
			$productreview->value 			= $request->valuerating;
			$productreview->quality 		= $request->qualityrating;
			$productreview->price 			= $request->pricerating;
			$productreview->title 			= $request->title;
			$productreview->description 	= $request->description;
			$productreview->updated_by 		= $request->updated_by;

			$productreview->save();

			return $this->respondWithSuccess('REVIEWED', 'Your Review stored successfully', []);
	}
	
}
