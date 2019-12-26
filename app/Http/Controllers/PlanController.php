<?php

namespace App\Http\Controllers;

use App\Plan;
use Stripe\Plan as StripePlan;

class PlanController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plan::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Stripe\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(StripePlan $plan)
    {
        return $plan;
    }
}
