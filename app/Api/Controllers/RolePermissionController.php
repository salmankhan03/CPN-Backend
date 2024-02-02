<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermissions;

class RolePermissionController extends Controller
{

    public function saveRole(Request $request)
    {
        try {
            $role = Role::updateOrCreate(['id' => $request->id], ['name' => $request->name]);

            return response()->json([
                'status_code' => 200,
                'message' => 'Role Saved Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function savePermission(Request $request)
    {
        try {
            $permission = Permission::updateOrCreate(['id' => $request->id], ['name' => $request->name]);

            return response()->json([
                'status_code' => 200,
                'message' => 'Permission Saved Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function synRolePermissions(Request $request)
    {
        try {

            $roleId = $request->get('roleId');

            $permissions = $request->get('permissions'); // array of permissions

            $role = Role::find($roleId);


            $role->syncPermissions($permissions);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Permissions For this role is updated',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status_code' => 500,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    public function deleteRole($roleId)
    {
        try {

            $result = Role::where('id', $roleId)->delete();

            return response()->json([
                'status_code' => 200,
                'message'     => 'Role Deleted Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status_code' => 500,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    public function deletePermission($permissionId)
    {
        try {

            $result = Role::where('id', $permissionId)->delete();

            return response()->json([
                'status_code' => 200,
                'message'     => 'Permission Deleted Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status_code' => 500,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    public function roleList()
    {
        try {
            $roles = Role::all();

            return response()->json([
                'status_code' => 200,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function permissionList()
    {
        try {
            $roles = Permission::all();

            return response()->json([
                'status_code' => 200,
                'permissions' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function rolePermissionList($roleId)
    {
        try {
            $roles = Permission::all();

            return response()->json([
                'status_code' => 200,
                'permissions' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function permissionListForRole($roleId)
    {
        try {

            $permissionList = RoleHasPermissions::with('Permission')->where('role_id', $roleId)->get();

            return response()->json([
                'status_code' => 200,
                'permissions' => $permissionList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
