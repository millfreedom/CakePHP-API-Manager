<?php

/*
 * Throws an exception when a paramater that was expected is not available.
 */

class ApiMissingParamException extends ApiException
{
    public function __construct($params = null, $code = 200)
    {
        $message = '';

        if (!empty($params)) {

            if (!is_array($params))
                $params = [$params];

            foreach ($params as $param) {
                $message .= $param . ' is missing. ';
            }
        }

        if (empty($message))
            $message = 'Please check all required fields.';

        parent::__construct(trim($message), $code);
    }
}

?>
