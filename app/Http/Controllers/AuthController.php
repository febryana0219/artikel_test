<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view("auth.index");
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.min' => 'Password harus lebih dari 6 karakter!'
        ]);
        $kredensial = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($kredensial)) {
            return redirect()->route('articles.index');
        }
        return redirect()->route('auth.index')->with(['pesan' => 'Email atau password yang anda masukan salah!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // $request->session()->
        return redirect()->route('auth.index');
    }
}
