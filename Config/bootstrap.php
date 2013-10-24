<?php

/**
 * Please copy the config below and place it on your /app/Config/bootstrap.php
 * Remember to fill in the fields!
 */

// Turn this on if you want to do "soft" errors, 
// ie. responses HTTP code 200 but with an error
if (!Configure::read('ApiManager.softErrors')) {
    Configure::write('ApiManager.softErrors', false);
}
    
?>