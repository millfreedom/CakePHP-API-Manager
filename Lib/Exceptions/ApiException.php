<?php

/*
 * Throws a generic API error
 */

class ApiException extends CakeException
{
    public function __construct($message = 'There was error in the API. Please try again later.', $code = 500)
    {
        if (Configure::read('ApiManager.softErrors')) {
            if (
                (
                    isset($this->getTrace()[0]['function']) &&
                    stristr($this->getTrace()[0]['function'], 'api')
                ) ||
                (
                    isset($this->getTrace()[0]['class']) &&
                    stristr($this->getTrace()[0]['class'], 'request')
                )
            ) {
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