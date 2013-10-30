<?php

App::uses('Controller', 'Controller');

class ApiController extends Controller
{
	protected function api_DefaultFlow(\AppModel $model, $function, $requestMapper) 
    {
        if (Configure::read('ApiManager.log')) {
            $this->loadModel('ApiManager.ApiLog');
            $this->ApiLog->create();
            $this->ApiLog->save(array(
                'controller' => $this->request->params['controller'],
                'action' => $this->action,
                'data' => json_encode($this->data)
            )); 
        }

		if (!$this->request->isPost()) {
		    throw new ApiNotPostException();
		}
		
		// Get image data from POST array
		if (!empty($this->request->params['form'])) {
		    $data = array_merge($this->request->data, $this->request->params['form']);
		} else {
		    $data = $this->request->data;
		}
        
        if (!in_array($this->action, Configure::read('ApiManager.objectifyExclude'))) {
            $data = $this->_objectify($model->{$function}($requestMapper::map($data)));
        } else {
            $data = $model->{$function}($requestMapper::map($data));
        }
        
		$response = [
		    'status' => 'success',
            'methodName' => 'Api'.$this->name.ucwords(str_replace('api_', '', $this->action)),
            'data' => $data
		];

		$this->set('Success', $response);
		$this->set('_serialize', 'Success');
	}
    
    // .hack//SIGN
    private function _objectify($data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->_objectify($value);
            }
        }
        
        return (object) $data;
    }
}
