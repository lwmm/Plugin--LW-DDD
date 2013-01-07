<?php

namespace LWddd;

class CommandObjectFactory 
{
    public function __construct(\LWddd\DomainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
    
    public function setEmptyEntity($entity)
    {
        $this->entity = $entity;
    }
    
    public function generate()
    {
        $valueObject = $this->filterService->filter($this->domainEvent->getValueObject());
        $valueObject = $this->validateService->validate($valueObject);
        if ($this->validateService->isValid()) {
            $this->entity->setValid(true);
        }
        
        $values = $valueObject->getValues();
        foreach ($values as $key => $value) {
            $this->entity->setValueByKey($key, $value);
        }
        $commandObject = new \LWddd\CommandObject();
        $commandObject->setDomainEvent($this->domainEvent);
        $commandObject->setEntity($this->entity);
        return $commandObject;
    }
}