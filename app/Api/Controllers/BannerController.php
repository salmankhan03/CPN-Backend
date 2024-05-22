<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BannerImages;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function upload(Request $request){

        try {
            
            $data = $request->only([
                'side',
                'heading',
                'content',
                'buttonLabel',
                'buttonUrl',
                'contentPosition',
                'id'
            ]);
    
            $user = \Auth::user();
    
            $files = $_FILES;
    
            foreach ($files as  $fileName => $file) {
    
                $imageData = [];
    
                $image = $request->file($fileName);
    
                $imageData['image'] = $image;
                $imageData['original_name'] = $image->getClientOriginalName();
                $imageData['created_by'] = $user->id;
                $imageData['side'] = $data['side'];

                $imageData['id'] = $data['id'];
                $imageData['heading'] = $data['heading'];
                $imageData['content'] = $data['content'];
                $imageData['button_label'] = $data['buttonLabel'];
                $imageData['button_url'] = $data['buttonUrl'];
                $imageData['content_position'] = $data['contentPosition'];
    
                BannerImages::updateOrCreate(['id' => !empty($data['id']) ? $data['id'] : null], $imageData);
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Banner Images Uploaded Successfully'
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

            $obj = BannerImages::find($id);
            $user = \Auth::user();

            if ($obj && $user) {

                $obj->deleted_by = $user->id;

                $obj->save();

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Banner Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Banner Not Found'
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

            $leftBanner = BannerImages::where('side' , BannerImages::SIDE_LEFT)->orderBy('id', 'desc')->first();
            $rightBanner = BannerImages::where('side' , BannerImages::SIDE_RIGHT)->orderBy('id', 'desc')->first();

            $data = [];

            if ($leftBanner){

                $data[] = [
                    'id' => $leftBanner->id,
                    'link' => $leftBanner->getImageAttribute(),
                    'side' => $leftBanner->side,
                    'heading' => $leftBanner->heading,
                    'content' => $leftBanner->content,
                    'button_label' => $leftBanner->button_label,
                    'button_url'=> $leftBanner->button_url,
                    'content_position' => $leftBanner->content_position
                ];

            }

            if ($rightBanner){

                $data[] = [
                    'id' => $rightBanner->id,
                    'link' => $rightBanner->getImageAttribute(),
                    'side' => $rightBanner->side,
                    'heading' => $rightBanner->heading,
                    'content' => $rightBanner->content,
                    'button_label' => $rightBanner->button_label,
                    'button_url'=> $rightBanner->button_url,
                    'content_position' => $rightBanner->content_position
                ];

            }

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

    public function getById($id){
        
        try {
            $imageData = BannerImages::find($id);

            if ($imageData){

                return response()->json([
                    'status_code' => 200,
                    'data' => $imageData
                ]);

            }

            else{
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Banner Image Not Found'
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