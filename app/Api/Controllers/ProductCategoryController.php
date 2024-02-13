<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryImages;
use App\Models\Product;
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

            $requestData = $request->only(
                'id',
                'name',
                'description',
                'parent_id',
                'status',
            );

            $data = [];

            $data['name'] = $requestData['name'];
            $data['description'] = $requestData['description'];
            $data['parent_id'] = $requestData['parent_id'];
            $data['status'] = $requestData['status'];

            $category = ProductCategory::updateOrCreate(['id' => $requestData['id']], $data);

            $files = $_FILES;

            if (count($files) && isset($requestData['id'])) {
                if ($requestData['id']) {

                    CategoryImages::where(['category_id' => $requestData['id']])->delete();
                }
            }

            foreach ($files as  $fileName => $file) {

                $categoryImage = [];

                $image = $request->file($fileName);

                $categoryImage['original_name'] = $image->getClientOriginalName();
                $categoryImage['category_id'] = $category->id;
                $categoryImage['name'] = $image;

                CategoryImages::create($categoryImage);
            }


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

                $defaultCategory = ProductCategory::where('name', ProductCategory::DEFAULT_PRODUCT_CATEGORY)->first();
                if ($defaultCategory) {
                    if ($defaultCategory->id == $id) {
                        return response()->json(
                            [
                                'status_code' => 500,
                                'message' => "Can't Delete the Default Product Category"
                            ]
                        );
                    }
                } else {
                    return response()->json(
                        [
                            'status_code' => 500,
                            'message' => "Default Category Not Found"
                        ]
                    );
                }


                Product::whereIn('category_id', $id)->update(['category_id' => $defaultCategory->id]);

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

            $list = ProductCategory::with('categoryImage')->paginate($request->get('pageSize'))->makeVisible(['description', 'parent_id']);

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
            $category = ProductCategory::with('categoryImage')->find($id);

            if ($category) $category->makeVisible(['description', 'parent_id']);

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

    public function getCategoryTree(Request $request)
    {
        try {

            $filter = [];

            if ($request->get('name')) {

                $filter['name'] = $request->get('name');
            }


            $result = ProductCategory::select('id', 'name', 'description', 'status')->whereNull('parent_id')->where($filter)->with(['children', 'categoryImage'])->paginate($request->get('pageSize'))->toArray();

            return response()->json([

                'status_code' => 200,
                'tree' => $result

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

            $defaultCategory = ProductCategory::where('name', ProductCategory::DEFAULT_PRODUCT_CATEGORY)->first();

            if ($defaultCategory) {
                if (in_array($defaultCategory->id, $ids)) {
                    return response()->json(
                        [
                            'status_code' => 500,
                            'message' => "Can't Delete the Default Product Category"
                        ]
                    );
                }
            } else {
                return response()->json(
                    [
                        'status_code' => 500,
                        'message' => "Default Category Not Found"
                    ]
                );
            }


            Product::whereIn('category_id', $ids)->update(['category_id' => $defaultCategory->id]);

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
