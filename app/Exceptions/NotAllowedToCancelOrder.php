<?php

namespace App\Exceptions;

use Exception;

class NotAllowedToCancelOrder extends Exception
{
    public function __construct(string $message = 'You are not allowed to cancel this order.')
    {
        parent::__construct($message);
    }
}
