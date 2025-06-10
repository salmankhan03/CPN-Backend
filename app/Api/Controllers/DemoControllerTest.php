<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdate;
use App\Models\PasswordResetTokens;
use App\Models\Role;
use App\Models\RoleMenuItemMap;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class DemoControllerTest extends Controller
{

    public function sendMail()
    {
        try {

            Mail::html('test email from server', function ($message) {
                $message->to('dudhatrasmit007@gmail.com')
                    ->subject('text subject');
            });

            return response()->json([
                'status_code' => 200,
                'message'     => 'Mail sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message'     => $e->getMessage()
            ]);
        }
    }
}
