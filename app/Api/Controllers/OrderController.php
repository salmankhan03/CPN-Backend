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

    public function delete($id)
    {

        try {
            $obj = Product::find($id);

            if ($obj) {
                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Product Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Product Not Found'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        try {

            $criteria = [];

            if ($request->get('category')) {
                $criteria['category_id'] = $request->get('category');
            }

            if ($request->get('title')) {
                $criteria['name'] = $request->get('title');
            }

            if ($request->get('price')) {
                $criteria['price'] = $request->get('price');
            }

            $list = Product::with('images')->where($criteria)->paginate($request->get('pageSize'));

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductById($id)
    {

        try {
            $product = Product::with('images')->find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function multipleDelete(Request $request)
    {
        try {
            $ids = explode(",",  $request->only('ids')['ids']);

            Product::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Products Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
