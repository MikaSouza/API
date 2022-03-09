<?php

namespace App\Services;

use App\Http\Resources\TicketResource;
use Marshmallow\ZohoDesk\Facades\Contact;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Facades\Ticket;
use App\Services\AvatarService;
use Illuminate\Support\Facades\Storage;






class ZohoDeskService
{
    public static $zohoTokenModel = \Marshmallow\ZohoDesk\Models\ZohoToken::class;

    public function __construct()
    {
        $this->avatarService = new AvatarService();
    }


    protected function getAccessToken()
    {
        $token = self::$zohoTokenModel::firstOrFail();
        if ($token->isExpired()) {
            $token->refresh();
        }

        return $token->access_token;
    }


    public function getTickets()
    {

        $contact = Contact::findOrCreate(auth()->user()->email, [
            'lastName' => auth()->user()->surname,
            'firstName' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);

        if (!$contact) {
            return response()->json(
                [
                    'code' => 401,
                    'success' => false,
                    'data' => [],
                    'message' => 'Usuário sem permissão para listar tickets'
                ],
                401
            );
        }

        $tickets = ZohoDesk::get('/contacts/' . $contact->id . '/tickets');
        $tickets = TicketResource::collection($tickets);

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => TicketResource::collection($tickets),
                'message' => 'Tickets listados com sucesso'
            ],
            200
        );
    }

    public function getContact($contact_id)
    {

        $contact = ZohoDesk::get('/contacts/' . $contact_id);

        return $contact;
    }

    public function getTicket($ticketNumber)
    {

        $ticket = ZohoDesk::get('/tickets/' . $ticketNumber);


        $contact = $this->getContact($ticket->contactId);



        if ($contact->email !== auth()->user()->email) {
            return response()->json(
                [
                    'code' => 401,
                    'success' => false,
                    'data' => [],
                    'message' => 'Usuário sem permissão para visualizar este ticket'
                ],
                401
            );
        }

        if (!$ticket) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'data' => [],
                    'message' => 'Ticket não encontrado'
                ],
                500
            );
        }


        switch ($ticket->status) {
            case 'Open':
                $status = 'Aberto';
                break;
            case 'Pending':
                $status = 'Pendente';
                break;
            case 'Solved':
                $status = 'Resolvido';
                break;
            case 'Closed':
                $status = 'Fechado';
                break;
            case 'Em Analise':
                $status = 'Em Análise';
                break;
            default:
                $status = $this['status'] ?? 'Não definido';
                break;
        }


        $ticketClean = [
            'id' => $ticket->id,
            'ticketNumber' => $ticket->ticketNumber,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'layoutId' => $ticket->layoutId,
            'email' => $ticket->email,
            'phone' => $ticket->phone,
            'status' =>  $status,
            'statusType' => $ticket->statusType,
            'createdTime' => date("d/m/Y H:i", strtotime($ticket->createdTime)),
            'category' => $ticket->category,
            'language' => $ticket->language,
            'subCategory' => $ticket->subCategory,
            'priority' => $ticket->priority,
            'channel' => $ticket->channel,
            'threads' => $this->getTicketThreads($ticket->id),
            'attachments' => $this->getTicketAttachments($ticket->id),
            'threadCount' => $ticket->threadCount
        ];

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $ticketClean,
                'message' => 'Ticket encontrado com sucesso'
            ],
            200
        );
    }


    public function getThread($ticket_id,  $thread_id)
    {

        $thread = ZohoDesk::get('/tickets/' . $ticket_id . '/threads/' . $thread_id);

        if (!$thread) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'data' => [],
                    'message' => 'Não foi possível listar a resposta'
                ],
                500
            );
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $thread->content,
                'message' => 'Resposta listada com sucesso'
            ],
            200
        );
    }





    public function getDepartments()
    {

        $departments = ZohoDesk::get('/departments?isEnabled=true');


        $departmentsList = [];
        foreach ($departments as $k => $d) {
            if ($d['id'] == 486535000008323371) {
                $departmentsList[$k]['id'] = $d['id'];
                $departmentsList[$k]['name'] = $d['name'];
            }
        }

        return $departmentsList;
    }
    public function getClassifications()
    {

        $classifications = [
            'Incidente',
            'Solicitação'
        ];

        return $classifications;
    }


    public function getCategories($department_id)
    {
        $categories = [];

        //zTestes
        if ($department_id == 486535000008323371) {
            $categories = [
                'Acesso',
                'Savebox',
                'Banco de Dados',
                'E-mail',
                'Firewall',
                'Servidor',
                'Licenciamento',
                'Monitoramento',
                'Sistema'
            ];
        }

        return $categories;
    }

    public function getSubCategories($department_id, $category)
    {

        $subCategories = [];


        //zTestes
        if ($department_id == 486535000008323371) {
            switch ($category) {
                case 'Acesso':
                    $subCategories = [
                        'Painel do Cliente CCM',
                        'Sistema de Chamados CCM'
                    ];
                    break;

                case 'Savebox':
                    $subCategories = [
                        'Backup ou restore'
                    ];
                    break;

                case 'Banco de Dados':
                    $subCategories = [
                        'Backup ou restore',
                        'Indisponibilidade ou conectividade',
                        'Desempenho',
                        'Usuários e acessos',
                        'Preventia',
                    ];
                    break;

                case 'E-mail':
                    $subCategories = [
                        'BlackList / Whitelist / Spam',
                        'Envio / Recebimento de E-mail',
                        'Backup ou restore',
                        'Indisponibilidade ou conectividade',
                        'Desempenho',
                        'Usuários e acessos'
                    ];
                    break;

                case 'Firewall':
                    $subCategories = [
                        'VPN',
                        'Conectividade com Datacenter',
                        'Conectividade com Firewall',
                        'Dominio / DNS / Coudflare',
                        'Liberação de Porta / IP externo'
                    ];
                    break;

                case 'Servidor':
                    $subCategories = [
                        'Backup ou restore',
                        'Indisponibilidade ou conectividade',
                        'Desempenho',
                        'Usuários e acessos'
                    ];
                    break;

                case 'Licenciamento':
                    $subCategories = [
                        'Gerenciamento de Licenças'
                    ];
                    break;

                case 'Monitoramento':
                    $subCategories = [
                        'Relatório'
                    ];
                    break;

                case 'Sistema':
                    $subCategories = [
                        'Linx',
                        'Sankhya'
                    ];
                    break;

                default:
                    $subCategories = [];
                    break;
            }
        }

        return $subCategories;
    }

    public function getTicketConversations($ticketId)
    {

        $conversations = ZohoDesk::get('/tickets/' . $ticketId . '/conversations');

        $conversationList = [];
        foreach ($conversations as $k => $c) {
            $conversationList[$k]['id'] = $c['id'];
            $conversationList[$k]['summary'] = $c['summary'];
            $conversationList[$k]['author'] = $c['author'];
            $conversationList[$k]['createdTime'] = date("d/m/Y H:i", strtotime($c['createdTime']));
        }

        return $conversationList;
    }

    public function getTicketThread($threadId, $ticketId)
    {
        $thread = ZohoDesk::get('/tickets/' . $ticketId . '/threads/' . $threadId);


        return $thread;
    }

    public function getTicketThreads($ticketId)
    {
        $threads = ZohoDesk::get('/tickets/' . $ticketId . '/threads');

        $threadList = [];

        $name = auth()->user()->name;
        $surname = auth()->user()->surname;
        $fullName = $name . ' ' . $surname;
        $email = auth()->user()->email;

        foreach ($threads as $k => $t) {
            $threadList[$k]['id'] = $t['id'];
            $threadList[$k]['canReply'] = $t['canReply'];
            $threadList[$k]['author']['name'] = $fullName;
            $threadList[$k]['author']['email'] = ($t['author']['email'] == 'services@ccmtecnologia.com.br' || $t['author']['name'] === null) ? $email : $t['author']['email'];
            $threadList[$k]['author']['photoURL'] = $this->avatarService->getAvatar();
            $threadList[$k]['author']['type'] = ($t['author']['name'] == 'Service Account') ? 'END_USER' : $t['author']['type'];
            // $threadList[$k]['author']['firstName'] = ($t['author']['firstName'] == 'Service') ? $name : $t['author']['firstName'];
            // $threadList[$k]['author']['lastName'] = ($t['author']['lastName'] == 'Account') ? $surname : $t['author']['lastName'];
            $threadList[$k]['content'] = $t['summary'];
            $threadList[$k]['createdTime'] = date("d/m/Y H:i", strtotime($t['createdTime']));
            $threadList[$k]['direction'] = $t['direction'];
            $threadList[$k]['channel'] = $t['channel'];
        }

        return $threadList;
    }

    //to-do revisar attachment
    public function getTicketAttachments($ticketId)
    {

        $attachments = ZohoDesk::get('/tickets/' . $ticketId . '/attachments');

        $attachmentList = [];
        foreach ($attachments as $k => $c) {
            $attachmentList[$k]['id'] = $c['id'];
            $attachmentList[$k]['name'] = $c['name'];
            // $attachmentList[$k]['author'] = $c['creatorId'];
            $attachmentList[$k]['file'] = $c['href'] . '?orgId=' . env('ZOHO_ORG_ID');
            $attachmentList[$k]['createdTime'] = date("d/m/Y H:i", strtotime($c['createdTime']));
        }

        return $attachmentList;
    }


    public function downloadAttachment($url, $name, $ticket_id)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $headers = array(
            "orgId: 710419230",
            "Authorization: Zoho-oauthtoken " . $this->getAccessToken()
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        $save = Storage::put('public/tickets/attachments/' . $ticket_id . '/' . $name, $result);

        $download = url('/storage/tickets/attachments/' . $ticket_id . '/' . $name);

        if (!$save) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'error' => '',
                    'message' => 'Não foi possível encontrar o arquivo.'
                ],
                422
            );
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $download,
                'message' => 'Arquivo encontrado com sucesso'
            ],
            200
        );
    }

 

    public function createAttachment($data, $name, $filePath)
    {
        $relative_path = 'app/public/' . $filePath;

        $ticket_id = $data['ticket_id'];
        $attachment = Ticket::attachment($ticket_id, $relative_path);


        return $attachment;
    }

    public function sendReply($data)
    {
        $ticket_id = $data['ticket_id'];
        $content = $data['content'] . ' Mensagem enviada por:' . auth()->user()->email . ' | ' . auth()->user()->name . ' ' . auth()->user()->surname . ' | ' . auth()->user()->company->name;

        $reply = ZohoDesk::post('/tickets/' . $ticket_id . '/sendReply', array_merge([
            "channel" => "EMAIL",
            "to" => "zteste@ccmint.zohodesk.com",
            "fromEmailAddress" => 'zteste@ccmint.zohodesk.com',
            "contentType" => "html",
            "content" => $content,
            "isForward" => "true"
        ]));

        if (!$reply) {
            return response()->json(
                [
                    'code' => 500,
                    'success' => false,
                    'data' => [],
                    'message' => 'A resposta não pôde ser enviada'
                ],
                500
            );
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => [],
                'message' => 'Resposta enviada com sucesso'
            ],
            200
        );
    }

    public function createComment($data)
    {
        $comment = Ticket::comment($data['ticket_id'], $data['message'], $public = true);

        return $comment;
    }

    public function createTicket($data)
    {

        $contact = Contact::findOrCreate(auth()->user()->email, [
            'lastName' => auth()->user()->surname,
            'firstName' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);

        if (!$contact) {
            return false;
        }

        $ticket = ZohoDesk::post('/tickets', array_merge([
            'departmentId' => $data['category'],
            'contactId' => $contact->id,
            'email' => $contact->email,
            'channel' => config('zohodesk.default_channel'),
            'classification' => config('zohodesk.default_classification'),
            'language' => config('zohodesk.default_language'),
            'category' => $data['category'],
            'subCategory' => $data['subCategory']
        ], $data));



        if (!$ticket) {
            return false;
        }

        switch ($ticket['status']) {
            case 'Open':
                $status = 'Aberto';
                break;
            case 'Pending':
                $status = 'Pendente';
                break;
            case 'Solved':
                $status = 'Resolvido';
                break;
            case 'Closed':
                $status = 'Fechado';
                break;
            case 'Em Analise':
                $status = 'Em Análise';
                break;
                $status = $ticket['status'] ?? 'Não definido';
        }

        $ticketClean = [
            'id' => $ticket['id'],
            'subject' => $ticket['subject'],
            'status' => $status,
            'category' => $ticket['category'],
            'subCategory' => $ticket['subCategory'],
            'classification' => $ticket['classification'],
            'createdTime' =>  date("d/m/Y H:i", strtotime($ticket['createdTime']))
        ];
        return $ticketClean;
    }
}
