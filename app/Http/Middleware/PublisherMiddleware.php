<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class PublisherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      //dd(Auth::user()->role_id);

       if(Auth::user()==null){
         return redirect('/home');
       }
        elseif(Auth::user()->role_id == 2){
          abort(404);
        }

        elseif(Auth::user()->role_id == 1 || Auth::user()->role_id == 3){
          return $next($request);
        }
    }
}
