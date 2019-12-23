<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Laravel\Cashier\Cashier;
use Stripe\Plan;

class PlanExists implements Rule
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
            return (bool) Plan::retrieve($value, Cashier::stripeOptions());
        } catch (Exception $exception) {
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
