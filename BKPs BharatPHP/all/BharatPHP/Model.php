<?php

namespace BharatPHP;

use PDO;
use BharatPHP\Config;

abstract class Model {

    static $db = null;

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB($database_config = null) {

        if (is_null($database_config)) {
            //load databse from database key on config
            if (static::$db === null) {
                $dsn = 'mysql:host=' . Config::get('database.drivers.mysql.host') . ';dbname=' . Config::get('database.drivers.mysql.database') . ';charset=utf8';
                $db = new PDO($dsn, Config::get('database.drivers.mysql.username'), Config::get('database.drivers.mysql.password'));
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8;SET time_zone = '+00:00'");
                static::$db = $db;
            }
            return static::$db;
        } else {
            
        }
    }

    protected static function getDBMysqli() {
        static $db = null;
        if ($db === null) {
            $db = new \mysqli(Config::get('database.drivers.mysql.host'), Config::get('database.drivers.mysql.username'), Config::get('database.drivers.mysql.password'), Config::get('database.drivers.mysql.database'));
        }
        return $db;
    }

}
