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
            if(Auth::user()->isAdmin()){
                return redirect()->route('dashboard');
            }else{
                return back()->with('error','Tài khoản không đủ quyền truy cập !');
            }
            
        }
        
        return back()->withErrors([
            'email' => 'Tài khoản không tồn tại',
        ])->onlyInput('email');
        
    }
    public function logout(){
        Auth::logout();
        \request()->session()->invalidate();
        return redirect('/login');
    }
}
