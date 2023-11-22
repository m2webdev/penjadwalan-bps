<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index()  
    {
        $users = User::where('role', 'admin')->orWhere('role', 'pengguna')->latest()->get();
        return view('user/index', ['users' => $users]);
    }

    function create(Request $request)
    {
        $request['username'] = $request->username_tambah;
        $request->validate([
            'fullname_tambah' => 'required',
            'jk_tambah' => 'required',
            'username' => 'required|unique:users',
            'role_tambah' => 'required',
            'password_tambah' => 'required',
            'agama_tambah' => 'required',
        ]);



        User::create([
            'name' => $request->input('fullname_tambah'),
            'jk' => $request->input('jk_tambah'),
            'role' => $request->input('role_tambah'),
            'username' => $request->input('username_tambah'),
            'password' => Hash::make($request->input('password_tambah')),
            'agama' => $request->input('agama_tambah'),
            'telegram_id' => $request->input('telegram_id'),
            'no_wa' => $request->input('no_wa')

        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil dibuat!');
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'jk' => 'required',
            'role' => 'required',
            'agama' => 'required',
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('akun.index')->with('error', 'Pengguna tidak ditemukan.');
        }

        $user->name = $request->input('name');
        $user->jk = $request->input('jk');
        $user->role = $request->input('role');
        $user->agama = $request->input('agama');
        $user->telegram_id = $request->input('telegram_id');
        $user->no_wa = $request->input('no_wa');
        if($request->password != null)
        {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil Diubah!');
    }

    function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('akun.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
