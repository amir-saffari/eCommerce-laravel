<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Models\User;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Client\User\UserResource;
use App\Http\Requests\Api\V1\Client\Auth\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'phone' => $data['phone'],
            'password' => $data['password'],
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'role' => UserRoleEnum::USER,
            'status' => UserStatusEnum::ACTIVE,
        ]);

        return new UserResource($user);

    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $data['phone'])->first();

        if (!$user)
        {
            return $this->error('User not found', 404);
        }

        if (!password_verify($data['password'], $user->password))
        {
            return $this->error('password is incorrect', 401);
        }

        $token = JWTAuth::fromUser($user);

        return $this->success('Login successful', 200, [
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->success('Logout successful', 200);
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        
        return $this->success('Token refreshed', 200, [
            'token' => $token,
        ]);
    }

}
