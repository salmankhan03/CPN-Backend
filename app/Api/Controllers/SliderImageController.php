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
    }
}