<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the payment methods.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->stripePaymentMethods();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laravel\Cashier\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->noContent();
    }
}
