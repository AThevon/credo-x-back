<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;


class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void
   {
      //
   }

   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
      Passport::enablePasswordGrant();
      // Optionnel : Configure l'expiration des tokens
      Passport::tokensExpireIn(now()->addDays(15));
      Passport::refreshTokensExpireIn(now()->addDays(30));
   }
}
