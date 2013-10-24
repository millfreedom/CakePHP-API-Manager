<?php

/*
 * Throws an exception when a paramater that was expected is not available.
 */

App::uses('ApiException', 'ExceptionManager.Lib');

class ApiMissingParamException extends ApiException
{
    public function __construct($params = null, $code = 200)
    {
        $message = [];

        foreach ($params as $param) {
            $message[] = $param . ' is missing.';
        }

        if (empty($message)) {
            $message = ['Please check all required fields.'];
        }
        
        parent::__construct(implode(' ', $message), $code);
    }
}

?>