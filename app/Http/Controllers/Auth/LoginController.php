<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
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
                    // $user->createToken('authToken')->plainTextToken;
                    # redirect last url
                    return redirect()->intended();
                } else {
                    # email not verify
                    Session::flash('bg', 'alert-danger');
                    if (!$user->email_verified_at) {
                        # send again email verified
                        Session::flash('message', __('global.user_email_not_verified'));
                        return redirect()->back();
                    }
                    Session::flash('message', __('global.user_not_active'));
                    return redirect()->back();
                }
            } else {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.password_not_valid'));
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            # throw $th;
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
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
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
