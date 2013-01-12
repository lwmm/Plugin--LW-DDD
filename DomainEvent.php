<?php

namespace LWddd;

class DomainEvent
{
    
    private $values;
    
    function __construct($eventName, ValueObject $dataValueObject, ValueObject $parameterValueObject, $id=false)
    {
        $this->eventName = $eventName;
        $this->dataValueObject = $dataValueObject;
        $this->parameterValueObject = $parameterValueObject;
        $this->id = $id;
    }

    function getEventName()
    {
        return $this->eventName;
    }
    
    function getDataValueObject()
    {
        return $this->dataValueObject;
    }
    
    function getDataByKey($key)
    {
        return $this->dataValueObject->getValueByKey($key);
    }
    
    function getParameterValueObject()
    {
        return $this->parameterValueObject;
    }
    
    function getParameterByKey($key)
    {
        return $this->parameterValueObject->getValueByKey($key);
    }
    
    function getId()
    {
        return $this->id;
    }
    
    function setSession($session)
    {
        $this->session = $session;
    }
    
    function getSession()
    {
        if ($this->hasSession()) {
            return $this->session;
        }
        return false;
    }
    
    public function hasSession()
    {
        if ($this->session) {
            return true;
        }
        return false;
    }
    
    function setEntity(\LWddd\Entity $entity)
    {
        $this->entity = $entity;
    }
    
    function hasEntity()
    {
        if (is_object($this->entity)) {
            return true;
        }
        return false;
    }
    
    function getEntity()
    {
        return $this->entity;
    }
}