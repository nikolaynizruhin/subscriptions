<?php

namespace App\Console\Commands;

use App\Plan;
use Illuminate\Console\Command;
use Laravel\Cashier\Cashier;
use Stripe\Plan as StripePlan;
use Stripe\Product;

class SetupSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new stripe product with plans';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $product = Product::create([
            'name' => config('app.name'),
        ], Cashier::stripeOptions());

        $plans = collect(config('subscription.plans'))->map(function ($plan) use ($product) {
            return Plan::create($plan + [
                'currency' => config('cashier.currency'),
                'product' => $product->id,
            ]);
        })->map(function (StripePlan $plan, $key) {
            return collect([
                '#' => $key + 1,
                'nickname' => $plan->nickname,
                'amount' => money($plan->amount),
                'currency' => $plan->currency,
                'interval' => $plan->interval,
                'product' => $plan->product,
            ]);
        });

        $this->table($plans->first()->keys(), $plans);
    }
}
