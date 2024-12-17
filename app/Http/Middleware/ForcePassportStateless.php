<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePassportStateless
{
   public function handle($request, Closure $next)
   {
      // Force le guard API
      Auth::setDefaultDriver('api');
      return $next($request);
   }
}
