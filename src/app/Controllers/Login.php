<?php

namespace erfan_kateb_saber\admin_panel\app\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Login extends Controller
{
    public function index()
    {
        return view('main::login');
    }

    public function loginPage()
    {
        return view('admin_panel.login');
    }

    public function doLogin(Request $request)
    {
        //validation
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        //login
        if (Auth::guard('admin_panel')->attempt(request()->only('username', 'password'))) {
            //redirect when login successful
            return redirect()->route('admin_panel.index');
        }

        //redirect when login faild
        return back()->withInput()->with('modal', [
            'title' => 'خطا',
            'text' => 'ورود ناموفق',
            'isShow' => true
        ]);
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return Redirect::route('admin_panel.loginPage');
    }
}
