<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use Illuminate\Http\Request;

class ResumeSubscriptionController extends Controller
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
        $request->user()
            ->subscription(config('subscription.product'))
            ->resume();

        return new User($request->user());
    }
}
