<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Order;
use App\Models\PasswordResetTokens;
use App\Models\Role;
use App\Models\RoleMenuItemMap;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{

    function __construct()
    {
        Config::set('jwt.user', AdminUser::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => AdminUser::class,
        ]]);
    }

    public function unauthorized()
    {

        return response()->json([
            'status_code' => 401,
            'message'     => 'unauthorized',
        ], 401);
    }

    public function login(Request $request)
    {

        //form-data and raw json body both can be parsed with same request

        try {
            $credentials = $request->only('email', 'password');

            $user = AdminUser::where('email', $credentials['email'])->first();



            $token = JWTAuth::attempt($credentials);
            if ($token) {

                // if (!$user->email_verified_at) {
                //     return response()->json([
                //         'status_code' => 400,
                //         'message'     => 'Please Verify Your Email First!',
                //     ], 400);
                // }

                $user = \Auth::user();

                return response()->json([
                    'status_code' => 200,
                    'data'        => $user,
                    'token'       => $token,
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message'     => 'Incorrect email address or password',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['status_code' => 500, 'message' => 'Could not create token'], 500);
        }
    }

    public function signUp(Request $request)
    {
        try {

            $data             = $request->only([
                'email', 'password', 'phone', 'profile_pic',
                'id',
                'role',
                'first_name',
                'last_name',
                'middle_name',
                'date_of_birth',
                'contact_no',
                'secondary_contact_number',
                'city',
                'state',
                'country',
                'zipcode'
            ]);

            $alreadyExistUser = AdminUser::where('email', $data['email'])->get();

            if ($alreadyExistUser) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'User With this Email Already Exist'
                ], 500);
            }

            $valiadated = $request->validate([]);

            if ($data['id']) {

                $validation = Validator::make($request->all(), [
                    'email' => 'required|unique:admin_panel_users,email,' . $data['id'] . ',id,deleted_at,NULL',
                ]);
            } else {
                $validation = Validator::make($request->all(), [
                    'email' => 'required|unique:admin_panel_users,email,NULL,id,deleted_at,NULL',
                ]);
            }


            if ($validation->fails()) {

                return response()->json([
                    'status_code' => 500,
                    'message'     => $validation->errors()->messages()['email'][0],
                ], 500);
            }

            if ($data['role']) {

                $role = Role::where('name', $data['role'])->first();

                if (!$role) {
                    return response()->json([
                        'status_code' => 400,
                        'message'     => $data['role'] . ' - Role Not Exist',
                    ], 400);
                }

                $data['role_id'] = $role->id;
            }

            $orignal_password = $data['password'];
            $data['password'] = Hash::make($data['password']);
            $data['role_id'] = $data['role_id'] ? $data['role_id'] : User::CUSOTMER_ROLE_ID;
            $data['contact_no'] = $data['phone'];

            $user = AdminUser::updateOrCreate(['id' => $request['id']], $data);

            $user->orignal_password = $orignal_password;

            $token            = \JWTAuth::fromUser($user);
            if ($user) {

                $user       = AdminUser::find($user->id);
                $user->role = AdminUser::CUSOTMER_ROLE_NAME;

                // event(new Registered($user));
            }
            return response()->json([
                'status_code' => 200,
                'data'        => $user,
                'token'       => $token,
            ]);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }


    public function forgetPassword(Request $request)
    {
        try {
            $user = AdminUser::where('email', $request->get('email'))->first();

            if (!$user) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Entered email address not found.',
                ], 400);
            } else {
                $resetToken = uniqueProfileUrl(30);
                PasswordResetTokens::where('email', $request->get('email'))->delete();
                $passwordReset = PasswordResetTokens::insert(
                    ['email' => $request->get('email'), 'token' => $resetToken]
                );

                $data = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'resetUrl' => url('/password/reset?email=' . $user->email . '&token=' . $resetToken),
                ];

                $user->notify(new ForgetPasswordNotification($data));

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Forget password link has been sent on your registered email address.',
                ]);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function getUser()
    {
        try {

            $user = \Auth::user();

            if ($user) {

                $user = AdminUser::find($user->id);
                $menuList = RoleMenuItemMap::with('menuItem')->where('role_id', $user->role_id)->get()->toArray();

                $menus = [];

                foreach ($menuList as $item) {
                    $menus[] = $item['menu_item'];
                }

                $user->menuList = $menus;

                return response()->json([
                    'status_code' => 200,
                    'user'        => $user
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'user'        => 'User Not Found'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->delete($id);
            }

            return response()->json([
                'status_code' => 200,
                'message' => "User Deleted Successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {

            $name = $request->get('name');

            if ($request->get('name')) {
                $list = User::where('first_name', $name)->orderBy('created_at', 'DESC')->with('menuList', 'role')->paginate($request->get('pageSize'));
            } else {

                $list = User::orderBy('created_at', 'DESC')->with('menuList', 'role')->paginate($request->get('pageSize'));
            }

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function customerDelete($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->delete();
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => "User Not Found"
                ], 500);
            }

            return response()->json([
                'status_code' => 200,
                'message' => "User Deleted Successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function customerList()
    {
        try {

            $list = User::orderBy('created_at', 'DESC')->with('menuList', 'role')->get();

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getOrders($id)
    {

        try {
            $orders = Order::with('shippingAddress', 'billingAddress', 'payment', 'Items.product.images')->where(['user_id' => $id])->get();

            return response()->json([
                'status_code' => 200,
                'list' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}