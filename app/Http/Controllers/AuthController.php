<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }
    public function doLogin(Request $request){
        $email=$request->get('email');
        $password=$request->get('password');
        $v=Validator::make($request->all(),[
            'email'=>['required','email'],
            'password'=>['required']
        ]);
        if ($v->fails()){
            return back()->withErrors([
                'msg'=>'Format Error'
            ])->withInput();
        }
        $user = User::where('email',$email)->get();
        if($user&&$user[0]->is_admin!==1){
            if(Auth::attempt([
                'email'=>$email,
                'password'=>$password,
            ])){
                $request->session()->regenerate();
                return redirect()->intended(route('index'));
            }else {
                return back()->withErrors([
                    'msg'=>'The username or password is incorrect'
                ])->withInput();
            }
        }else {
            return back()->withErrors([
                'msg'=>'Account is not exist'
            ])->withInput();
        }

    }
    public function logout(){
        Auth::logout();
        return redirect(route('index'));
    }
}
