<?php

namespace App\Api\Controllers;

use App\Api\Requests\Auth\ProductRequest;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetTokens;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDescription;
use App\Models\RoleMenuItemMap;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use JWTAuth;
use Auth;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;


class ProductCategoryController extends Controller
{

    public function upsert(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|unique:product_category,deleted_at,NULL'
            ]);

            $request = $request->only(
                'id',
                'name'
            );

            $data['name'] = $request['name'];

            ProductCategory::updateOrCreate(['id' => $request['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Category Saved Successfully',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message'     => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $obj = ProductCategory::find($id);
            if ($obj) {

                $obj->delete();
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Category Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        try {
            $list = ProductCategory::all();

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
