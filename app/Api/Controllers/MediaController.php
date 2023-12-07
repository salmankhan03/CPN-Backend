<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryImages;
use App\Models\ProductImages;
use Illuminate\Http\Request;


class MediaController extends Controller
{

    public function upload(Request $request)
    {
        try {
            $requestData = $request->only(
                'category_id',
                'product_id'
            );



            if (isset($requestData['category_id']) && isset($requestData['product_id'])) {
                return response()->json([
                    'status_code' => 500,
                    'message' => "Don't Select Category Id And Product Id Both"
                ], 500);
            }

            if (!isset($requestData['category_id']) &&  !isset($requestData['product_id'])) {
                return response()->json([
                    'status_code' => 500,
                    'message' => "Please Specify Either Category id or Product Id"
                ], 500);
            }

            if (!empty($requestData['category_id']) || !empty($requestData['product_id'])) {

                if ($request->only('images')) {

                    foreach ($request->only('images')['images'] as  $image) {

                        $data = [];

                        $data['original_name'] = $image->getClientOriginalName();

                        $data['name'] = $image;

                        if (!empty($requestData['product_id'])) {

                            $data['product_id'] = $request->get('product_id');
                            ProductImages::create($data);
                        }

                        if (!empty($requestData['category_id'])) {

                            $data['category_id'] = $request->get('category_id');
                            CategoryImages::create($data);
                        }
                    }

                    return response()->json([
                        'status_code' => 200,
                        'message'     => 'Images Uploaded Successfully',
                    ], 200);
                }
            }
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {

        try {
            $category_ids = explode(",",  $request->only('category_images')['category_images']);

            CategoryImages::whereIn('id', $category_ids)->delete();

            $product_ids = explode(",",  $request->only('product_images')['product_images']);

            ProductImages::whereIn('id', $product_ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Images Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function categoryImages()
    {
        try {

            $results = CategoryImages::with('category')->get();

            return response()->json([
                'status_code' => 200,
                'list' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function productImages()
    {
        try {

            $results = ProductImages::with('product')->get();

            return response()->json([
                'status_code' => 200,
                'list' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
