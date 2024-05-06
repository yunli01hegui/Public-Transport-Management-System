<?php

namespace App\Http\Controllers;

use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request){
        $v=Validator::make($request->all(),[
            'email'=>['required','email'],
            'password'=>['required']
        ]);
        if ($v->fails()){
            return response()->json([
                'msg'=>'Format Error'
            ],422);
        }
        $email=$request->get('email');
        $password=$request->get('password');
        $user=\App\Models\User::where('email',$email)->first();
        if ($user->is_admin){
            if(Auth::attempt([
                'email'=>$email,
                'password'=>$password,
            ],true)){
                return response()->json([
                    'msg'=>'Login successfully',
                    'data'=>[
                        'id'=>Auth::user()->id,
                        'name'=>Auth::user()->name,
                        'remember_token'=>Auth::user()->remember_token
                    ]
                ],200);
            }else{
                return response()->json(['msg'=>'Wrong email or password'],401);
            }
        }else{
            return response()->json(['msg'=>'Forbidden'],403);
        }
    }
    public function logout(Request $request){
        $token=$request->get('remember_token');
        if (!$token){
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
        $user=\App\Models\User::where('remember_token',$token)->first();
        if ($user){
            $user->remember_token=null;
            $user->save();
            return response()->json(['msg'=>'Logout successfully'],200);
        }else{
            return response()->json([
                'msg'=>'Invalid token'
            ],401);
        }
    }
}
