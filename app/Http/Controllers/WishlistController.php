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
use App\Transformers\WishlistTransformer;

use App\Http\Requests\AddWishlistItemRequest;

class WishlistController extends ApiController
{
	
	 public function getWishlistItemsByCustomer($customerId) {
		
		$wishlistObject 		= $this->createCollectionData(CustomerWishlist::where('customer_id', $customerId)->get(), new WishlistTransformer);
		return $this->respondWithSuccess('PRODUCT_FOUND',"Product found.", $wishlistObject['data']);
		
	}
	
	public function getWishlistItemsByIds($wishlistIds) {
		parse_str($wishlistIds, $output);
		$wishlistObject 		= $this->createCollectionData(CustomerWishlist::whereIn('id', $output['pIds'])->get(), new WishlistTransformer);
		return $this->respondWithSuccess('PRODUCT_FOUND',"Product found.", $wishlistObject['data']);
	}
	
	public function addWishlistItem(AddWishlistItemRequest $request) {
		
		if($request->input('customer_id') !== null) {
			if($this->isItemExists($request->input('product_id'), $request->input('customer_id'))) {
				return $this->respondBadRequest('Product already exists in cart');
			} 
		}

		$wishlist 				= new CustomerWishlist();
		$wishlist->quantity 	= $request->input('quantity');
		$wishlist->product_id 	= $request->input('product_id');
		$wishlist->updated_by 	= $request->input('updated_by');
		$wishlist->customer_id 	= ($request->input('customer_id') !== null) ? $request->input('customer_id') : 0;
		$wishlist->save();
		
		return $this->respondWithSuccess('PRODUCT_ADDED', 'Product added to wishlist successfully', ['id'=> $wishlist->id]);
	}
    
	public function addFromCart(Request $request) {
		
		$cartItem 		= CustomerCart::findorFail($request->cart_id);
		$cartItemArr	= $cartItem->toArray();

		$wishlist						= new CustomerWishlist();
		$wishlist->customer_id 			= $cartItemArr['customer_id'];
		$wishlist->product_id 			= $cartItemArr['product_id'];
		$wishlist->quantity 			= $cartItemArr['quantity'];
		$wishlist->product_option_id	= $cartItemArr['product_option_id'];
		$wishlist->option_value_id		= $cartItemArr['option_value_id'];
		$wishlist->updated_by 			= $request->updated_by;
		$wishlist->save();
		
		$cartItem->delete();
		
		return $this->respondWithSuccess('WISHLIST UPDATED', 'Product updated to wishlist successfully', []);
	}
	
	public function updateCustomerId($wishlistIds, $customerId) {
		parse_str($wishlistIds, $output);
		foreach($output['pIds'] as $id) {
			if($this->isItemExists($id, $customerId)) {
				if (($key = array_search($id, $output['pIds'])) !== false) {
					unset($output['pIds'][$key]);
				}
			}
		} 
		
		$wishlistObject 		= CustomerWishlist::whereIn('id', $output['pIds'])->update(['customer_id' => $customerId]);
		return $this->respondWithSuccess('WISHLIST_UPDATED',"Wishlist updated with customer id");
	}
	
	public function updateWishlistItem(updateWishlistItemRequest $request) {
		
		$wishlistId 					= CustomerWishlist::findorFail($request->id);
		$wishlistId->quantity 			= $request->input('quantity');
		$wishlistId->product_id 		= $request->input('product_id');
		$wishlistId->updated_by 		= $request->input('updated_by');
		$wishlistId->customer_id 		= ($request->input('customer_id') !== null) ? $request->input('customer_id') : 0;
		$wishlistId->save();
		
		return $this->respondWithSuccess('CART UPDATED', 'Product updated to cart successfully', ['id'=> $wishlistId->id]);
	}
	
	public function updateQuantity($wishlistId, $quantity) {
		$wishlist 					= CustomerWishlist::findorFail($wishlistId);
		$wishlist->quantity			= $quantity;
		$wishlist->save();
		return $this->respondWithSuccess('QUANTITY UPDATED', 'Product updated to wishlist successfully', ['id'=> $wishlist->id]);
	}
	
	public function deleteWishlistItem($wishlistId){
		$wishlist 					= CustomerWishlist::findorFail($wishlistId);
		$wishlist->delete();
		return $this->respondWithSuccess('ITEM DELETED', 'Product deleted from wishlist successfully', []);
	}
	
	private function isItemExists($productId, $customerId) {
		$exists = CustomerWishlist::where('product_id', $productId)->where('customer_id', $customerId)->count();
		if($exists > 0) {
			return true;
		} else {
			return false;
		}
	}
}
