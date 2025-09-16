<?php

namespace App\Http\Requests\Api\V1\Client\Auth;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string|unique:users,phone',
            'name' => 'required|string',
            'address' => 'nullable|string',
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password',
        ];
    }
}
