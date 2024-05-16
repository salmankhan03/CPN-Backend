<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermissions;
use App\Models\SliderImages;
use Illuminate\Support\Facades\Auth;



class SliderImageController extends Controller
{
    public function upload(Request $request){
        try {
            $data = $request->only([
                'side'
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
    
                SliderImages::create($imageData);
            }

            $this->response()->json([
                'status_code' => 200,
                'message' => 'Slider Images Uploaded Successfully'
            ]);
        }

        catch (\Exception $e){
            $this->response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
       


    }
}