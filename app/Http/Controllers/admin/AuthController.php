<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ],400);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            if($user->role == 'admin'){
                $token = $user->createToken('token')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'message' => 'Login successful',
                    'token' => $token,
                    'id'=>$user->id,
                    'name'=>$user->name,
                ],200);
            }
            else{
                return response()->json([
                    'status' => 403,
                    'message' => 'Unauthorized access admin panel'
                ],403);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid email or password'
            ],401);
        }
    }
}
