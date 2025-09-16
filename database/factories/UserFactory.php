<?php

namespace Database\Factories;

use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;
use App\Enums\UserStatusEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(),
            'status' => fake()->randomElement([UserStatusEnum::ACTIVE, UserStatusEnum::DEACTIVE]),
            'role' => fake()->randomElement([UserRoleEnum::ADMIN, UserRoleEnum::USER]),
            'password' => static::$password ??= Hash::make('password'),
            'address' => fake()->address(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }

}
