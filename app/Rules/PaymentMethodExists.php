<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class PaymentMethodExists implements Rule
{
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new rule instance.
     *
     * @param  \App\User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !!$this->user->findPaymentMethod($value);
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
