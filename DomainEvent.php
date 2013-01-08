<?php

namespace LWddd;

class DomainEvent
{
    
    private $values;
    
    function __construct($eventName, $postValueObject, $getValueObject, $id=false)
    {
        $this->eventName = $eventName;
        $this->postValueObject = $postValueObject;
        $this->getValueObject = $getValueObject;
        $this->id = $id;
    }

    function getEventName()
    {
        return $this->eventName;
    }
    
    function getPostValueObject()
    {
        return $this->postValueObject;
    }
    
    function getGetValueObject()
    {
        return $this->getValueObject;
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