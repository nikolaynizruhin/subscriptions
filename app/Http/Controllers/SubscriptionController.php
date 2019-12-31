<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use App\Rules\PlanExists;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('payment')->only('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['plan' => ['required', 'string', 'max:255', new PlanExists]]);

        $paymentMethod = $request->user()->defaultPaymentMethod();

        $request->user()->newSubscription($request->plan)->create($paymentMethod->id);

        return new User($request->user()->fresh());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(['plan' => ['required', 'string', new PlanExists]]);

        $request->user()->subscription()->swap($request->plan);

        return new User($request->user());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->user()->subscription()->cancel();

        return new User($request->user());
    }
}
