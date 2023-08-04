<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetTokens;
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


class UserController extends Controller
{

    public function login(Request $request)
    {

        //form-data and raw json body both can be parsed with same request

        try {
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
            if ($token) {
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

            $data             = $request->only(['name', 'email', 'password', 'role_id']);

            $emailExist = User::where('email', $data['email'])->first();
            if ($emailExist) {
                return response()->json([
                    'status_code' => 400,
                    'message'     => 'This email is already registered',
                ], 400);
            }
            $orignal_password = $data['password'];
            $data['password'] = Hash::make($data['password']);


            $user = User::create($data);

            $user->orignal_password = $orignal_password;

            $token            = \JWTAuth::fromUser($user);
            if ($user) {
                $user->roles()->sync($data['role_id']);
                $user       = User::find($user->id);
                $user->role = $user->roles()->first()->id;
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
            $user = User::where('email', $request->get('email'))->first();

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
}
