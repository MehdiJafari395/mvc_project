<?php

namespace System\Database\Traits;

trait HasSofDelete
{
    protected function deleteMethod($id = null)
    {
        $object = $this;
        if($id){
            $this->resetQuery();
            $object = $this->findMethod($id);
        }
        if($object){
            $object->resetQuery();
            $object->setSql("UPDATE ".$object->getTableName(). "SET". $this->getAttributeName($this->deletedAt)."=NOW()");
            $object->setWhere("AND", $this->getAttributeName($object->primaryKey)." = ?");
            $object->addValue($object->primaryKey, $object->{$object->primaryKey});
            return $object->executeQuery();
        }
    }

    protected function allMethod()
    {
        $this->setSql("SELECT".$this->getTableName().".* FROM".$this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL");
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if($data){
            $this->arrayToObject($data);
            return $this->collection;
        }
        return [];
    }

    protected function findMethod($id)
    {
        $this->setSql("SELECT".$this->getTableName().".* FROM".$this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey)." = ?");
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL");
        $this->addValue($this->primaryKey, $id);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        $this->setAllowedMethods(['update', 'delete', 'save']);
        if($data){
            return $this->arrayToAttributes($data);
        }
        return null;
    }

    protected function getMethod($array = [])
    {
        if($this->sql == ''){
            if(empty($array)){
                $fields = $this->getTableName().".*";
            }else{
                foreach ($array as $key=>$field){
                    $array[$key] = $this->getAttributeName($field);
                }
                $fields = implode(' , ', $array);
            }
            $this->setSql("SELECT $fields FROM".$this->getTableName());
        }
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL");
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if($data){
            $this->arrayToObject($data);
            return $this->collection;
        }
        return [];
    }

    protected function paginateMethod($perPage)
    {
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt)." IS NULL");
        $totalRow = $this->getCount();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalPages = ceil($totalRow / $perPage);
        $currentPage = min($totalPages, $currentPage);
        $currentPage = max($totalPages, 1);
        $currentRow = ($currentPage - 1)*$perPage;
        $this->setLimit($currentRow, $perPage);
        if($this->sql == ''){
            $this->setSql("SELECT ".$this->getTableName().".* FROM".$this->getTableName());
        }
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if($data){
            $this->arrayToObject($data);
            return $this->collection;
        }
        return [];
    }
}