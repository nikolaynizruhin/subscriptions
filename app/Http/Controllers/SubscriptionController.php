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

        $product = config('subscription.product');

        $request->user()
            ->newSubscription($product, $request->plan)
            ->create($paymentMethod->id);

        $request->user()->endTrial();

        return new User($request->user());
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

        $request->user()
            ->subscription(config('subscription.product'))
            ->swap($request->plan);

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
        $request->user()->subscription(config('subscription.product'))->cancel();

        return new User($request->user());
    }
}
