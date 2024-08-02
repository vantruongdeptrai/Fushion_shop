<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    public function showFormLogin(){
        //dd(Hash::make('12345'));
        return view('auth.login');
    }
    public function login(Request $request){
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        //dd(Hash::make('admin'));
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(Auth::user()->isAdmin()){
                return redirect('/admin');
            }
            return redirect()->route('home');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
        
    }
    public function logout(){
        Auth::logout();
        \request()->session()->invalidate();
        return redirect('auth/login');
    }
}
