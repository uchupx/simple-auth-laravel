<?php

namespace App\Http\Controllers;

use App\Plugin\Jwt;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('username', $request->input('username'))->get();
        $user  = null;

        foreach ($users as $usr) {
            if (Hash::check($request->input('password'), $usr->password)) {
                $user = $usr;
                break;
            }
        }

        if (!$user) {
            return response()->json(["message" => 'User not found'], 403);
        }

        $jwt = new Jwt(env('APP_KEY'));

        return $jwt->create($user);
    }
}
