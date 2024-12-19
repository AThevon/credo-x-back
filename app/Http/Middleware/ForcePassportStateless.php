<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;


class ForcePassportStateless
{
   public function handle($request, Closure $next)
   {
      // Force le guard API
      Auth::setDefaultDriver('api');
      Passport::withoutCookieSerialization();
      return $next($request);
   }
}
