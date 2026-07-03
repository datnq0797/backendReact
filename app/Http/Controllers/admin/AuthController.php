<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
class AuthController extends Controller
{
    public function authenticate(Request $request)
    {

        try {

            // Giữ nguyên toàn bộ code hiện tại
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ],
                [
                    'email.required' => 'Vui lòng nhập email.',
                    'email.email' => 'Email không đúng định dạng.',
                    'password.required' => 'Vui lòng nhập mật khẩu.',
                ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors(),
                ], 400);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                if ($user->role == 'admin') {
                    $token = $user->createToken('token')->plainTextToken;

                    return response()->json([
                        'status' => 200,
                        'message' => 'Login successful',
                        'token' => $token,
                        'id' => $user->id,
                        'name' => $user->name,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 403,
                        'message' => 'Unauthorized access admin panel',
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid email or password',
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }

    }
}
