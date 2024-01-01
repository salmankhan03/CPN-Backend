<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponCodeValidateRequest;
use App\Models\CouponCode;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CouponCodeController extends Controller
{

    public function upsert(Request $request)
    {
        try {
            $data = $request->only(
                'id',
                'code',
                'expires_at',
                'amount',
                'calculation_type',
                'minimum_amount',
                'product_category_id',
                'is_eligible_for_free_shipping'
            );

            CouponCode::updateOrCreate(['id' => $data['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Coupon Code Created Successfully',
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
            $obj = CouponCode::find($id);

            if ($obj) {

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Coupon Code Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Coupon Code Not Found'
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

            $name = $request->get('name');

            if ($name) {

                $list = CouponCode::where('code', $request->get('name'))->paginate($request->get('pageSize'));
            } else {
                $list = CouponCode::paginate($request->get('pageSize'));
            }

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

    public function getById($id)
    {

        try {
            $couponCode = CouponCode::find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $couponCode
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

            CouponCode::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Coupon Codes Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function validateCouponCode(CouponCodeValidateRequest $request)
    {
        try {





            $currentTimestmap = Carbon::now();

            $couponCode = $request->get('coupon_code');
            $cartAmount = $request->get('cart_amount');

            $couponCode = CouponCode::where(function ($query) use ($couponCode) {
                $query->where('code',  strtoupper($couponCode))
                    ->orWhere('code', strtolower($couponCode));
            })->where('minimum_amount', '<', $cartAmount)
                ->where('expires_at', '>', $currentTimestmap->toDateTimeString())->first();

            // $messages = [];

            // $couponCodeSelected = null;

            // foreach ($couponCodes as $couponCode) {

            //     if (
            //         $couponCode->expires_at >= $currentTimestmap->toDateTimeString() &&
            //         $couponCode->minimum_amount <= $cartAmount
            //     ) {
            //         $couponCodeSelected = $couponCode;
            //     }
            // }


            return response()->json([
                'status_code' => 200,
                'is_coupon_code_valid' => $couponCode ? true : false,
                'coupon_code' => $couponCode,
                'message' => "Coupon Code Not Matched or Expired Already ."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
