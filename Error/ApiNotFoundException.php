<?php

/*
 * Throws an exception when something that was being searched for could not be found.
 */

App::uses('ApiException', 'ApiManager.Error');

class ApiNotFoundException extends ApiException
{
    public function __construct($object = 'object', $code = 404)
    {
        $message = sprintf('The %s you are looking for could not be found. Please try again later.', $object);

        parent::__construct($message, $code);
    }
}

?>