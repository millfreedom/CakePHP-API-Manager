<?php

/*
 * Throws a generic API error
 */

class ApiException extends CakeException
{
    public function __construct($message = 'There was error in the API. Please try again later.', $code = 500)
    {
        if (Configure::read('ExceptionManager.softErrors')) {
            if (stristr($this->getTrace()[0]['function'], 'api') || stristr($this->getTrace()[0]['class'], 'request')) {
                echo json_encode([
                    'status' => 'error',
                    'methodName' => 'API',
                    'data' => trim($message)
                ]);
            
                exit;
            }
        }
        
        parent::__construct($message, $code);
    }
}