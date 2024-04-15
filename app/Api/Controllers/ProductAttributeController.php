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
            
            // if (isset($attributeData['id'])){
            //     if ($attributeData['id']){
            //         ProductAttributeValue::where(['product_attribute_id' => $attributeData['id']])->delete();
            //     }
            // }   

            $variants = $request->only('variants');

            if (isset($variants['variants'])){

                foreach ($variants['variants'] as $variant){
                    
                    $productAttributeValueData = [];
                    
                    $productAttributeValueData['name'] = $variant['name'];
                    $productAttributeValueData['product_attribute_id'] = $attribute->id;
                    $productAttributeValueData['type'] = $attributeData['option'];
                    $productAttributeValueData['status'] = 'show';

                    ProductAttributeValue::updateOrCreate(['id' => (isset($variant['id']) ? $variant['id'] : null)] , $productAttributeValueData);
    
                }
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

                $list = ProductAttribute::where('name' , $name)->paginate($request->get('pageSize'));
            }

            else{
                $list = ProductAttribute::paginate($request->get('pageSize'));
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

    public function getValues ($id , Request $request) {

        try{

            $list = ProductAttributeValue::where('product_attribute_id' , $id)->paginate($request->get('pageSize'));

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

    public function getById($id){

        try {
            $attribute = ProductAttribute::with('Variants')->find($id);

            if ($attribute){

                return response()->json([
                    'status_code' => 200,
                    'attribute' => $attribute
                ]);

            }

            else{
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Attribute Not Found'
                ]);
            }

            
        }

        catch (\Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function multipleDelete(request $request){

        try {
            $ids = explode(",",  $request->only('ids')['ids']);

            ProductAttribute::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Product Attribute Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

}