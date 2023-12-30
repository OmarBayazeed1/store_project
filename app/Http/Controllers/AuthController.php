<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'username'=>['required'],
            'mobile'=>['required','unique:users,mobile'],
            'password'=>['required']
        ],[
            'mobile.unique'=>"Mobile is not unique"
        ]);

       $user=User::query()->create([
           'username'=>$request['username'],
           'mobile'=>$request['mobile'],
           'password'=>$request['password']
       ]);
      $token= $user->createToken("API TOKEN")->plainTextToken;
      $data=[];
      $data['user']=$user;
      $data['token']=$token;

        return response()->json([
           'status'=>1,
           'data'=>$data,
           'message'=>'User Registered Successfully'
       ]);
    }
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'mobile'=>['required','digits:10','exists:users,mobile'],
            'password'=>['required']
        ],[
            'mobile.exists'=>"Mobile does not exist in db"
        ]);
        if(!Auth::attempt($request->only(['mobile','password']))){
            $message="Mobile & Password do not match our records.";
            return response()->json([
                'status'=>0,
                'data'=>[],
                'message'=>$message
            ],500);
        }
        $user=User::query()->where('mobile','=',$request['mobile'])->first();
        $token= $user->createToken("API TOKEN")->plainTextToken;
        $data=[];
        $data['user']=$user;
        $data['token']=$token;

        return response()->json([
            'status'=>1,
            'data'=>$data,
            'message'=>'User Logged in Successfully'
        ]);
    }
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'status'=>1,
            'data'=>[],
            'message'=>'User Logged out Successfully'
        ]);
    }

}
