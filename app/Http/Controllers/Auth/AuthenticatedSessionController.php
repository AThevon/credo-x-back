<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
   /**
    * Handle an incoming authentication request.
    */
   public function store(LoginRequest $request): JsonResponse
   {
      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
         return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
      }

      // Connecter l'utilisateur via Laravel Auth (session)
      Auth::login($user);

      return response()->json(['user' => $user], 200);
   }

   /**
    * Destroy an authenticated session.
    */
   public function destroy(Request $request): JsonResponse
   {
      // Déconnecter l'utilisateur (session)
      Auth::logout();

      // Invalider la session
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      // Supprimer le cookie CSRF
      $cookie = cookie()->forget('XSRF-TOKEN');

      // Retourner une réponse JSON sans redirection
      return response()->json(['message' => 'Logged out successfully'], 200)->withCookie($cookie);
   }
}
