<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Closure;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            //Obtiene el token que viene en la solicitud
            $token = JWTAuth::parseToken();
            //Intenta autenticar al usuario
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            //Excepción por si el token ya expiró
            return $this->unauthorized('Tu token a expirado. Por favor vuelve a iniciar sesión.');
        } catch (TokenInvalidException $e) {
            //Excepción por si el token es invalido
            return $this->unauthorized('Tu token es invalido. Por favor vuelve a iniciar sesión.');
        } catch (JWTException $e) {
            //Excepción por si no viene token en la solicitud
            return $this->unauthorized('Por favor incluye un token de autorización en tu solicitud');
        }

        //Si el usuario se autenticó correctamente y pertenece al role correcto se enviará la solicitud
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return $this->unauthorized();
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ? $message : 'No está autorizado para realizar ésta solicitud.',
            'success' => false
        ], 401);
    }
}
