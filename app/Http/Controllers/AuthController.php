<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'magic']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (Auth::once($request->all())) {
            return $this->createNewToken($user);
        } else {
            return response()->json(['error' => 'Invalid Credential!'], 401);
        }

        // if ($user) {
        //     if (Hash::check($request->password, $user->password)) {
        //         return $this->createNewToken($user);
        //     }
        // } else {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user            = auth()->user();
        $user->api_token = null;
        $user->save();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function magic(Request $request)
    {
        $user = cache($request->token . "_user");
        if ($user) {
            $user = User::findOrFail($user);
            return $this->createNewToken($user);
        }
        return response()->json(['error' => '**none**'], 406);
    }

}
