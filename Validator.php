<?php

namespace LWddd;

class Validator
{
    public function __construct()
    {
        
    }
    
    protected function setDataArray($array)
    {
        $this->array = $array;
    }
    
    protected function resetErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }
    
    protected function addError($key, $number, $array=false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }    
    
    public function isEmail($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            return false;
        }
        return true;
    }    
    
    static function hasMaxlength($value, $options) 
    {
        if (strlen(trim($value)) > intval($options['maxlength'])) {
            return false;
        }
        return true;
    }
    
    static function hasMinlength($value, $options) 
    {
        if (strlen(trim($value)) < intval($options['minlength'])) {
            return false;
        }
        return true;
    }
    
    static function isRequired($value) 
    {
        if (strlen(trim($value)) < 1) {
            return false;
        }
        return true;
    }    
    
    static function isAlnum($value) 
    {
        $test = preg_replace('/[^a-zA-Z0-9\s]/', '', (string) $value);
        if (Validator::isRequired($value) && ($value != $test)) {
            return false;
        }
        return true;
    }
    
    static function isBetween($value, $options) 
    {
        if (Validator::isRequired($value) && ($value < strval($options["value1"]) || $value > strval($options["value2"]))) {
            return false;
        }
        return true;
    }    
    
    static function isDigits($value) 
    {
    	$test = preg_replace('/[^0-9]/', '', (string) $value);
        if (Validator::isRequired($value) && ($value != $test)) {
            return false;
        }
        return true;
    }
    
    static function isGreaterThan($value, $options) 
    {
        if (Validator::isRequired($value) && ($value < strval($options["value"]))) {
            return false;
        }
        return true;
    }

    static function isLessThan($value, $options) 
    {
        if (Validator::isRequired($value) && ($value > strval($options["value"]))) {
            return false;
        }
        return true;
    }
    
    static function isInt($value) 
    {
        if (Validator::isRequired($value) && (!is_int($value))) {
            return false;
        }
        return true;
    }
    
    static function isFiletype($value, $options) 
    {
    	if (Validator::isRequired($value)) {
            $ext = strtolower(substr($value,strrpos($value,'.')+1,strlen($value)));
            if (!strstr($options["extensions"], ":".$ext.":")) {
                return false;
            }
        }
        return true;
    }

    static function isImage($value) 
    {
        return Validator::isFiletype($value, array('extensions' => ':jpg:jpeg:png:gif:'));
    }
}