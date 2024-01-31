<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'username' => 'required',
        //     'password' => 'required',
        // ]);
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Users::where('username', $request->username)->first();

        if (! $user || ($request->password != $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // return $user->createToken('user login')->plainTextToken;
        $token = $user->createToken('user login')->plainTextToken;
        return response()->json(['Username' => $user->username, 'Token' => $token], 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
