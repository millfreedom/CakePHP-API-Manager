<?php

App::uses('AppModel', 'Model');

class ApiLog extends AppModel {
	public $name = 'ApiLog';
    
    public function log($call)
    {
        $this->create();
        $this->save(array(
            'controller' => $call->request->params['controller'],
            'action' => $call->action,
            'data' => json_encode($call->data)
        )); 
    }
}

?>
