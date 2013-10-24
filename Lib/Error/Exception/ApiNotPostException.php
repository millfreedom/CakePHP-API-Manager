<?php

/*
 * Throws an exception if an API call is not using POST
 */

App::uses('ApiException', 'Error/Exception');

class ApiNotPostException extends ApiException 
{ 
	public function __construct($message = 'This action is not permitted.', $code = 500) 
    {
		parent::__construct($message, $code); 
	}
}

?>