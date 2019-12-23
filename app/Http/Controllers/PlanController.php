<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Stripe\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = config('subscription.product');

        $plans = Plan::all(['product' => $product], Cashier::stripeOptions());

        return $plans->data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Stripe\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return $plan;
    }
}
