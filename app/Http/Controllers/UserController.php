<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    private $rules = [
        'username' => 'required|unique:users',
        'phone'    => 'required|unique:users',
        'email'    => 'required|unique:users',
        'password' => 'required',
    ];

    private $messages = [
        'username.required' => 'username field is required',
        'phone.required'    => 'phone field is required',
        'email.required'    => 'email field is required',
        'password.required' => 'password field is required',
    ];

    public function index()
    {
        $user = User::paginate(10);

        return $user;
    }

    public function create(Request $req)
    {
        $validate = Validator::make($req->all(), $this->rules, $this->messages);

        if($validate->fails()) {
            return response()->json($validate->errors(), 403);
        }

        $user           = new User;
        $user->username = $req->input('username');
        $user->phone    = $req->input('phone');
        $user->email    = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->saveOrFail();

        return $user->id;
    }

    public function edit(Request $req, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(["message" => 'User not found'], 403);
        }

        if ($req->input('phone')) {
            $user->phone = $req->input('phone');
        }
        if ($req->input('email')) {
            $user->email = $req->input('email');
        }
        if ($req->input('password')) {
            $user->password = Hash::make($req->input('password'));
        }

        $user->saveOrFail();

        return $user->id;
    }

}
