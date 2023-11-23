<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;


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
