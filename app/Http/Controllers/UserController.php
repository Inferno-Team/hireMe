<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' =>  'required|email',
                'password' =>  'required'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'status_code' => 400, 'message' => 'Bad Request',
                'errors' => $errors
            ]);
        }
       
       
        $user = User::where('email', $request->email)->first();
        if (!isset($user)) {
            return response()->json(['status_code' => 400, 'message' => 'User not found'], 200);
        }
        
        if (!Hash::check($request->password, $user->password))
            return response()->json(['status_code' => 400, 'message' => 'Do not match our records!!'], 200);

        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json(['status_code' => 200, 'token' => $tokenResult, 'message' => 'good', 'type' => $user->type], 200);
    }
    public function signup(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone,
            'type' => $request->type
        ]);
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'code' => '200',
            'message' => "good",
            'token' => $tokenResult,
            'type' => $user->type
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'msg' => 'token deleted successfully'
        ], 200);
    }

    function user(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user
        ], 200);
    }
}
