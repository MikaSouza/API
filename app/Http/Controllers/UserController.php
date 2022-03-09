<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\CompanyResource;

use App\Models\Company;

/**
 * @group Usuários
 */
class UserController extends Controller
{
    /**
     * @authenticated
     */
    public function index()
    {
        $users = User::with(['company']);

        //Observar que a collection por agora não virá paginada ->
        //'data' => UserResource::collection($users->paginate(50)),
        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => UserResource::collection($users->get()),
                'message' => 'Usuários listados com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'surname' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'financeiro' => 'required|boolean',
                'investimentos' => 'required|boolean',
                'monitoramento' => 'required|boolean',
                'tickets' => 'required|boolean',
                'company_id' => 'required|integer|min:1',
                'is_admin' => 'required|boolean'
            ],
            [],
            [
                'company_id' => 'empresa',
                'is_admin' => 'admin'
            ]
        );

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

               
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'financeiro' => $request->financeiro,
            'investimentos' => $request->investimentos,
            'monitoramento' => $request->monitoramento,
            'tickets' => $request->tickets,
            'password' => bcrypt($request->password),
            'company_id' => $request->company_id,
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => new UserResource($user),
                'message' => 'Usuário criado com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function show($id)
    {
        $user = User::with(['company'])->where('id', '=', $id)->first();
        if (is_null($user)) {
            return response()->json(
                [
                    'code' => 404,
                    'success' => false,
                    'data' => '',
                    'message' => 'Usuário não encontrado'
                ],
                404
            );
        }
        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => new UserResource($user),
                'message' => 'Usuário listado com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     * @bodyParam email string required E-mail do usuário.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'surname' => 'required|max:255',
                'email' => 'required|email|unique:users,email, ' . $user->id,
                'financeiro' => 'required|boolean',
                'investimentos' => 'required|boolean',
                'monitoramento' => 'required|boolean',
                'tickets' => 'required|boolean',
                'company_id' => 'required|integer|min:1',
                'is_admin' => 'required|boolean'
            ],
            [],
            [
                'company_id' => 'empresa',
                'is_admin' => 'admin'
            ]
        );

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

        //Verifica se empresa existe

        $company = Company::find($request->get('company_id'));

        if (!$company) {
            return response()->json(
                [
                    'code' => 422,
                    'success' => false,
                    'error' => '',
                    'message' => 'Empresa não existe'
                ],
                422
            );
        }


        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->financeiro = $request->get('financeiro');
        $user->investimentos = $request->get('investimentos');
        $user->monitoramento = $request->get('monitoramento');
        $user->tickets = $request->get('tickets');
        $user->company_id = $request->get('company_id');
        $user->is_admin = $request->get('is_admin');

        $user->save();

        $user = User::with(['company'])->where('id', '=', $user->id)->first();

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => new UserResource($user),
                'message' => 'Usuário alterado com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $user,
                'message' => 'Usuário excluído com sucesso'
            ],
            200
        );
        
    }
}
