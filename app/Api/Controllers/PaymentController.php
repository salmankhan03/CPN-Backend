<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Charge;


class PaymentController extends Controller
{

    public function processStripePayment(Request $request)
    {
        try {

            $token = $request->input('token');
            $price = $request->input('orderPrice');
            $orderId = $request->input('orderId');

            Stripe::setApiKey('sk_test_51NBOXVFb9Yh8bF65NwWJ0TqLndQLndBwzKm2xUNBGTMsqnBDIByK2GgeejLIgJvNN9wEWRJ0VdC64F1Ut29x601c009i3TN4Mq');

            $charge = Charge::create([
                'amount' => $price * 100, // Amount is in cents
                'currency' => 'usd', // Change to your desired currency
                'source' => $token,
                'description' => "OrderId:- {$orderId}",
            ]);

            print_r($charge);
            die;

            return response()->json(['message' => 'Payment processed successfully']);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }
}
