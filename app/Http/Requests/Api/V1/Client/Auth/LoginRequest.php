<?php

namespace App\Http\Requests\Api\V1\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
