<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function upsert(Request $request)
    {
        try {

            // print_r($_FILES);
            // die;
            $data = $request->only(
                'id',
                'name',
                'price',
                'currency',
                'produced_by',
                'shipping_weight',
                'product_code',
                'upc_code',
                'package_quantity',
                'dimensions',
                'is_visible',
                'description',
                'suggested_use',
                'other_ingredients',
                'disclaimer',
                'category_id',
                'warnings',
                'sku',
                'bar_code',
                'quantity',
                'slug',
                'tags',
                'status',
                'brand_id',
                'brand',
                'is_tax_apply'
            );

            $product = Product::updateOrCreate(['id' => $data['id']], $data);

            $isExist = ProductBrands::where(['name' => $data['brand']])->exists();

            if (!$isExist) {
                $brandData['name'] = $data['brand'];
                $brandData['is_active'] = 1;

                $newProductBrand = ProductBrands::create($brandData);

                $product->brand_id = $newProductBrand->id;

                $product->save();
            }

            $files = $_FILES;

            foreach ($files as  $fileName => $file) {

                $productImage = [];

                $image = $request->file($fileName);

                $productImage['original_name'] = $image->getClientOriginalName();
                $productImage['product_id'] = $product->id;
                $productImage['name'] = $image;

                ProductImages::create($productImage);
            }

            return response()->json([
                'status_code' => 200,
                'message'     => 'Product Saved Successfully',
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
            $obj = Product::find($id);

            if ($obj) {
                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Product Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Product Not Found'
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

            $criteria = [];

            if ($request->get('category')) {
                $criteria['category_id'] = $request->get('category');
            }

            if ($request->get('title')) {
                $criteria['name'] = $request->get('title');
            }

            if ($request->get('price')) {
                $criteria['price'] = $request->get('price');
            }

            $list = Product::with('images')->where($criteria)->paginate($request->get('pageSize'));

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

    public function getProductById($id)
    {

        try {
            $product = Product::with('images', 'category')->find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $product
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

            Product::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Products Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProducts(Request $request)
    {
        try {

            $categories = $request->get('category');
            $priceRange = $request->get('price');
            $brands = $request->get('brands');

            $list = [];

            $queryBuilder = $list = Product::with('images');

            if ($categories) {
                $queryBuilder->whereIn('category_id',  $categories);
            }
            if ($brands) {

                $queryBuilder->whereIn('brand', $brands);
            }

            if ($priceRange) {
                $queryBuilder->whereBetween('price', $priceRange);
            }
            $list = $queryBuilder->get();

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getMaxPrice()
    {

        try {
            $maxPrice = Product::max('price');

            return response()->json([
                'status_code' => 200,
                'max_price' => $maxPrice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
