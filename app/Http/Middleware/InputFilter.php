<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Session;
use Str;

class InputFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->slug) {
            $request->merge(['slug' => Str::slug($request->slug)]);
        }

        if ($request->mobile) {
            $request->merge(['mobile' => preg_replace('/^(01[1-9]+[0-9]{7})/', '88$1', $request->mobile)]);
        }

        if (session('locale')) {
            App::setlocale(session('locale'));
        }

        return $next($request);
    }
}
