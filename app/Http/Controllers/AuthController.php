<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\CompanyResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;





/**
 * @group Autenticação
 */
class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'code' => 422,
                    'success' => false,
                    'error' => $validator->errors(),
                    'message' => 'Confira os dados e tente novamente'
                ],
                422
            );
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                [
                    'code' => 301,
                    'success' => false,
                    'error' => 'E-mail e/ou senha incorretos.',
                    'message' => 'Confira os dados e tente novamente'
                ],
                301
            );
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => new UserResource($user),
            'company'  => new CompanyResource(Auth::user()->company),
            'access_token' => $user->createToken($request->email)->plainTextToken,
            'token_type' => 'Bearer',
        ];

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $data,
                'message' => 'Login realizado com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => [],
                'message' => 'Logout realizado com sucesso'
            ],
            200
        );
    }
}
