<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    function index()
    {
        return view("auth/login");
    }

    public function login(Request $request)
    {
        Session::flash('email', $request->email);
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], );

        $emailOrUsername = $request->input('email');
        $password = $request->input('password');

        if (strpos($emailOrUsername, '@') !== false) {
            $credentials = ['email' => $emailOrUsername, 'password' => $password];
        } else {
            $credentials = ['username' => $emailOrUsername, 'password' => $password];
        }

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        } else {
            $user = User::where('email', $emailOrUsername)->first();
            $errorMessage = strpos($emailOrUsername, '@') !== false ? 'Email Yang Dimasukkan Tidak Valid' : 'Username Yang Dimasukkan Tidak Valid';
            $errorMessage = $user ? 'Password Yang Dimasukkan Tidak Valid' : $errorMessage;
            return redirect('auth')->withErrors($errorMessage);
        }
    }

    function logout()
    {
        Auth::Logout();
        return redirect('auth')->with('success', 'Berhasil Logout');
    }

    function forget()
    {

    }
}
