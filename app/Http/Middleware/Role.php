<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Role
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = '')
    {
        $roles = explode("|", $roles);
        $user  = Auth::user();
        $role  = strtolower($user->role);

        if ($user->status == 2) {
            Auth::logout();
            return redirect('login')->with('error', 'Account suspended for policy violations.');
        } elseif ($user->status == 3) {
            Auth::logout();
            return redirect('login')->with('error', 'Account suspended for reseller policy violations.');
        }

        if (in_array($role, $roles)) {
            return $next($request);
        }

        if (Auth::check()) {
            return abort(403);
        }
        return route('login');

        /*if ($this->auth->guest()) {
        return redirect()->guest('login')->with(array('login_required' => trans('auth.login_required')));
        } else if (in_array($this->auth->User()->role, ['normal', 'donor'])) {
        return redirect('/');
        }*/
        // return $next($request);
    }

}
