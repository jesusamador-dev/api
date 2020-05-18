<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     *
     * Devuelve las credenciales de autorización
     * @return \Illuminate\Http\JsonResponse
     * Las contraseñas se encriptan antes de la comparación en la BD
     */

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        try {
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                return response()->json([
                    'succes' => true,
                    'token' => $token,
                    'user' => User::where('email', $credentials['email'])->get()->first()
                ], 200);
            } else {
                return response()->json([
                    'succes' => false,
                    'error' => 'credenciales incorrectas',
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'no se pudo creear el token'], 500);
        }
    }

    /**
     * Registra un nuevo usuario
     * @param Request
     * @return responseJSON */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * Devuele los datos del usuario autenticado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Cierra la sesión
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'ok']);
    }

    /**
     * Refresca el tiempo de vida del token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    /**
     * Devuelve la estructura del token
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600,
            'user' => auth()->user(),
        ]);
    }
}
