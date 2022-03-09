<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this['status']) {
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

        return [
            'id' => $this['id'],
            'ticketNumber' => $this['ticketNumber'],
            'layoutId' => $this['layoutId'],
            'email' => $this['email'],
            'phone' => $this['phone'],
            'subject' => $this['subject'],
            'status' =>  $status,
            'statusType' => $this['statusType'],
            'createdTime' => date("d/m/Y H:i", strtotime($this['createdTime'])),
            'category' => $this['category'],
            'language' => $this['language'],
            'subCategory' => $this['subCategory'],
            'priority' => $this['priority'],
            'channel' => $this['channel'],
        ];
    }
}
