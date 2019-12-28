<?php

namespace App;

use Laravel\Cashier\SubscriptionBuilder as CashierSubscriptionBuilder;

class SubscriptionBuilder extends CashierSubscriptionBuilder
{
    /**
     * Create a new Stripe subscription.
     *
     * @param  \Stripe\PaymentMethod|string|null  $paymentMethod
     * @param  array  $options
     * @return \Laravel\Cashier\Subscription
     *
     * @throws \Laravel\Cashier\Exceptions\PaymentActionRequired
     * @throws \Laravel\Cashier\Exceptions\PaymentFailure
     */
    public function create($paymentMethod = null, array $options = [])
    {
        $subscription = parent::create($paymentMethod, $options);

        $this->owner->endTrial();

        return $subscription;
    }
}
