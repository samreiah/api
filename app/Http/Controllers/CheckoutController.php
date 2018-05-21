<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Carbon;


use Auth;
use App\ShippingZipcode;
use App\ShippingRule;
use App\CustomerCart;
use App\CustomerWallet;
use App\CustomerAddress;
use App\Product;

use App\Order;
use App\OrderProduct;
use App\OrderStatus;
use App\OrderBilling;
use App\OrderPayment;
use App\OrderTracking;

use App\Http\Requests\CheckPincodeDeliveryRequest;
use App\Http\Requests\MakeOrderRequest;

use App\Transformers\CartTransformer;
use App\Transformers\ShippingRuleTransformer;


class CheckoutController extends ApiController
{
    public function checkPincodeDelivery(CheckPincodeDeliveryRequest $request) {

    	$isDeliveryAvailable = ShippingZipcode::where('zipcode', $request->zipcode)->where('is_active', 1)->count();
    	
    	if($isDeliveryAvailable >= 1) {
    		return $this->respondWithSuccess('DELIVERY_AVAILABLE', 'Hurray! Delivery Available to this zipcode', []);
    	} else {
    		return $this->respondWithError('DELIVERY_NOT_AVAILABLE', 'Sorry!, Delivery not available to this zipcode, try with different zipcode', []);
    	}
    }


    public function calculateGrandTotal(Request $request) {

    	$customerId 				= $request->customer_id;

    	$shipping_rules 			= $this->createData(new Item(ShippingRule::where('is_active', 1)->first(), new ShippingRuleTransformer));
    	$cartItems 					= $this->createCollectionData(CustomerCart::where('customer_id', $customerId)->get(), new CartTransformer);
    	$creditReceved 				= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_received');
		$creditDeducted 			= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_deducted');
		$customerwalletAmount		= ['total_credits' => ($creditReceved - $creditDeducted)];
    	
		
    	if(isset($shipping_rules['data'])) {
			$minimum_value 		= $shipping_rules['data']['order_min_value'];
			$shipping_charge 	= $shipping_rules['data']['shipping_charges'];
		} else {
			$minimum_value 		= null;
			$shipping_charge 	= 0;
		}
		
		//calculating price
		$subtotal			= 0;
		$used_credits		= 0;
		$grand_total		= 0;
		$i					= 0;
		$inventory			= [];


		foreach($cartItems['data'] as $item) {
			
			if($item['product']['data']['stock_status'] == 'Available' && $item['product']['data']['quantity'] > 0) {

				if($item['product']['data']['quantity'] > $item['quantity']) {
				
					if(isset($item['product']['data']['discount']['data']['price'])) {
						
						$subtotal = $subtotal + ($item['product']['data']['discount']['data']['price'] * $item['quantity']);
					
					} else {
						$subtotal = $subtotal + ($item['product']['data']['price'] * $item['quantity']);
					}
					
					$availableCartItems[] 			= $item['cart_id'];
					$inventory[$i]['product_id']	= $item['product']['data']['product_id'];
					$inventory[$i]['quantity']		= $item['quantity'];
					$i++;
				}
			}
		}


		
		//check for shipping charge
		if($minimum_value != null && $minimum_value > $subtotal) {
			$shipping_charge = $shipping_charge;
		} else {
			$shipping_charge = 0;
		}

		//sub total before deduction
		$subtotal		= ($subtotal + $shipping_charge);

		//deductions
		if($subtotal > $customerwalletAmount['total_credits']) {
			$used_credits 	= $customerwalletAmount['total_credits'];
		} else {
			$used_credits 	= $subtotal;
		}

		//Amount to Pay
		$grand_total		= ($subtotal - $used_credits);

		$response 					= [];
		$response['cartItems'] 		= $availableCartItems;
		$response['inventory']		= $inventory;
		$response['used_credits']	= $used_credits;
		$response['grand_total']	= $grand_total;

		
		return $this->respondWithSuccess('TOTAL_CALCULATED', 'Total calculated', $response);
    }

