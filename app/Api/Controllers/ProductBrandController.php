<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use Illuminate\Http\Request;


class ProductBrandController extends Controller
{

    public function upsert(Request $request)
    {
        try {
            $data = $request->only(
                'id',
                'name',
                'is_active'
            );

            ProductBrands::updateOrCreate(['id' => $data['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Product Brnad Created Successfully',
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
            $obj = ProductBrands::find($id);

            if ($obj) {
                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Product Brand Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Product Brand Not Found'
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

                $list = ProductBrands::where('name', $request->get('name'))->paginate($request->get('pageSize'));
            } else {
                $list = ProductBrands::paginate($request->get('pageSize'));
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

    public function getProductBrandById($id)
    {

        try {
            $productBrand = ProductBrands::find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $productBrand
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

            ProductBrands::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Products Brands Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
