<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderImages;



class SliderImageController extends Controller
{
    public function upload(Request $request){

        try {

            $requestData = $request->only(
                'heading',
                'content',
                'buttonLabel',
                'buttonUrl',
                'contentPosition',
                'id'
            );
    
            $user = \Auth::user();
    
            $imageData = [];

            $files = $_FILES;
    
            foreach ($files as  $fileName => $file) {
    
                $image = $request->file($fileName);
    
                $imageData['image'] = $image;
                $imageData['original_name'] = $image->getClientOriginalName();
                
            }

            $imageData['created_by'] = $user->id;
                
            $imageData['id'] = $requestData['id'];
            $imageData['heading'] = $requestData['heading'];
            $imageData['content'] = $requestData['content'];
            $imageData['button_label'] = $requestData['buttonLabel'];
            $imageData['button_url'] = $requestData['buttonUrl'];
            $imageData['content_position'] = $requestData['contentPosition'];

            SliderImages::updateOrCreate(['id' => $requestData['id']], $imageData);

            return response()->json([
                'status_code' => 200,
                'message' => 'Slider Images Uploaded Successfully'
            ]);
        }

        catch (\Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    
    }

    public function delete($id){
        try{

            $obj = SliderImages::find($id);
            $user = \Auth::user();

            if ($obj && $user) {

                $obj->deleted_by = $user->id;

                $obj->save();

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Slider Image Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Slider Image Not Found'
                ], 500);
            }

        }

        catch (\Exception $e){
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list(){

        try{

            $data = SliderImages::all();
            
            return response()->json([
                'status_code' => 200,
                'list' => $data
            ]);

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

            $user = \Auth::user();

            foreach ($ids as $id){

                $obj = SliderImages::find($id);
                
                if ($obj){
                    $obj->deleted_by = $user->id;
                    $obj->save();
                }
                

            }

            SliderImages::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Slider Images Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getById($id){

        try {
            $imageData = SliderImages::find($id);

            if ($imageData){

                return response()->json([
                    'status_code' => 200,
                    'data' => $imageData
                ]);

            }

            else{
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Slider Image Not Found'
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
}