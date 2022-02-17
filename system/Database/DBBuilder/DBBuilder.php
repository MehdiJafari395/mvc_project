<?php

namespace System\Database\DBBuilder;

use System\Config\Config;
use System\Database\DBConnection\DBConnection;

class DBBuilder
{
    public function __construct()
    {
        $this->createTable();
    }

    private function createTable()
    {
        $migrations = $this->getMigrations();
        $dbInstance = DBConnection::getDBConnectionInstance();
        foreach ($migrations as $migration){
            $statement = $dbInstance->prepare($migration);
            $statement->execute();
        }
        return true;
    }

    private function getMigrations()
    {
        $oldMigrations = $this->getOldMigrations();
        $migrationsDirectory = Config::get('app.BASE_DIR') . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "migrations" .
            DIRECTORY_SEPARATOR;
        $allMigrations = glob($migrationsDirectory . "*.php");
        $newMigrations = array_diff($allMigrations, $oldMigrations);
        $this->putMigrations($allMigrations);
        // get sql code from migrations and execute those
        $sqlCodeArray = [];
        foreach ($newMigrations as $fileName){
            $sqlCode = require $fileName;
            array_push($sqlCodeArray, $sqlCode[0]);
        }
        return $sqlCodeArray;
    }

    private function getOldMigrations()
    {
        $oldMigrations = file_get_contents(__DIR__.'/oldTables.db');
        return empty($oldMigrations) ? [] : unserialize($oldMigrations);
    }

    private function putMigrations($data)
    {
        file_put_contents(__DIR__."/oldTables.db", serialize($data));
    }
}