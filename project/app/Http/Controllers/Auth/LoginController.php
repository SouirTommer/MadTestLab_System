<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $account = DB::table('Accounts')->where('Username', $request->username)->first();

        if ($account && Hash::check($request->password, $account->Password)) {
            
            Auth::loginUsingId($account->AccountID);
            return redirect('/welcome');
        } else {
            // 登入失敗
            return redirect()->back()->withErrors(['Invalid credentials']);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}