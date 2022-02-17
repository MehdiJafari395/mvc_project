<?php

namespace System\Database\Traits;

trait HasRelation
{
    protected function hasOne($model, $foreignKey, $localKey)
    {
        if($this->{$this->primaryKey}){
            $modelObject = new $model();
            return $modelObject->getHasOneRelation($this->table, $foreignKey, $localKey, $this->localKey);
        }
    }

    public function getHasOneRelation($table, $foreignKey, $otherKey, $otherKeyValue)
    {
        $this->setSql("SELECT `b`.* FROM `{$table}` AS `a` JOIN".$this->getTableName()." AS `b` ON `a`.`{$otherKey}` = `b`.`{$foreignKey}`");
        $this->setWhere("AND", "`a`.`{$otherKey}` = ?");
        $this->table = 'b';
        $this->addValue($otherKey, $otherKeyValue);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        if($data){
            return $this->arrayToAttributes($data);
        }
        return null;
    }

    protected function hasMany($model, $foreignKey, $otherKey)
    {
        echo 'hasmany';
        if($this->{$this->primaryKey}){
            $modelObject = new $model();
            return $modelObject->getHasManyRelation($this->table, $foreignKey, $otherKey, $this->$otherKey);
        }
    }

    public function getHasManyRelation($table, $foreignKey, $otherKey, $otherKeyValue)
    {
        $this->setSql("SELECT `b`.* FROM `{$table}` AS `a` JOIN ".$this->getTableName()." AS `b` ON `a`.`{$otherKey}` = `b`.`{$foreignKey}`");
        $this->setWhere("AND", "`a`.`{$otherKey}` = ?");
        $this->table = 'b';
//        echo '<pre>';
//        var_dump($otherKey, $otherKeyValue);
//        exit;
        $this->addValue($otherKey, $otherKeyValue);
        return $this;
    }

    protected function belongsTo($model, $foreignKey, $localKey)
    {
        echo 'belongsTo';
        if($this->{$this->primaryKey}){
            $modelObject = new $model();
            return $modelObject->getHasOneRelation($this->table, $foreignKey, $localKey, $this->$foreignKey);
        }
    }

    public function getBelongsToRelation($table, $foreignKey, $otherKey, $foreignKeValue)
    {
        $this->setSql("SELECT `b`.* FROM `{$table}` AS `a` JOIN".$this->getTableName()." AS `b` ON `a`.`{$foreignKey}` = `b`.`{$otherKey}`");
        $this->setWhere("AND", "`a`.`{$foreignKey}` = ?");
        $this->table = 'b';
        $this->addValue($foreignKey, $foreignKeValue);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        if($data){
            return $this->arrayToAttributes($data);
        }
        return null;
    }

    protected function belongsToMany($model, $commonTable, $localKey, $middleforeignKey, $middleRelation, $foreignKey)
    {
        if($this->{$this->primaryKey}){
            $modelObject = new $model();
            return $modelObject->getHasOneRelation($this->table, $commonTable, $localKey, $this->localKey, $middleforeignKey, $middleRelation, $foreignKey);
        }
    }

    public function getBelongsToManyRelation($table, $commonTable, $localKey, $localKeyValue, $middleforeignKey, $middleRelation, $foreignKey)
    {
        $this->setSql("SELECT `c`.* FROM (SELECT `b`.* FROM `{$table}` AS `a` JOIN `{$commonTable}` AS `b` ON
        `a`.`{$localKey}` = `b`.`{$middleforeignKey}` WHERE `a`.`{$localKey}` = ?) AS `relation` JOIN ".
        $this->getTableName()." AS `c` ON `relation`.`{$middleRelation}` = `c`.`{$foreignKey}`");
        $this->setWhere("AND", "`a`.`{$foreignKey}` = ?");
        $this->addValue("{$table}_{$localKey}", $localKeyValue);
        $this->table = 'c';
        return $this;
    }
}