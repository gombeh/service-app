<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    /**
     * @param $message
     * @param $code
     */
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
