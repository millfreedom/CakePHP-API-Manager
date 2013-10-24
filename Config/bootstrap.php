<?php

/**
 * Please copy the config below and place it on your /app/Config/bootstrap.php
 * Remember to fill in the fields!
 */

// Turn this on if you want to do "soft" errors, 
// ie. responses HTTP code 200 but with an error
Configure::write('ApiManager.softErrors', false);

foreach (['ApiException', 'ApiSaveException', 'ApiLoginException', 'ApiNotPostException', 'ApiNotFoundException', 'ApiMissingParamException'] as $exception) {
    require APP . 'Plugin' . DS . 'ApiManager' . DS . 'Lib' . DS . 'Exception' . DS . $exception . '.php';
}

require APP . 'Plugin' . DS . 'ApiManager' . DS . 'Request' . DS . 'Api' . DS . 'ApiRequest.php';
    
?>