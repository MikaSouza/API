<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $company = $this->whenLoaded('company');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,           
            'financeiro' => $this->financeiro, 
            'investimentos' => $this->investimentos, 
            'monitoramento' => $this->monitoramento, 
            'tickets' => $this->tickets,
            'is_admin' => $this->is_admin,
            'company' => new CompanyResource($company),
        ];

    }
}
