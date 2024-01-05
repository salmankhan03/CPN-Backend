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
        $data = [];
        Mail::to('smitd@intellidt.com')->send(new OrderStatusUpdate($data));
    }
}
