<?php

namespace App;

use Exception;
use Laravel\Cashier\Cashier;
use Stripe\Plan as StripePlan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Plan
{
    /**
     * Get list of subscription plans.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        $product = config('subscription.product');

        $plans = StripePlan::all(['product' => $product], Cashier::stripeOptions());

        return collect($plans->data);
    }

    /**
     * Get subscription plan by id.
     *
     * @param  string  $id
     * @return StripePlan|null
     */
    public static function find($id)
    {
        try {
            return StripePlan::retrieve($id, Cashier::stripeOptions());
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Get subscription plan by id.
     *
     * @param  string  $id
     * @return StripePlan|null
     */
    public static function findOrFail($id)
    {
        $plan = static::find($id);

        if (is_null($plan)) {
            throw new NotFoundHttpException;
        }

        return $plan;
    }

    /**
     * Create new plan.
     *
     * @param  array  $params
     * @return \App\Plan
     */
    public static function create($params)
    {
        return StripePlan::create($params, Cashier::stripeOptions());
    }
}
