<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use Carbon;

use Auth;

use App\Order;
use App\OrderProduct;

use App\Transformers\OrderTransformer;
use App\Transformers\OrderProductTransformer;

class OrderController extends ApiController
{
    public function allOrders($customerId) {

    	$orderProducts = OrderProduct::where('customer_id', $customerId)->get();
    	$orderProducts = $this->createCollectionData($orderProducts, new OrderProductTransformer);

    	return $this->respondWithSuccess('ORDERS_FOUND', 'your orders found', $orderProducts);
    }


    public function trackOrder($orderproductId) {

    	$order = OrderProduct::findorFail($orderproductId);
    	$order = $this->createData(new Item($order, new OrderProductTransformer));
    	return $this->respondWithSuccess('TRACKING FOUND', 'your orders found', $order);
    }


}
