<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Payments;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use Illuminate\Http\Request;


class OrderController extends Controller
{

    public function placeOrder(Request $request)
    {

        try {
            // $data = $request->all();

            $orderData = $request->only(
                'user_id',
                'total_amount',
                'status',
                'is_guest',
                'guest_user_id'

            );

            $orderData['status'] = Order::STATUS_PENDING;

            $order = Order::create($orderData);

            $paymentData = $request->get('payment_data');


            $paymentData['order_id'] = $order->id;
            $paymentData['user_id'] = $orderData['user_id'];

            $payment = Payments::create($paymentData);

            $order->payment_id = $payment->id;
            $order->save();

            $shippingAddress = $request->get('shipping_address');

            $shippingAddress['user_id'] = $orderData['user_id'];
            $shippingAddress['order_id'] = $order->id;

            UserShippingAddress::create($shippingAddress);

            $billingAddress = $request->get('billing_address');

            $billingAddress['user_id'] = $orderData['user_id'];
            $billingAddress['order_id'] = $order->id;

            UserBillingAddress::create($billingAddress);

            // need to reduce the quantity from available quantity in products table
            $productData = $request->get('product_data');

            foreach ($productData as $product) {

                $product['order_id'] = $order->id;

                OrderItems::create($product);
            }


            return response()->json([
                'status_code' => 200,
                'message'     => 'Order Created Successfully',
                'order_id' => $order->id
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {

            $list = Order::with('shippingAddress', 'billingAddress', 'payment')->paginate($request->get('pageSize'));

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        try {

            $orderId = $request->get('id');
            $status = $request->get('status');

            if (!$orderId) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Order Id Is missing'
                ], 500);
            }

            if (!$status) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'status is missing'
                ], 500);
            }

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Order Not Found'
                ], 500);
            }

            $order->status = $status;

            $order->save();

            return response()->json([
                'status_code' => 200,
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id)
    {
        try {

            $order = Order::with('shippingAddress', 'billingAddress', 'payment')->find($id);

            return response()->json([
                'status_code' => 200,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
