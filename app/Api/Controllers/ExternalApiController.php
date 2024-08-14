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

            // $username = env('CANADAPOST_USERNAME');
            // $password = env('CANADAPOST_PASSWORD');

            // if (!$username || !$password){
            //     return response()->json([
            //         'message' => 'CANADA POST credentials are not set',
            //         'status_code' => 500
            //     ],500);
            // }

            ini_set('max_execution_time', 3600);
            // Define parameters for the request
            $shippingFormData = $request->all();

            $originPostalCode = isset($shippingFormData['originPostalCode']) ? $shippingFormData['originPostalCode'] : ''; // You can adjust this according to your needs

            $weight = isset($shippingFormData['weight']) ? $shippingFormData['weight'] : '';
            
            // Ensure zipcode is set and has a length greater than 5
            if (isset($shippingFormData['destinationPostalCode']) && strlen($shippingFormData['destinationPostalCode']) > 5) {
              
                $destinationPostalCode = $shippingFormData['destinationPostalCode'];
                // Example weight, adjust according to your requirements

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://soa-gw.canadapost.ca/rs/ship/price',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="UTF-8"?>
                        <mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
                            <parcel-characteristics>
                            <weight>'.$weight.'</weight>
                            </parcel-characteristics>
                            <origin-postal-code>'.$originPostalCode.'</origin-postal-code>
                            <destination>
                            <domestic>
                                <postal-code>'.$destinationPostalCode.'</postal-code>
                            </domestic>
                            </destination>
                            <quote-type>counter</quote-type>
                        </mailing-scenario>',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/vnd.cpc.ship.rate-v4+xml',
                    'Content-Type: application/vnd.cpc.ship.rate-v4+xml',
                    'Authorization: Basic YjIyZjQ4NWJlMmNjYzQ5MjphMDNlYWY4NjY4NDdjODM3YjMxZTA2',
                    'Accept-Language: en-CA'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                return response()->json([
                    'response' => $response,
                    'status_code' => 200
                ]);
            }
             else {

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
