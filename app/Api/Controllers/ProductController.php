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
                'is_tax_apply',
                'sell_price'
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

            $qb = Product::with('images');

            $criteria = [];

            if ($request->get('category')) {
                $criteria['category_id'] = $request->get('category');
            }



            if ($request->get('price')) {
                $criteria['price'] = $request->get('price');
            }

            if ($request->get('quantity') == 1) {
                $criteria[] = ['quantity', '>', 0];
            }

            if ($request->get('quantity') == -1) {
                $criteria[] = ['quantity', '<=', 0];
            }

            $qb->where($criteria);

            if ($request->get('title')) {
                $qb->andWhere('name', 'like', '%' . $request->get('title') . "%");
            }

            if ($request->get('sort')) {
                $qb->orderBy(array_key_first($request->get('sort')), array_values($request->get('sort'))[0]);
            }

            $list = $qb->paginate($request->get('pageSize'));

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

            $categories = $request->get('category'); //[1,2] , for all it will be ""
            $priceRange = $request->get('price'); // [min, max]
            $brands = $request->get('brands'); // [brand1,brand2]
            $productName = $request->get('title'); // productName

            //mixed filters will be handled manuallly and discussed the sorting order

            $list = [];

            $queryBuilder = $list = Product::with('images');

            if (is_array($categories)) {
                if (count($categories)) {

                    $queryBuilder->whereIn('category_id',  $categories);
                }
            }
            if ($brands) {

                $queryBuilder->whereIn('brand', $brands);
            }

            if ($priceRange) {
                $queryBuilder->whereBetween('sell_price', $priceRange);
            }

            if ($productName) {
                $queryBuilder->where('name', $productName);
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

    public function demoFormUpload(Request $request)
    {
        try {
            // print_r($request->all());
            // echo "ff";
            // die;
            return response()->json([
                'status_code' => 500,
                'message' => [$_FILES, $request->all()]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'error' => $e->getMessage()
            ]);
        }
    }
}