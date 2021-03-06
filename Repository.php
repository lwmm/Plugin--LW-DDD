<?php

namespace LWddd;

class Repository
{
    public function __construct()
    {
        $this->dic = new \lwMembersearch\Services\dic();
    }
    
    public function buildObjectByArray($data)
    {
        $object = $this->buildObjectById($data['id']);
        return $this->prepareObject($object, $data);
    }

    protected function buildAggregateFromQueryResult($items)
    {
        foreach($items as $item) {
            $entities[] =  $this->buildObjectByArray($item);
        }
        return new \LWddd\EntityAggregate($entities);
    }
    
    public function getAllObjectsAggregate()
    {
        $items = $this->getQueryHandler()->loadAllEntries();
        return $this->buildAggregateFromQueryResult($items);
    }
    
    public function prepareObject($object, $data)
    {
        $object->setDataValueObject(new \LWddd\ValueObject($data));
        $object->setLoaded();
        $object->unsetDirty();
        return $object;
    }
    
    public function getObjectById($id)
    {
        $data = $this->getQueryHandler()->loadObjectById($id);
        return $this->buildObjectByArray($data);
    }
    
    public function saveObject($id, $dataObject)
    {
        $DataValueObjectFiltered = $this->getDataValueObjectFilter()->filter($dataObject);
        $entity = $this->buildEntityFromFilteredValues($id, $DataValueObjectFiltered);
        if ($this->getIsValidSpecification()->isSatisfiedBy($entity)) {
            return $this->saveValidObject($id, $entity);
        }
        else {
            $exception = new \LWddd\validationErrorsException('Error');
            $exception->setErrors($this->getIsValidSpecification()->getErrors());
            throw $exception;
        }
    }
    
    protected function buildEntityFromFilteredValues($id, $DataValueObjectFiltered)
    {
        if (!$id) {
            $entity = $this->getFactory()->buildNewObjectFromValueObject($DataValueObjectFiltered);
        }
        else {
            $entity = $this->getObjectById($id);
            $entity->setDataValueObject($DataValueObjectFiltered);
        }
        return $entity;
    }
    
    protected function saveValidObject($id, $entity)
    {
        if ($entity->getId() > 0 ) {
            $result = $this->getCommandHandler()->saveEntity($entity->getId(), $entity->getValues());
            $id = $entity->getId();
        }
        else {
            $result = $this->getCommandHandler()->addEntity($entity->getValues());
            $id = $result;
        }
        $this->postSaveWork($result, $id, $entity);
        return $id;
    }
    
    protected function postSaveWork($result, $id, $entity)
    {
        if ($result) {
            $entity->setLoaded();
            $entity->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $entity->setLoaded();
            }
            else {
                $entity->unsetLoaded();
            }
            $entity->setDirty();
            throw new Exception('An DB Error occured saving the Entity');
        }        
    }
    
    public function deleteObjectById($id)
    {
        $fb = $this->getObjectById($id);
        if ($this->getIsDeletableSpecification()->isSatisfiedBy($fb)) {
            return $this->getCommandHandler()->deleteEntityById($id);
        }
        else {
            throw new Exception('Delete not allowed');
        }
    }
}