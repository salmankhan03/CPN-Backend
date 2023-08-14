<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\EmailReset;
use App\Http\Requests\ResetPassword\ResetPasswordRequest;
use App\Models\PasswordResetTokens;
use App\Models\RoleMenuItemMap;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;
use Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function saveMenuList(Request $request)
    {
        try {

            $roleId           = $request->role_id;
            $menuItems    = explode(',', $request->menu_items);

            if ($menuItems) {

                RoleMenuItemMap::where(['role_id' => $roleId])->delete();

                foreach ($menuItems as $menuItem) {

                    RoleMenuItemMap::create([
                        'role_id'            => $roleId,
                        'menu_item_id'           => $menuItem
                    ]);
                }

                return response()->json([
                    'status_code' => 200,
                    'message'     => 'Menu Items Updated For The Role',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status_code' => 500, 'message' => $th->getMessage()], 500);
        }
    }
}
