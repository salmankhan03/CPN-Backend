<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

use App\Models\ProductSubCategories;

use Illuminate\Http\Request;


class ProductSubCategoryController extends Controller
{

    public function upsert(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|unique:product_sub_categories,name,NULL,id,deleted_at,NULL',
                'category_id' => 'required',
            ]);

            $request = $request->only(
                'id',
                'name',
                'category_id'
            );

            $data['name'] = $request['name'];
            $data['category_id'] = $request['category_id'];

            ProductSubCategories::updateOrCreate(['id' => $request['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Sub Category Saved Successfully',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message'     => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $obj = ProductSubCategories::find($id);
            if ($obj) {

                $obj->delete();
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Sub Category Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        try {
            $list = ProductSubCategories::with('category')->get();

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function mapCategoryWithSubCategory(Request $request)
    {

        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required|array',
        ]);
    }
}
