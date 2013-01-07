<?php

namespace LWddd;

class ValueObject
{
    private $values;
    
    function __construct($values, $allowedKeys=false, $validationService=false)
    {
        if ($allowedKeys) {
            $this->checkAllowedKeys($values, $allowedKeys);
        }
        if ($validationService) {
            $this->validate($values, $validationService);
        }
        $this->values = $values;
    }

    public function validate($values, $validationService)
    {
        $validationService->setValues($values);
        $valid = $validationService->validate();
        if (!$valid) {
            throw new Exception('event data is not valid: validation errors');
        }
    }
    
    public function checkAllowedKeys($values, $allowedKeys)
    {
        foreach($values as $key => $value)
        {
            if (!in_array($key, $allowedKeys)) {
                throw new Exception('event data is not valid: invalid key(s)');
            }
        }
    }    
    
    function getValueByKey($key)
    {
        return $this->values[$key];
    }
    
    function getValues()
    {
        return $this->values;
    }
    
    function renderView($view)
    {
        foreach($this->values as $key => $value)
        {
            $view->entity[$key] = $value;
        }
    }
}