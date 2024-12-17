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
use Laravel\Passport\PersonalAccessTokenResult;


class AuthenticatedSessionController extends Controller
{
   /**
    * Handle an incoming authentication request.
    */
   public function store(LoginRequest $request): JsonResponse
   {
      // Récupérer l'utilisateur par email
      $user = User::where('email', $request->email)->first();

      // Vérifier les informations d'identification
      if (!$user || !Hash::check($request->password, $user->password)) {
         return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
      }

      // Générer un token Passport
      $tokenResult = $user->createToken('AppToken');

      // Retourner le token et les infos utilisateur
      return response()->json([
         'user' => $user,
         'token' => $tokenResult->accessToken,
         'token_type' => 'Bearer',
         'expires_at' => $tokenResult->token->expires_at,
      ], 200);
   }

   /**
    * Destroy an authenticated session.
    */
   public function destroy(Request $request): JsonResponse
   {
      // Récupérer le token actuel
      $request->user()->token()->revoke();

      return response()->json([
         'message' => 'Logged out successfully. Token revoked.',
      ], 200);
   }
}
