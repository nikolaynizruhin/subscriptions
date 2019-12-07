<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerPaymentMethodController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->user()->addPaymentMethod($request->payment_method)->asStripePaymentMethod()->toArray();
    }
}
