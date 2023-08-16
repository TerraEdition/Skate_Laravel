<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    #
    public function index()
    {
        return view('Auth.Login');
    }

    public function store(StoreRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->is_active == '1') {
                    $user->createToken('authToken')->plainTextToken;
                    # redirect last url
                    return redirect()->intended();
                } else {
                    # email not verify
                    if (!$user->email_verified_at) {
                        # send again email verified
                        return redirect()->back()->with('msg', __('global.user_email_not_verified'));
                    }
                    return redirect()->back()->with('msg', __('global.user_not_active'));
                }
            } else {
                return redirect()->back()->with('msg', __('global.login_not_valid'));
            }
        } catch (\Throwable $th) {
            # throw $th;
            return redirect()->back()->with('msg', __('global.error_system'));
        }
    }

    public function destroy(Request $request)
    {
        try {
            Auth::guard('web')->logout(); # Logout dari guard 'web'
            $request->session()->invalidate(); # Invalidasi sesi

            # delete token Sanctum
            $request->user()->currentAccessToken()->delete();
            return redirect()->to('login')->with('msg', __('global.logout_successfull'));
        } catch (\Throwable $th) {
            # throw $th;
            return redirect()->back()->with('msg', __('global.error_system'));
        }
    }
}
