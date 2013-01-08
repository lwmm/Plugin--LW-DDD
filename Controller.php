<?php

namespace LWddd;
use \lw_response as lwResponse;
use \lw_request as lwRequest;
use \LWddd\ValueObject as valueObject;
use \LWddd\DomainEvent as domainEvent;
use \LWddd\CommandBus as commandBus;

class Controller 
{
    public function __construct(lwResponse $response)
    {
        $this->response = $response;
        $this->commandBus = new commandBus();
        $this->commandString = "cmd";
        $this->idString = "id";
    }
    
    public function setCommandString($cmd)
    {
        $this->commandString = $cmd;
    }
    
    public function setIdString($id)
    {
        $this->idString = $id;
    }
    
    public function execute(lwRequest $HTTPRequest)
    {
        $this->generateDomainEventFromHTTPRequest($HTTPRequest);

        if (method_exists($this, $this->domainEvent->getEventName()) && is_callable(array($this, $this->domainEvent->getEventName()))) {
            call_user_func(array($this, $this->domainEvent->getEventName()));
        }
        return $this->response;
    }

    public function generateDomainEventFromHTTPRequest($HTTPRequest)
    {
        $domainCommand = $HTTPRequest->getAlnum($this->commandString);
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
    
        $id = $HTTPRequest->getInt($this->idString);
    
        $postValueObject = new valueObject($HTTPRequest->getPostArray());
        $getValueObject = new valueObject($HTTPRequest->getGetArray());
        $this->domainEvent = new domainEvent($domainCommand, $postValueObject, $getValueObject, $id);
    }    
}