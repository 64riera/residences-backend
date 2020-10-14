<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    /**
     * Creates a new user
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'control_number' => 'required|numeric|unique:users,control_number',
            'area_id' => 'required|integer',
            'birthdate' => 'required|date',
            'is_active' => 'required|integer',
            'phone' => 'required|numeric',
            'visible_mail' => 'required|boolean',
            'visible_phone' => 'required|boolean',
            'user_type' => 'required|numeric|exists:type_users,id',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->control_number = $request->control_number;
        $user->area_id = $request->area_id;
        $user->birthdate = $request->birthdate;
        $user->is_active = $request->is_active;
        $user->phone = $request->phone;
        $user->visible_mail = $request->visible_mail;
        $user->visible_phone = $request->visible_phone;
        $user->user_type = $request->user_type;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
            
        if ($user->save()) {
            return response()->json([
                "code" => Config::get('constants.responses.SUCCESS_CODE'),
                "message" => Config::get('constants.responses.SUCCESS_MESSAGE'),
                "data" => $user
            ], Config::get('constants.responses.SUCCESS_CODE'));
        } else {
            return response()->json([
                "code" => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                "message" => Config::get('constants.responses.FAIL_MESSAGE'),
                "data" => []
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }
    }

    /**
     * Login authentication
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Logout user / Invalidate token
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => 'Successfully logged out',
            'data' => []
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Get current loged user information
     */
    public function user(Request $request)
    {
        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $request->user()
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
