<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                    'error' => 'El email o la contraseña son incorrectos, intente nuevamente.',
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
    }

    /**
     * Registra un nuevo usuario
     * @param Request
     * @return responseJSON
     *
     * */

    public function register(RegisterUserRequest $request)
    {
        $password = Hash::make($request->password);
        $parametros = array(
            $request->name,
            $password,
            $request->email,
            $request->role
        );
        $result = DB::select("call sp_CreateUser(?, ?, ?, ?)", $parametros);
        if ($result[0] = 'ok') {
            return response()->json(['success' => true, 'message' => 'Te registraste correctamente'], 200);
        } else {
            return response()->json($result, 400);
        }
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
}
