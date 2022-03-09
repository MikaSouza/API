<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Http\Resources\DepartmentResource;
use App\Services\ZohoDeskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;




/**
 * @group Tickets
 */
class TicketController extends Controller
{
    public function __construct()
    {
        $this->zohoDeskService = new ZohoDeskService();
    }
    /**
     * @authenticated
     */
    public function index()
    {
        $tickets = $this->zohoDeskService->getTickets();

        return $tickets;
    }

    /**
     * @authenticated
     */
    public function store(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'subject' => 'required|max:255',
                'description' => 'required|max:255',
                'departmentId' => 'required|int',
                'category' => 'required|max:255',
                'subCategory' => 'required|max:255',
                'classification' => 'required|max:255',
            ],
            [],
            []
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


        $ticket = $this->zohoDeskService->createTicket($validator->validated());


        if (!$ticket) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'error' => '',
                    'message' => 'Não foi possível criar o ticket, sistema indisponível ou usuário sem permissão.'
                ],
                422
            );
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $ticket,
                'message' => 'Ticket criado com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function show($id)
    {

        $ticket = $this->zohoDeskService->getTicket($id);

        return $ticket;
    }

    /**
     * @authenticated
     */
    public function threads($ticket_id, $thread_id)
    {

        $thread = $this->zohoDeskService->getThread($ticket_id, $thread_id);

        return $thread;
    }


    /**
     * @authenticated
     */
    public function classifications()
    {
        $classifications = $this->zohoDeskService->getClassifications();

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $classifications,
                'message' => 'Classificações listadas com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function categories($department_id)
    {
        $categories = $this->zohoDeskService->getCategories($department_id);

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $categories,
                'message' => 'Categorias listadas com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function subCategories($department_id, $category)
    {

        if (!$category) {
            return response()->json(
                [
                    'code' => 422,
                    'success' => false,
                    'error' => [
                        'category' => 'O campo categoria é obrigatório.'
                    ],
                    'message' => 'Confira os dados e tente novamente'
                ],
                422
            );
        }

        $subCategories = $this->zohoDeskService->getSubCategories($department_id, $category);

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $subCategories,
                'message' => 'Subcategorias listadas com sucesso'
            ],
            200
        );
    }


    /**
     * @authenticated
     */
    public function departments()
    {

        $departments = $this->zohoDeskService->getDepartments();

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => DepartmentResource::collection($departments),
                'message' => 'Departamentos listados com sucesso'
            ],
            200
        );
    }
    /**
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        //
    }



    /**
     * @authenticated
     *
     */
    public function getAttachment(Request $request)
    {
        //  $validator = Validator::make(
        //     $request->all(),
        //     [   
        //         'ticket_id' => 'required|integer',
        //         'attachment_id' => 'required|integer',
        //         'name' => 'required|max:255'
        //     ],
        //     [],
        //     []
        // );


        // if ($validator->fails()) {
        //     return response()->json(
        //         [
        //             'code' => 422,
        //             'success' => false,
        //             'error' => $validator->errors(),
        //             'message' => 'Confira os dados e tente novamente'
        //         ],
        //         422
        //     );
        // }


        $url = 'https://desk.zoho.com/api/v1/tickets/' . $request->ticket_id . '/attachments/' . $request->attachment_id . '/content?orgId=' . env('ZOHO_ORG_ID');

        $attachment = $this->zohoDeskService->downloadAttachment($url, $request->name, $request->ticket_id);
        return $attachment;


    }


    /**
     * @authenticated
     * 
     * @bodyParam file file 
     */
    public function attachments(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'ticket_id' => 'required|integer',
                'file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048'
            ],
            [],
            [
                'file' => 'arquivo'
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


        if ($request->file()) {
            $name = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $name, 'public');
        }



        $attachment = $this->zohoDeskService->createAttachment($validator->validated(), $name, $filePath);


        if (!$attachment) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'error' => '',
                    'message' => 'Não foi possível adicionar o arquivo ao ticket, sistema indisponível ou usuário sem permissão.'
                ],
                422
            );
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $attachment,
                'message' => 'Arquivo adicionado com sucesso'
            ],
            200
        );
    }



    /**
     * @authenticated
     */
    public function replies(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ticket_id' => 'required|integer',
                'content' => 'required|max:255'
            ],
            [],
            [
                'content' => 'conteúdo'
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



        $reply = $this->zohoDeskService->sendReply($validator->validated());


        return $reply;
    }


    /**
     * @authenticated
     */
    public function destroy($id)
    {
        //
    }
}
