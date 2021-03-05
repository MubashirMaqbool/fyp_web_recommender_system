<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'user_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);



    }
    public function login(Request $request){
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

    }
}
