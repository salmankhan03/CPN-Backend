<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetTokens;
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
use Tymon\JWTAuth\Exceptions\JWTException;


class RolePermissionController extends Controller
{

    public function saveRole()
    {
        return response()->json([
            'status_code' => 200,
            'message'     => 'hello from index',
        ], 200);
    }

    public function savePermission()
    {
        return response()->json([
            'status_code' => 200,
            'message'     => 'hello from index 2',
        ], 200);
    }

    public function synRolePermissions()
    {

        return response()->json([
            'status_code' => 401,
            'message'     => 'unauthorized',
        ], 401);
    }

    public function deleteRole($roleId)
    {
    }

    public function deletePermission($permissionId)
    {
    }

    public function rolePermissionList()
    {
    }
}
