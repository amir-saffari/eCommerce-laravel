<?php

namespace App\Http\Resources\Api\V1\Client\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [

            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'phone' => $this->whenHas('phone'),
            'role' => $this->whenHas('role'),
            'status' => $this->whenHas('status'),
            'address' => $this->whenHas('address'),
            'created_at' => $this->whenHas('created_at'),
            'updated_at' => $this->whenHas('updated_at'),
        ];
    }
}
