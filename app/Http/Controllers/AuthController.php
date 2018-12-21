<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginUser;

class AuthController extends Controller
{

    /**
     * register a new user
     *
     * @param string name
     * @param string email
     * @param string password
     * @param string password_confirmation
     * @return json
     */
    public function register(CreateUser $request)
    {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json(['message' => 'User successfully created!']);
    }

    /**
     * login user
     *
     * @param Illuminate\Http\Request $request
     * @return json token
     */
    public function login(LoginUser $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'UnAuthorized'], 401);
        }

        $user = $request->user();

        $createdToken = $user->createToken('Access Token');
        $accessToken = $createdToken->accessToken;
        $token = $createdToken->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($createdToken->token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * logout user
     *
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'User logged out'
        ]);
    }

    /**
     * get user
     *
     * @return json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
