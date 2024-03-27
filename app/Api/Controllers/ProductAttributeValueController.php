<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;


class ProductAttributeValueController extends Controller{

    public function upsert(Request $request){

        try {
            $attributeValueData = $request->only([
                'id',
                'name',
                'type',
                'status',
                'product_attribute_id'
            ]);
    
            ProductAttributeValue::updateOrCreate(['id' => $attributeValueData['id']] , $attributeValueData);

            return response()->json([
                'status_code' => 200,
                'message' => 'Product Attribute Value Saved Successfully'
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

            ProductAttributeValue::destroy($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Product Attribute Value Deleted Successfully'
            ]);

        }

        catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }


    }

    public function list($productAttributeId){

        try {   
    
            $list = ProductAttributeValue::where('product_attribute_id' , $productAttributeId)->get();
        
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
            $attribute = ProductAttributeValue::find($id);

            if ($attribute){

                return response()->json([
                    'status_code' => 200,
                    'attributeValue' => $attribute
                ]);

            }

            else{
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Attribute Value Not Found'
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

            ProductAttributeValue::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Product Attribute Values Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }


 

}