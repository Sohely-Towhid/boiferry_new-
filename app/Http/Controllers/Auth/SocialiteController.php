<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Socialite;
use Str;

class SocialiteController extends Controller
{
    /**
     * Socialite Redirect
     * @param  [type] $provider [description]
     * @return [type]           [description]
     */
    public function redirect($provider)
    {
        $magic = request()->get('magic');
        if ($magic) {
            session(['magic' => $magic]);
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Socialite Callback
     * @param [type] $provider [description]
     */
    public function Callback($provider, Request $request)
    {

        if (!$request->has('code') || $request->has('denied')) {
            return redirect('/');
        }

        $social = Socialite::driver($provider)->stateless()->user();
        if ($social) {
            $email = $social->getEmail();
            if (!$email) {
                return redirect('/login')->with('error', 'Your facebook account does not have any email account.');
            }
            $user = User::whereEmail($email)->first();

            if (!$user) {
                $user = User::create(['name' => $social->getName(), 'email' => $email, 'mobile' => '', 'password' => bcrypt(Str::random(8))]);
            }
            if (session('magic')) {
                Cache::put(session('magic') . "_user", $user->id, 60 * 5);
                $request->session()->forget('magic');
                return view('auth.device');
            }
            Auth::login($user);
            return redirect('/my-account');
        }
    }
}
