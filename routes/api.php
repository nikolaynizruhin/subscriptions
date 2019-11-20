<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication
Route::post('login', 'Auth\LoginController@login')->name('login');

// Registration
Route::post('register', 'Auth\RegisterController@register')->name('register');

// Password Reset
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Password Confirmation
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm')->name('password.confirm');

// Account
Route::get('user', 'UserController@show')->name('user.show');
Route::put('user', 'UserController@update')->name('user.update');

Route::middleware('password.confirm')->get('settings', function (Request $request) {
    return $request->user();
})->name('settings');
