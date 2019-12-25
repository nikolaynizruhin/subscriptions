<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Laravel\Cashier\Cashier;
use Stripe\PaymentMethod;

class PaymentMethodExists implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            return (bool) PaymentMethod::retrieve($value, Cashier::stripeOptions());
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
