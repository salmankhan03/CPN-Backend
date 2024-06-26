<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Payments;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\SentOrderStatusUpdateEmailLog;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;



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
                'guest_user_id',
                'discount_price',
                'shipping_price'

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

            $customerName = $request->get('customerName');
            $status = $request->get('status');
            $ordersFromLastNDays = $request->get('day');
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');
            $paymentMethod = $request->get('method');

            $queryBuilder = Order::with('shippingAddress', 'billingAddress', 'payment', 'Items.product.images');

            if ($customerName) {
                $queryBuilder->whereHas('shippingAddress', function ($q) use ($customerName) {

                    $q->where('first_name', $customerName);
                });
            }

            if ($status) {
                $queryBuilder->where('status', $status);
            }

            if ($ordersFromLastNDays) {
                $lastNDayDate = \Carbon\Carbon::today()->subDays($ordersFromLastNDays);
                $queryBuilder->where('created_at', '>=', $lastNDayDate);
            }

            if ($startDate) {

                $startDateFormatted = Carbon::parse(strtotime($startDate))->format('Y-m-d H:i:s');

                $queryBuilder->where('created_at', '>=', $startDateFormatted);
            }

            if ($endDate) {
                $endDateFormatted = Carbon::parse(strtotime($endDate))->format('Y-m-d H:i:s');

                $queryBuilder->where('created_at', '<=', $endDateFormatted);
            }

            if ($paymentMethod) {

                $queryBuilder->whereHas('payment', function ($q) use ($paymentMethod) {
                    $q->where('type', $paymentMethod);
                });
            }

            $list = $queryBuilder->paginate($request->get('pageSize'));

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

            $userId = Auth::id();

            $orderUpdateData = $request->only(
                'order_id',
                'previous_order_status',
                'current_order_status',
                'email_body',
                'from_email',
                'to_email'
            );

            $orderId = $orderUpdateData['order_id'];
            $status =  $orderUpdateData['current_order_status'];

            //can't revert back from cancelled orders

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

            if ($order->status == Order::STATUS_CANCELLED || $order->status == Order::STATUS_DELIVERED) {
                return response()->json([
                    'status_code' => 200,
                    'message' => "Can't Change The Status That are already cancelled or delivered"
                ], 200);
            }

            if (!in_array($status, Order::STATUSES)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unknown Status :- ' . $status
                ], 500);
            }

            $order->status = $status;

            $order->save();

            $orderUpdateData['updated_by'] = $userId;
            $orderUpdateData['to_email'] = json_encode($orderUpdateData['to_email']);

            // check order previous status and current status

            SentOrderStatusUpdateEmailLog::create($orderUpdateData);

            //order status mail

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

            $order = Order::with('shippingAddress', 'billingAddress', 'payment', 'Items.product')->find($id);

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
