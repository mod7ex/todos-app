<?php

namespace TODOS\LIB\DB;

class Db
{
    private static $pdo = NULL;

    public static function dbh()
    {
        try {
            self::$pdo = new \PDO(DSN, DB_USER, DB_PASS);
        }catch (\PDOException $e){

        }

        return self::$pdo;
    }
}