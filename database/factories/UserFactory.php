<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'trial_ends_at' => now()->addDays(config('subscription.trial_days')),
        'password_confirmed_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'api_token' => Str::random(60),
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'unverified', [
    'email_verified_at' => null,
]);

$factory->state(User::class, 'unconfirmed_password', [
    'password_confirmed_at' => null,
]);

$factory->state(User::class, 'expired_password_confirmation', [
    'password_confirmed_at' => now()->subSeconds(config('auth.password_timeout') + 1),
]);
