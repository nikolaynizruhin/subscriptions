<?php

namespace App;

use Laravel\Cashier\Billable as CashierBillable;
use Stripe\PaymentMethod as StripePaymentMethod;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * End trial period.
     */
    public function endTrial()
    {
        return $this->update(['trial_ends_at' => null]);
    }

    /**
     * Find payment method or fail.
     *
     * @param  string  $id
     * @return \Laravel\Cashier\PaymentMethod|null
     */
    public function findPaymentMethodOrFail($id)
    {
        $paymentMethod = $this->findPaymentMethod($id);

        if (is_null($paymentMethod)) {
            throw new NotFoundHttpException;
        }

        return $paymentMethod;
    }
}
