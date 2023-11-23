<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class VerificationController extends Controller
{
    use VerifiesEmails, RedirectsUsersBasedOnRoles;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {

        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function redirectPath()
    {
        return $this->getRedirectTo(FacadesAuth::guard()->user());
    }


    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified())
            event(new Verified($user));

        return redirect($this->redirectPath())->with('verified', true);
    }
}
