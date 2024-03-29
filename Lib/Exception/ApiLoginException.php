<?php

/*
 * Throws a login API error
 */

class ApiLoginException extends ApiException
{
    public function __construct($message = 'Authorization failed. Please check the credentials.', $code = 401)
    {
        parent::__construct($message, $code);
    }
}