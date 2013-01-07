<?php

namespace LWddd;

class Controller 
{
    
    public function __construct()
    {
        $this->response = \lw_response::getInstance();
        $this->commandBus = new \LWddd\CommandBus();
    }
    
    public function execute(\lw_request $HTTPRequest)
    {
        $this->generateDomainEventFromHTTPRequest($HTTPRequest);

        if (method_exists($this, $this->domainEvent->getEventName()) && is_callable(array($this, $this->domainEvent->getEventName()))) {
            call_user_func(array($this, $this->domainEvent->getEventName()));
        }
        return $this->response;
    }

    public function generateDomainEventFromHTTPRequest($HTTPRequest)
    {
        $domainCommand = $HTTPRequest->getAlnum("cmd");
        if (!$domainCommand) {
            if ($this->defaultAction) {
                $domainCommand = $this->defaultAction;
            } 
            else {
                $domainCommand = "indexAction";
            }
        }
        else {
            $domainCommand = $domainCommand."Action";
        }   
    
        $id = $HTTPRequest->getInt("id");
    
        $postValueObject = new \LWddd\ValueObject($HTTPRequest->getPostArray());
        $getValueObject = new \LWddd\ValueObject($HTTPRequest->getGetArray());
        $this->domainEvent = new \LWddd\DomainEvent($domainCommand, $postValueObject, $getValueObject, $id);
    }    
}