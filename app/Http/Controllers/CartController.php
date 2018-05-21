<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\CustomerCart;
use App\CustomerWishlist;
use App\Transformers\CartTransformer;
use App\Http\Requests\AddCartItemRequest;


class CartController extends ApiController
{
    public function getCartItemsByCustomer($customerId) {
		
		$cartObject 		= $this->createCollectionData(CustomerCart::where('customer_id', $customerId)->get(), new CartTransformer);
		return $this->respondWithSuccess('PRODUCT_FOUND',"Product found.", $cartObject['data']);
		
	}
	
	public function getCartItemsByIds($cartIds) {
		parse_str($cartIds, $output);
		$cartObject 		= $this->createCollectionData(CustomerCart::whereIn('id', $output['pIds'])->get(), new CartTransformer);
		return $this->respondWithSuccess('PRODUCT_FOUND',"Product found.", $cartObject['data']);
	}
	
	public function addFromWishlist(Request $request) {
		
		$wishListItem 		= CustomerWishlist::findorFail($request->cart_id);
		$wishListItemArr	= $wishListItem->toArray();

		$customerCart						= new CustomerCart();
		$customerCart->customer_id 			= $wishListItemArr['customer_id'];
		$customerCart->product_id 			= $wishListItemArr['product_id'];
		$customerCart->quantity 			= $wishListItemArr['quantity'];
		$customerCart->product_option_id	= $wishListItemArr['product_option_id'];
		$customerCart->option_value_id		= $wishListItemArr['option_value_id'];
		$customerCart->updated_by 			= $request->updated_by;
		$customerCart->save();
		
		$wishListItem->delete();
		
		return $this->respondWithSuccess('CART UPDATED', 'Product updated to cart successfully', []);
	}
	
	public function addCartItem(AddCartItemRequest $request) {
		
		if($request->input('customer_id') !== null) {
			if($this->isItemExists($request->input('product_id'), $request->input('customer_id'))) {
				return $this->respondBadRequest('Product already exists in cart');
			}
		}
		
		$cart 						= new CustomerCart();
		$cart->quantity 			= $request->input('quantity');
		$cart->product_id 			= $request->input('product_id');
		$cart->product_option_id 	= $request->input('product_option_id');
		$cart->option_value_id 		= $request->input('option_value_id');
		$cart->updated_by 			= $request->input('updated_by');
		$cart->customer_id 			= ($request->input('customer_id') !== null) ? $request->input('customer_id') : 0;
		$cart->save();
		
		return $this->respondWithSuccess('PRODUCT_ADDED', 'Product added to cart successfully', ['id'=> $cart->id]);
	}
	
	private function isItemExists($productId, $customerId) {
		$exists = CustomerCart::where('product_id', $productId)->where('customer_id', $customerId)->count();
		if($exists > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function updateCartItem(updateCartItemRequest $request) {
		
		$cart 					= CustomerCart::findorFail($request->id);
		$cart->quantity 		= $request->input('quantity');
		$cart->product_id 		= $request->input('product_id');
		$cart->updated_by 		= $request->input('updated_by');
		$cart->customer_id 		= ($request->input('customer_id') !== null) ? $request->input('customer_id') : 0;
		$cart->save();
		
		return $this->respondWithSuccess('CART UPDATED', 'Product updated to cart successfully', ['id'=> $cart->id]);
	}
	
	public function updateQuantity($cartId, $quantity) {
		$cart 					= CustomerCart::findorFail($cartId);
		$cart->quantity			= $quantity;
		$cart->save();
		return $this->respondWithSuccess('QUANTITY UPDATED', 'Product updated to cart successfully', ['id'=> $cart->id]);
	}
	
	public function deleteCartItem($cartId){
		$cart 					= CustomerCart::findorFail($cartId);
		$cart->delete();
		return $this->respondWithSuccess('ITEM DELETED', 'Product deleted from cart successfully', []);
	}
	
	public function updateCustomerId($cartIds, $customerId) {
		parse_str($cartIds, $output);
		foreach($output['pIds'] as $id) {
			if($this->isItemExists($id, $customerId)) {
				if (($key = array_search($id, $output['pIds'])) !== false) {
					unset($output['pIds'][$key]);
				}
			}
		} 
		
		$cartObject 		= CustomerCart::whereIn('id', $output['pIds'])->update(['customer_id' => $customerId]);
		return $this->respondWithSuccess('CART_UPDATED',"Cart updated with customer id");
	}
	
}
