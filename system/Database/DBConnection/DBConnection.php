<?php

namespace System\Database\DBConnection;

use \PDO;
use \PDOException;

class DBConnection
{
    private static $DBConnectionInstance = null;

    private function __construct()
    {
    }

    public static function getDBConnectionInstance()
    {
        if(self::$DBConnectionInstance == null ){
            $connection = new DBConnection();
            self::$DBConnectionInstance = $connection->dbConnecion();
        }

        return self::$DBConnectionInstance;
    }

    private function dbConnecion()
    {
        $options = [PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

        try{
            return new PDO("mysql:host=".DBHOST.";dbname=".DBNAME,DBUSERNAME, DBPASSWORD, $options);
        }catch (PDOException $e){
            echo "database connection error is :" . $e->getMessage();
            return false;
        }
    }

    public static function newInsertId()
    {
        return self::getDBConnectionInstance()->lastInsertId();
    }
}