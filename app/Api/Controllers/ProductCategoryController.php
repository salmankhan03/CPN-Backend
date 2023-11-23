<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class ProductCategoryController extends Controller
{

    public function upsert(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'status' => 'required'
            ]);

            $request = $request->only(
                'id',
                'name',
                'description',
                'parent_id',
                'status',
            );

            $data['name'] = $request['name'];
            $data['description'] = $request['description'];
            $data['parent_id'] = $request['parent_id'];
            $data['status'] = $request['status'];

            $category = ProductCategory::updateOrCreate(['id' => $request['id']], $data);



            return response()->json([
                'status_code' => 200,
                'message'     => 'Category Saved Successfully',
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
            $obj = ProductCategory::find($id);
            if ($obj) {

                $obj->delete();
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Category Deleted Successfully'
                ]);
            }

            return response()->json([
                'status_code' => 500,
                'message' => 'Category Not Found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {


            $list = ProductCategory::paginate($request->get('pageSize'));

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

    public function getProductCategoryById($id)
    {
        try {
            $category = ProductCategory::find($id);

            return response()->json([
                'status_code' => 200,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCategoryTree()
    {
        try {

            $result = ProductCategory::with('descendants')->get()->toArray();

            return response()->json([

                'status_code' => 200,
                'category' => $result

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

            ProductCategory::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Categories Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
