<?php

namespace App;

use Laravel\Cashier\Billable as CashierBillable;
use Stripe\PaymentMethod as StripePaymentMethod;

trait Billable
{
    use CashierBillable;

    /**
     * Get a collection of the entity's payment methods.
     *
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection|\Stripe\PaymentMethod[]
     */
    public function stripePaymentMethods($parameters = [])
    {
        $this->assertCustomerExists();

        $parameters = array_merge(['limit' => 24], $parameters);

        // "type" is temporarily required by Stripe...
        $paymentMethods = StripePaymentMethod::all(
            ['customer' => $this->stripe_id, 'type' => 'card'] + $parameters,
            $this->stripeOptions()
        );

        return $paymentMethods->data;
    }
}
