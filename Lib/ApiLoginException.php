<?php

/*
 * Throws a login API error
 */

App::uses('ApiException', 'ExceptionManager.Lib');

class ApiLoginException extends ApiException
{
    public function __construct($message = 'Authorization failed. Please check the credentials.', $code = 401)
    {
        parent::__construct($message, $code);
    }
}