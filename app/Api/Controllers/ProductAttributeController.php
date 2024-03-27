<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;



class ProductAttributeController extends Controller{

    public function upsert(Request $request){

        try {
            $attributeData = $request->only([
                'name',
                'title',
                'option',
                'status',
                'id'
            ]);
    
            $attribute = ProductAttribute::updateOrCreate(['id' => $attributeData['id']] , $attributeData);

            if (isset($attributeData['id'])){
                if ($attributeData['id']){
                    ProductAttributeValue::where(['product_attribute_id' => $attributeData['id']])->delete();
                }
            }   

            $variants = $request->only('variants');

            foreach ($variants as $variant){

                $productAttributeValue = [];
                
                $productAttributeValue['name'] = $variant;
                $productAttributeValue['product_attribute_id'] = $attribute->id;
                $productAttributeValue['type'] = $attributeData['option'];
                $productAttributeValue['status'] = 'show';
            
                ProductAttributeValue::create($productAttributeValue);

            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Product Attribute Saved Successfully'
            ]);


        }

        catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function delete($id){

        try {
            

            ProductAttribute::destroy($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Product Attribute Deleted Successfully'
            ]);


        }

        catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function list(Request $request){

        try {   

            $name = $request->get('name');

            if ($name){

                $list = ProductAttribute::where('name' , $name)->get();
            }

            else{
                $list = ProductAttribute::get();
            }


            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);

        }

        catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function getValues ($id) {

        try{

        $list = ProductAttributeValue::where('product_attribute_id' , $id)->get();

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);

        }

        catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }

}