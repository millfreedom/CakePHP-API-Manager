<?php

App::uses('Model', 'Model');

class ApiModel extends Model
{
    public $name = 'ApiModel';
    
    public $recursive = -1;
    
    public $actsAs = ['Containable'];
}

?>