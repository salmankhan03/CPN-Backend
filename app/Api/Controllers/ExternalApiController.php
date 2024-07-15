<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExternalApiController extends Controller
{
    public function getShippingQuote(Request $request)
    {
        try {

            $username = env('CANADAPOST_USERNAME');
            $password = env('CANADAPOST_PASSWORD');

            if (!$username || !$password){
                return response()->json([
                    'message' => 'CANADA POST credentials are not set',
                    'status_code' => 500
                ],500);
            }

            ini_set('max_execution_time', 3600);
            // Define parameters for the request
            $shippingFormData = $request->all();

            $originPostalCode = isset($shippingFormData['originPostalCode']) ? $shippingFormData['originPostalCode'] : ''; // You can adjust this according to your needs

            $weight = isset($shippingFormData['weight']) ? $shippingFormData['weight'] : '';
            
            // Ensure zipcode is set and has a length greater than 5
            if (isset($shippingFormData['destinationPostalCode']) && strlen($shippingFormData['destinationPostalCode']) > 5) {
              
                $destinationPostalCode = $shippingFormData['destinationPostalCode'];
                // Example weight, adjust according to your requirements

                $xmlRequest = <<<XML
                                    <?xml version="1.0" encoding="UTF-8"?>
                                    <mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
                                    <customer-number>1234567890</customer-number>
                                    <parcel-characteristics>
                                        <weight>{$weight}</weight>
                                    </parcel-characteristics>
                                    <origin-postal-code>{$originPostalCode}</origin-postal-code>
                                    <destination>
                                        <domestic>
                                        <postal-code>{$destinationPostalCode}</postal-code>
                                        </domestic>
                                    </destination>
                                    </mailing-scenario>
                                XML;

                $response = Http::withHeaders([
                    'Content-Type' => 'application/vnd.cpc.ship.rate-v4+xml',
                    'Accept' => 'application/vnd.cpc.ship.rate-v4+xml',
                    'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
                ])->post('https://ct.soa-gw.canadapost.ca/rs/ship/price', $xmlRequest);


                return response()->json([
                    'response' => $response->body(),
                    'status_code' => 200
                ]);
            } else {

                return response()->json(['error' => 'Invalid zipcode'], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
