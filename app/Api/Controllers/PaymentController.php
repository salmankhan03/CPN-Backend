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

            $request->validate([
                'token' => 'required',
                'orderPrice' => 'required',
                'orderId' => 'required'
            ]);
       
            $token = $request->input('token');
            $price = $request->input('orderPrice');
            $orderId = $request->input('orderId');

            $stripeApiKey = env('STRIPE_API_KEY');

            if (!$stripeApiKey){
                return response()->json([
                    'message' => 'Stripe Credentials Are Not set',
                    'status_code' => 500
                ],500);
            }

            Stripe::setApiKey($stripeApiKey);

            $charge = Charge::create([
                'amount' => $price * 100, // Amount is in cents
                'currency' => 'usd', // Change to your desired currency
                'source' => $token,
                'description' => "OrderId:- {$orderId}",
            ]);



            return response()->json(['message' => 'Payment processed successfully', 'stripe-message' => $charge]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
