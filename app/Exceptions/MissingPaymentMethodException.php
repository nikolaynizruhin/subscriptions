<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MissingPaymentMethodException extends Exception
{
    /**
     * MissingPaymentMethodException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Missing default payment method", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
