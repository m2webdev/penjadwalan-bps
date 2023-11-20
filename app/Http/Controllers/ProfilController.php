<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    public function index()
    {
        return response()->view('profil');
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:8', 'unique:users']
        ]);
        $user = auth()->user();
        $user->username = $request->username;
        $user->save();
        return back()->with('success', 'Berhasil mengubah username');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_sekarang' => ['required']
        ]);
        if (!Hash::check($request->password_sekarang, auth()->user()->password))
            throw ValidationException::withMessages([
                'password_sekarang' => 'password tidak sama'
            ]);
        $request->validate([
            'password_baru' => ['required', Password::min(8)],
            'konfirmasi_password_baru' => ['required', 'same:password_baru']
        ]);
        $user = auth()->user();
        $user->password = bcrypt($request->password_baru);
        $user->save();
        return back()->with('success', 'Berhasil mengubah password');
    }
}