	public function placeOrder(MakeOrderRequest $request) {

		try {

			$order_id 		= $this->InitiateOrder($request);
			$store_order	= $this->storeOrder($request, $order_id);

			$order_status 	= $this->getOrderStatusId('CONFIRMED');
			$updateOrder 	= $this->updateOrderStatus($order_id, $order_status);

			return $this->respondWithSuccess('ORDER_PLACED', 'Order Placed Successfully', []);

		} catch (Exception $ex) {
			return $this->respondWithError('Error', 'Error While placing order', []);
		}
	}

	private function InitiateOrder($request) {

		$order 	= new Order();
		$order->invoice_prefix 		= 'OID';
		$order->customer_id 		= $request->customer_id;
		$order->shipping_method_id 	= $request->shipping_method_id;
		$order->payment_method_id 	= $request->payment_method_id;
		$order->order_status_id 	= $this->getOrderStatusId('INITIATED');
		$order->ip 					= $request->ipaddr;
		$order->forwarded_ip 		= $request->ipaddr;
		$order->user_agent 			= $request->user_agent;
		$order->order_created 		= Carbon::now();
		$order->updated_by 			= $request->updated_by;

		$order->save();
		return $order->id;
	}

	private function storeOrder($request, $orderId) {

		$customerId 				= $request->customer_id;

		$shipping_rules 			= $this->createData(new Item(ShippingRule::where('is_active', 1)->first(), new ShippingRuleTransformer));
		$cartItems 					= $this->createCollectionData(CustomerCart::where('customer_id', $customerId)->get(), new CartTransformer);
		$creditReceved 				= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_received');
		$creditDeducted 			= CustomerWallet::where('customer_id', $request->customer_id)->sum('credit_deducted');
		$customerwalletAmount		= ['total_credits' => ($creditReceved - $creditDeducted)];

		if(isset($shipping_rules['data'])) {
			$minimum_value 		= $shipping_rules['data']['order_min_value'];
			$shipping_charge 	= $shipping_rules['data']['shipping_charges'];
		} else {
			$minimum_value 		= null;
			$shipping_charge 	= 0;
		}
		
		//calculating price
		$subtotal			= 0;
		$used_credits		= 0;
		$grand_total		= 0;
		$price				= 0;
		$tax 				= 0;
		$inventory			= [];


		foreach($cartItems['data'] as $item) {
				
			if($item['product']['data']['stock_status'] == 'Available' && $item['product']['data']['quantity'] > 0) {

				if($item['product']['data']['quantity'] > $item['quantity']) {
				
					if(isset($item['product']['data']['discount']['data']['price'])) {
						
						$subtotal 	= $subtotal + (($item['product']['data']['discount']['data']['price'] - $item['product']['data']['tax'])* $item['quantity']);
						$price 		= $item['product']['data']['discount']['data']['price'];
					} else {
						$subtotal 	= $subtotal + (($item['product']['data']['price'] - $item['product']['data']['tax']) * $item['quantity']);
						$price		= $item['product']['data']['price'];
					}

					$tax 			= $item['product']['data']['tax'] * $item['quantity'];

					// Insert into Order Products
					$order_product 						= new OrderProduct();
					$order_product->order_id 			= $orderId;
					$order_product->customer_id 		= $customerId;
					$order_product->product_id 			= $item['product']['data']['product_id'];
					$order_product->product_option_id 	= $item['product_option_id'];
					$order_product->option_value_id 	= $item['option_value_id'];
					$order_product->quantity 			= $item['quantity'];
					$order_product->purchased_for 		= $price;
					$order_product->affiliate_id 		= $item['product']['data']['affiliate_id'];
					$order_product->updated_by 			= $request->updated_by;

					$order_product->save();

					$updateTracking = $this->makeOrderTrackingEntry($request, $orderId, $order_product->id, 0, 0, 'INITIATED');
					

					//remove products from customer cart
					$customercart 						= CustomerCart::find($item['cart_id']);
					$customercart->delete();

					//update Inventory
					$product 						= Product::find($item['product']['data']['product_id']);
					$product->quantity				= $product->quantity - $item['quantity'];
					if($product->quantity <= 0) {
						$product->stock_status 		= 'NotAvailable';
					}
					$product->save();

					$updateTracking = $this->makeOrderTrackingEntry($request, $orderId, $order_product->id, 0, 0, 'CONFIRMED');
				}
			}
		}


		if(isset($shipping_rules['data'])) {
			$minimum_value 		= $shipping_rules['data']['order_min_value'];
			$shipping_charge 	= $shipping_rules['data']['shipping_charges'];
		} else {
			$minimum_value 		= null;
			$shipping_charge 	= 0;
		}

		//deductions
		if($subtotal > $customerwalletAmount['total_credits']) {
			$used_credits 	= $customerwalletAmount['total_credits'];
		} else {
			$used_credits 	= $subtotal;
		}

		$doOrderBillingEntry				= $this->makeOrderBillingEntry($request, $orderId, $tax, $subtotal, $used_credits, $shipping_charge);

		$update_wallet						= $this->updateWallet($request, $used_credits, $orderId);

		$doOrderPaymentEntry 				= $this->makeOrderPaymentEntry($request, $orderId, $tax, $subtotal, $used_credits, $shipping_charge, 0);


	}


