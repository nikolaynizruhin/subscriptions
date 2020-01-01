<?php

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

// Email Verification
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Account
Route::get('user', 'UserController@index')->name('user.index');
Route::put('user', 'UserController@update')->name('user.update');

// Password Reset
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

// Password Confirmation
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm')->name('password.confirm');

// Password Update
Route::put('password', 'PasswordController@update')->name('password.update');

// Setup Intents
Route::post('setup-intents', 'SetupIntentController@store')->name('setup-intents.store');

// Customer
Route::get('customer', 'CustomerController@index')->name('customer.index');

// Payment Methods
Route::apiResource('payment-methods', 'PaymentMethodController')->only(['index', 'destroy']);
Route::put('default-payment-method', 'DefaultPaymentMethodController@update')->name('default-payment-method.update');
Route::post('customer-payment-method', 'CustomerPaymentMethodController@store')->name('customer-payment-method.store');

// Plans
Route::apiResource('plans', 'PlanController')->only(['index', 'show']);

// Subscriptions
Route::post('subscription', 'SubscriptionController@store')->name('subscription.store');
Route::post('resume-subscription', 'ResumeSubscriptionController@store')->name('resume-subscription.store');
Route::put('subscription', 'SubscriptionController@update')->name('subscription.update');
Route::delete('subscription', 'SubscriptionController@destroy')->name('subscription.destroy');

// Invoices
Route::apiResource('invoices', 'InvoiceController')->only(['index']);
