<?php

/*
 * Throws an exception when there is an error while saving an object
 */

App::uses('ApiException', 'Error/Exception');

class ApiSaveException extends ApiException 
{ 
	public function __construct($validationErrors = null, $code = 500) 
    {
		$message = [];
		
		foreach ($validationErrors as $validationError) {
			$message[] = $validationError;
		}
		
		if (empty($message)) {
			$message = ["There was an error saving your information. Please try again later"];
		}
		
		parent::__construct(implode(' ', $message), $code);
	}
}

?>