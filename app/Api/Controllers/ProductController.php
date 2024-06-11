<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


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
                'status',
                'brand_id',
                'brand',
                'is_tax_apply',
                'sell_price',
                'visitors_counter',
                'variants',
                'variants_array',
                'ratings',
                'is_featured',
                'sell_price_is_change',
                'ratings_is_change',
                'is_featured_is_change'
            );

            $tags = $request->only('tags');
            
            if(!empty($data['id'])){

                
                if ($data['sell_price_is_change']){

                    $data['sell_price_updated_at'] = Carbon::now();
                    
                }

                if ($data['ratings_is_change']){

                    $data['ratings_updated_at'] = Carbon::now();
                    
                }

                if ($data['is_featured_is_change']){

                    $data['is_featured_updated_at'] = Carbon::now();
                    
                }

            }

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

            //remove old tags

            ProductTag::where(['product_id' => $product->id])->delete();

            if (isset($tags['tags'])){

                foreach(json_decode($tags['tags']) as $tag){
                    if (!empty($tag)){

                        ProductTag::create([
                            'name' => trim($tag),
                            'product_id' => $product->id
                        ]);
                    }
                }

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

                ProductTag::where(['product_id' => $id])->delete();
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

            $qb = Product::with('images', 'category','tags');

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

            if ($request->get('title')) {
                $criteria[] = ['name', 'like', '%' . $request->get('title') . "%"];
            }

            $qb->where($criteria);

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

    public function getProductById(Request $request , $id)
    {

        try {

            $noOfRelatedProducts = $request->get('reltedProducts');

            $product = Product::with('images', 'category','tags')->find($id);

            Product::where('id', $id)->increment('visitors_counter'); // update the counter with 1

            $finalResult = null;

            $relatedProducts = Product::with('images', 'category','tags')->where('id' , '!=' , $id)->where('category_id' , $product->category_id)->orderBy('id' , 'DESC')->take($noOfRelatedProducts)->get();

            $relatedProductIds = [];

            foreach ($relatedProducts as $relatedProduct){

                $relatedProductIds[] = $relatedProduct->id;

            }

            $noOfRelatedProductsGot = count($relatedProducts);

            if ($noOfRelatedProductsGot < $noOfRelatedProducts){

                $noOfFeaturedProducts = $noOfRelatedProducts - $noOfRelatedProductsGot;
              

                if ($noOfFeaturedProducts){

                    $featuredProducts  = Product::with('images', 'category','tags')->whereNotIn('id' , $relatedProductIds)->where('is_featured' , 1)->orderBy('is_featured_updated_at' , 'DESC')->take($noOfFeaturedProducts)->get();
                    
                    $finalResult = array_merge($relatedProducts->toArray() , $featuredProducts->toArray());
                }

            }else{
                $finalResult = $relatedProducts;
            }

            $product->relatedProducts = $finalResult;

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

            ProductTag::whereIn('product_id' , $ids)->delete();

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

            // is_featured_updated_at ,, DESC >> to get the featured products
            // created_at ,, DESC >> to get the newest product
            // sell_price_updated_at ,, DESC >> to get the product on sale first
            // most viewed ,, visitors_counter >> DESC


            $list = [];

            $queryBuilder = $list = Product::with('images', 'category','tags');

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

            if ($request->get('sort')) {

                if (array_key_first($request->get('sort')) == 'is_featured_updated_at'){

                    $queryBuilder->where('is_featured', 1);
                    
                }

                if (array_key_first($request->get('sort')) == 'ratings_updated_at'){
                    $queryBuilder->orderBy('ratings' , 'DESC');
                }

                $queryBuilder->orderBy(array_key_first($request->get('sort')), array_values($request->get('sort'))[0]);
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

    public function getFeaturedProductsList(Request $request)
    {
        try {

            $categoryId = $request->get('category_id');

            $qb = Product::with('images', 'category','tags');

            if ($categoryId) {
                $qb->where('category_id', $categoryId);
            }

            $qb->where('is_featured' , 1);

            $featuredProducts = $qb->orderBy('updated_at', 'DESC')->get();

            return response()->json([
                'status_code' => 200,
                'list' => $featuredProducts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function dashboardProductList()
    {
        // new products
        // selling products
        // top rated products

        try {

            $newProducts = Product::orderBy('created_at', 'DESC')->with('images', 'category','tags')->get();
            $productsOnSale = Product::where('sell_price', '>', '0')->orderBy('updated_at', 'DESC')->with('images', 'category','tags')->get();
            $topViewedProducts = Product::orderBy('visitors_counter', 'DESC')->with('images', 'category','tags')->get();

            return response()->json([
                'status_code' => 200,
                'list' => [
                    'newProducts' => $newProducts,
                    'productsOnSale' => $productsOnSale,
                    'topRatedProducts'  => $topViewedProducts
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductForGenericSearch(Request $request){

        //searchParams = tags
        // this api will return the list of tags that mathces with the search keyword

        //whereJsonContains will return the row , only if the whole array is found in the given field (in this case it is `tags` column)

        try{

            // we will search for upper and lower case for search keyword

            $uniqueSearchKeywords = [];

            $keyWord = trim($request->get('searchParam'));

            $results = ProductTag::select('name')->where('name', 'like', '%' . $keyWord . '%')->get();

            foreach ($results as $result){

                $name = $result->name;
            
                if (!empty($name)){
                    
                    $uniqueSearchKeywords[] = $name;
                    
                }
                
            }

            return response()->json([
                'list' => array_count_values($uniqueSearchKeywords),
                'status_code' => 200
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function getProductListForGenericSearch(Request $request){
        try{

            $keyWord = trim($request->get('searchParam'));

            $productIds = ProductTag::select('product_id')->where('name', 'like', '%' . $keyWord . '%')
                                ->groupBy('product_id')->pluck('product_id');
                    

            $products = Product::with('images', 'category','tags')->whereIn('id' , $productIds)->get();

            return response()->json([
                'list' => $products,
                'status_code' => 200
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

}
