<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductImages;
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
}