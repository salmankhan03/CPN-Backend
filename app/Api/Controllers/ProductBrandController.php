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

                $defaultBrand = ProductBrands::where('name', ProductBrands::DEFAULT_BRAND_NAME)->first();

                if ($defaultBrand->id == $id) {
                    return response()->json(
                        [
                            'status_code' => 500,
                            'message' => "Can't Delete the Default Brand"
                        ]
                    );
                }

                Product::where('brand', $obj->name)->update(['brand' => ProductBrands::DEFAULT_BRAND_NAME]);
                Product::where('brand_id', $id)->update(['brand_id' => $defaultBrand->id]);

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

            $defaultBrand = ProductBrands::where('name', ProductBrands::DEFAULT_BRAND_NAME)->first();

            if (in_array($defaultBrand->id, $ids)) {
                return response()->json(
                    [
                        'status_code' => 500,
                        'message' => "Can't Delete the Default Brand"
                    ]
                );
            }


            $productNames = ProductBrands::selcet('name')->whereIn('brand', $ids)->get();


            Product::whereIn('brand', $productNames)->update(['brand' => ProductBrands::DEFAULT_BRAND_NAME]);
            Product::whereIn('brand_id', $ids)->update(['brand_id' => $defaultBrand->id]);

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
