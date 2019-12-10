<?php

namespace App\Http\Controllers;

use App\Rules\PaymentMethodExists;
use Illuminate\Http\Request;

class DefaultPaymentMethodController extends Controller
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
     * Update user default payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'payment_method' => [
                'required', 'string', 'max:255', new PaymentMethodExists($request->user()),
            ],
        ]);

        $request->user()->updateDefaultPaymentMethod($request->payment_method);

        return $request->user()->asStripeCustomer()->toArray();
    }
}
