<?php

namespace PixelAdmin\Admin\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class CheckIfMainAdmin
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
		//dd(Auth::user());
		
        if(Auth::check())
        {	
			
            if(Auth::user()->user_role_id  == 1)
            {
                return $next($request);
            }
            else
            {
                return redirect('/login');
            }

        }
        else
        {
            return redirect('/login');
        }


    }
}
