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
        $this->idString = "id";
    }
    
    public function setIdString($id)
    {
        $this->idString = $id;
    }
    
    public function setSession($session)
    {
        $this->session = $session;
    }
    
    public function execute($cmd, lwRequest $HTTPRequest)
    {
        $this->generateDomainEventFromHTTPRequest($cmd, $HTTPRequest);

        if (method_exists($this, $this->domainEvent->getEventName()) && is_callable(array($this, $this->domainEvent->getEventName()))) {
            call_user_func(array($this, $this->domainEvent->getEventName()));
        }
        return $this->response;
    }

    public function generateDomainEventFromHTTPRequest($cmd, $HTTPRequest)
    {
        $domainCommand = $cmd;
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
    
        $dataValueObject = new valueObject($HTTPRequest->getPostArray());
        $parameterValueObject = new valueObject($HTTPRequest->getGetArray());
        $this->domainEvent = new domainEvent($domainCommand, $dataValueObject, $parameterValueObject, $id);
        $this->domainEvent->setSession($this->session);
    }    
}