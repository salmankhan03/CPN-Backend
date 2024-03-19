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

            ini_set('max_execution_time', 3600);
            // Define parameters for the request
            $shippingFormData = $request->all();

            $originPostalCode = isset($shippingFormData['originPostalCode']) ? $shippingFormData['originPostalCode'] : ''; // You can adjust this according to your needs

            $weight = isset($shippingFormData['weight']) ? $shippingFormData['weight'] : '';
            
            // Ensure zipcode is set and has a length greater than 5
            if (isset($shippingFormData['destinationPostalCode']) && strlen($shippingFormData['destinationPostalCode']) > 5) {
              
                $destinationPostalCode = $shippingFormData['destinationPostalCode'];
                // Example weight, adjust according to your requirements

                $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
                                <mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
                                    <parcel-characteristics>
                                        <weight>' . $weight . '</weight>
                                    </parcel-characteristics>
                                    <origin-postal-code>' . $originPostalCode . '</origin-postal-code>
                                    <destination>
                                        <domestic>
                                            <postal-code>' . $destinationPostalCode . '</postal-code>
                                        </domestic>
                                    </destination>
                                    <quote-type>counter</quote-type>
                                </mailing-scenario>';

                // username = f89d8930468d9b94
                // password =2100e015132b09d589da39

                $response = Http::withHeaders([
                    'Content-Type' => 'application/vnd.cpc.ship.rate-v4+xml',
                    'Accept' => 'application/vnd.cpc.ship.rate-v4+xml',
                    'Authorization' => 'Basic ' . base64_encode('f89d8930468d9b94' . ':' . '2100e015132b09d589da39'),
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
