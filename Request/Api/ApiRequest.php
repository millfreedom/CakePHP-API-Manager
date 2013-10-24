<?php

class ApiRequest
{

    protected $map = [];
    protected $mandatory = [];
    protected $defaults = [];

    /**
     *
     * @var ArrayObject
     */
    protected $request;

    /**
     *
     * @var ArrayObject
     */
    protected $mappedRequest;

    public static function map($request)
    {
        $calledClass = get_called_class();
        $calledClassInstance = new $calledClass($request);

        $calledClassInstance->mapPrepare();

        //map 
        if ($calledClassInstance->beforeMapCallback()) {
            $calledClassInstance->mapData();
            $calledClassInstance->afterMapCallback();
        }

        //check
        if ($calledClassInstance->beforeCheckCallback()) {
            $calledClassInstance->checkData();
            $calledClassInstance->afterCheckCallback();
        }

        return $calledClassInstance;
    }

    /**
     * 
     * @param array $request
     */
    protected function __construct($request = [])
    {
        $this->request = new ArrayObject($request);
        $this->mappedRequest = new ArrayObject();
    }

    /**
     * 
     * @return \AppModelRequest
     * @throws Exception
     */
    protected function mapPrepare()
    {
        $newMap = [];
        
        foreach ((array) $this->map as $key => $value) {
            if (is_numeric($key)) {
                if (!is_string($value)) {
                    throw new Exception('key must be string, but "' . gettype($value) . '" given.');
                }
                
                $key = $value;
            }
            
            $newMap[$key] = $value;
        }
        
        $this->map = $newMap;
        
        return $this;
    }

    /**
     * 
     * @return \AppModelRequest
     */
    public function mapData()
    {
        foreach ((array) $this->map as $key => $value) {
            $parsedValue = $this->parseValue($this->request, $key);
            
            if (!is_null($parsedValue)) {
                $this->mappedRequest[$value] = $parsedValue;
            }
        }

        return $this;
    }

    /**
     * 
     * @return boolean
     * @throws ApiMissingParamException
     */
    protected function checkData()
    {
        $errors = [];
        
        foreach ($this->mandatory as $key => $value) {
            $allowedValues = [];

            if (is_numeric($key)) {
                $fieldName = $value;
                $key = $value;
            }
            
            if (is_string($value)) {
                $fieldName = $value;
            } elseif (is_array($value)) {
                $fieldName = Hash::get($value, 'name');
                $allowedValues = (array) Hash::get($value, 'allowed');
            }

            if (!$this->mappedRequest->offsetExists($key)) {
                $errors[] = $fieldName;
            } elseif ($this->mappedRequest->offsetGet($key)) {
                //if value is evaluated to true
            } else {
                $isOk = false;
                
                foreach ($allowedValues as $allowedValue) {
                    $isOk = $isOk || ($this->mappedRequest->offsetGet($key) == $allowedValue);
                }
                
                if (!$isOk)
                    throw new ApiException("$fieldName not found.");
            }
        }
        
        if (!empty($errors)) {
            throw new ApiMissingParamException($errors);
        }
        
        return true;
    }

    /**
     * 
     * @param array $data
     * @param string $key
     * @return mixed
     */
    public function parseValue(&$data, $key)
    {
        $return = Hash::get((array) $data, $key);
        
        if (is_null($return)) {
            $return = Hash::get($this->defaults, $key);
        }
        
        return $return;
    }

    /**
     * 
     * @param string $mappedKey
     * @return mixed
     */
    protected function getKeyByMappedKey($mappedKey)
    {
        return array_search($mappedKey, $this->map);
    }

    /**
     * convert data to array
     * @return array
     */
    public function toArray()
    {
        return (array) $this->mappedRequest;
    }

    public function __set($name, $value)
    {
        $this->mappedRequest->offsetSet($name, $value);
    }

    public function __unset($name)
    {
        if ($this->mappedRequest->offsetExists($name)) {
            $this->mappedRequest->offsetUnset($name);
        }
    }

    public function __isset($name)
    {
        return $this->mappedRequest->offsetExists($name);
    }

    public function __get($name)
    {
        if (!$this->mappedRequest->offsetExists($name)) {
            return null;
        }
        
        return $this->mappedRequest->offsetGet($name);
    }

    public function __invoke()
    {
        return $this->toArray();
    }

    /**
     * 
     * @return boolean
     */
    protected function beforeMapCallback()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    protected function afterMapCallback()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    protected function beforeCheckCallback()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    protected function afterCheckCallback()
    {
        return true;
    }
}

?>