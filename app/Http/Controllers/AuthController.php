<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users',
            'full_name' => 'required|max:120',
            'password' => 'required|max:20|min:6'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            $inputs = $validator->validated();

            $user = User::create($inputs);

            $data = [
                'status' => 200,
                'message' => 'Created a user',
                'data' => $user
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function login(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'email' => 'required|email|max:255|exists:users',
            'password' => 'required|max:20|min:6'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            $user = User::where('email', $request->email)->first();

            $is_valid_password = $user->check_password_is_valid($request->password);

            if ($is_valid_password) {

                $credentials = $user->credentials()->create();

                $credentials = ['user' => $user, 'access_token' => $credentials];
                $data = [
                    'status' => 200,
                    'message' => 'Authenticated successfully',
                    'data' => $credentials
                ];
            } else {
                $data = [
                    'status' => 401,
                    'message' => 'Invalid password'
                ];
            }
        }

        return response()->json($data, $data['status']);
    }

    public function logout(Request $request)
    {
        Credential::where('id', $request->get('credential_id'))->update(['is_active' => false]);

        $data = [
            'status' => 200,
            'message' => 'Successfully logged out'
        ];

        return response()->json($data, $data['status']);
    }
}