	private function getOrderStatusId($orderStatus) {

		$orderStatus = OrderStatus::where('type', $orderStatus)->first();
		return $orderStatus->id;
	}

	private function updateWallet($request, $used_credits, $orderId) {

		$customerWallet 					= new CustomerWallet();
		$customerWallet->customer_id		= $request->customer_id;
		$customerWallet->credit_received	= 0;
		$customerWallet->credit_deducted	= $used_credits;
		$customerWallet->reference 			= 'DEBITED for Order Id '.$orderId;
		$customerWallet->updated_by			= $request->updated_by;
		$customerWallet->save();
		return true;
	}

	private function makeOrderBillingEntry($request, $orderId, $tax, $subtotal, $used_credits, $shipping_charge) {

		$customer_address					= CustomerAddress:: find($request->billing_address_id);

		$orderbilling 						= new OrderBilling();
		$orderbilling->order_id 			= $orderId;
		$orderbilling->customer_id 			= $request->customer_id;
		$orderbilling->payment_address_id 	= $request->billing_address_id;
		$orderbilling->shipping_address_id 	= $request->shipping_address_id;
		$orderbilling->tax 					= $tax;
		$orderbilling->subtotal 			= $subtotal;
		$orderbilling->wallet_discounts		= $used_credits;
		$orderbilling->shipping_charge		= $shipping_charge;
		$orderbilling->total				= ($subtotal + $tax + $shipping_charge) - $used_credits;
		$orderbilling->is_paid				= 0;
		$orderbilling->name					= $customer_address->firstname;
		$orderbilling->mobile				= $customer_address->mobile;
		$orderbilling->updated_by			= $request->updated_by;
		$orderbilling->save();

		return true;

	}

	private function makeOrderPaymentEntry($request, $orderId, $tax, $subtotal, $used_credits, $shipping_charge, $ispaid) {

		$OrderPayment 					= new OrderPayment();
		$OrderPayment->order_id 		= $orderId;
		$OrderPayment->total 			= ($subtotal + $tax + $shipping_charge) - $used_credits;
		$OrderPayment->is_paid 			= $ispaid;
		$OrderPayment->updated_by 		= $request->updated_by;
		$OrderPayment->save();
		return true;
	}

	private function makeOrderTrackingEntry($request, $orderId, $order_product_id, $is_shipped, $is_delivered, $comment) {

		$orderTracking 						= new OrderTracking();
		$orderTracking->order_id 			= $orderId;
		$orderTracking->order_product_id	= $order_product_id;
		$orderTracking->is_shipped 			= $is_shipped;
		$orderTracking->is_delvered 		= $is_delivered;
		$orderTracking->comment 			= $comment;
		$orderTracking->updated_by			= $request->updated_by;
		$orderTracking->save();
		return true;
	}

	private function updateOrderStatus($orderId, $statusid) {

		$order 					= Order::find($orderId);
		$order->order_status_id	= $statusid;
		$order->save();
		return true;
	}
}
