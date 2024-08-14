<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function login(){
        return view('user.login');
    }
    public function postLogin(Request $request){
        $request->validate([
            'email' => 'required', 'email',
            'password' => 'required|string',
        ]);
        //dd($request->all());
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $request->session()->regenerate();
            return redirect()->route('home');
        }else{
            return redirect()->back()->with('error','Đăng nhập không thành công');
        }
    }
    public function register(){
        return view('user.register');
    }
    public function postRegister(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',  // Tên là bắt buộc, phải là chuỗi, tối đa 255 ký tự
            'email' => 'required|email|unique:users,email', // Email là bắt buộc, phải hợp lệ, tối đa 255 ký tự, duy nhất trong bảng users
            'password' => 'required|string|confirmed', // Mật khẩu là bắt buộc, phải là chuỗi, tối thiểu 8 ký tự, và phải khớp với trường password_confirmation
        ]);
        $user = User::query()->create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
        ]);  
        if($user){
            return redirect()->back()->with('success','Đăng ký tài khoản thành công');
        }else{
            return redirect()->back()->with('error','Đăng ký tài khoản thất bại');
        }
    }
    public function sigOut(){
        Auth::logout();
        request()->session()->invalidate();
        return redirect()->route('user.login');
    }
}
