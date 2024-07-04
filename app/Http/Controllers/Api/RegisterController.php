<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules;
class RegisterController extends Controller
{
    
   public function register(Request $request){
   
        $validateUser = validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required'],
        ]);
        if ($validateUser->fails()){
             return response()->json([
                "message"=>"validation error",
                "error"=> $validateUser->errors()
             ],401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            "data"=> $user,
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 200);
   }
   // login
    public function login(Request $request){
    
        $validateUser = validator::make($request->all(),[
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data'=>$user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
    // logout
    public function logout()
    {
        Auth::user()->Tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
// profile
    public function profile() {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'message' => "data of profile",
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => "User not authenticated",
                'data' => null,
            ], 401);
        }
    }
}
