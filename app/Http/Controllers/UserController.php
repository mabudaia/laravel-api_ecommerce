<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterUserRequest $Request){
       $user= User::create(
            [
            'name'=>$Request->name,
            'email'=>$Request->email,
            'password'=>Hash::make($Request->password),

            ]            
            );

        return response()->json(
            [
            'message'=>'user Registered successfully',
            'User'=>$user
            ],200);

    }
    public function login(LoginUserRequest $request){
        if(!Auth::attempt($request->only('email','password')))
        return response()->json([
            'message'=>'invalid email or password'
        ],401);
       $user= User::where('email',$request->email)->FirstorFail();//اذا كانوا متطبيقين رح ترجعوا اول اشي
        $token=$user->CreateToken('auth_Token')->plainTextToken;//رح يحول التوكن لنص
    
            return response()->json(
            [
            'message'=>'Lohin successfully',
            'User'=>$user,
            'Token'=>$token
            ],201);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
          return response()->json([
           'message' =>'Logout Sccessful',
           ],200); 
    }

    public function getUserProduct($id){
      $product=Product::findorFail($id)->Pproduct;
      return Response()->json($product,200);
    }

    public function getProfile(){
            $user = Auth::user(); // المستخدم الحالي من التوكن
    $profile = $user->profile;

        //$profile=User::find($id)->profile;
        return response()->json([ 
        'profile'=>$profile
],200);
    }
}
