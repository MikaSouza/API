<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;


/**
 * @group Recuperação de senha
 */
class PasswordController extends Controller
{


    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
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

        $user = User::where('email', '=', $request->email)->get();

        if(!count($user) > 0){
            $status = '';
            return response()->json(
                [
                    'code' => 200,
                    'success' => true,
                    'data' => '',
                    'message' => 'Se você for um usuário CCM Cockpit, enviaremos seu link de redefinição de senha por e-mail!'
                ],
                200
            );
        }


        

        $status = Password::sendResetLink(
            $request->only('email')
        );




        if ($status == Password::RESET_THROTTLED) {
            return response()->json(
                [
                    'code' => 333,
                    'success' => false,
                    'data' => '',
                    'message' => __($status)
                ],
                333
            );
        }

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(
                [
                    'code' => 200,
                    'success' => true,
                    'data' => '',
                    'message' => __($status)
                ],
                200
            );
        }
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', RulesPassword::defaults()],
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

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(
                [
                    'code' => 200,
                    'success' => true,
                    'data' => '',
                    'message' => 'Senha alterada com sucesso'
                ],
                200
            );
        }

        return response()->json(
            [
                'code' => 500,
                'success' => false,
                'data' => '',
                'message' => 'Não foi possível alterar a senha'
            ],
            500
        );
    }
}
