<?php

namespace TODOS\MODELS;

use TODOS\LIB\DB\Db;

class AbstractModel
{
    const DATA_TYPE_INT = \PDO::PARAM_INT;
    const DATA_TYPE_STR = \PDO::PARAM_STR;

    public function create()
    {
        $sql = "INSERT INTO " . static::$tableName . " SET ";
        foreach (static::$tableSchema as $culName => $type) {
            $sql .= $culName . ' = :' . $culName . ', ';
        }
        $sql = trim($sql, ', ') . ';';

        if (Db::dbh()) {
            $stmt = Db::dbh()->prepare($sql);
            foreach (static::$tableSchema as $culName => $type) {
                $stmt->bindValue(':' . $culName, $this->$culName, $type);
            }
            if ($stmt->execute()) {
                # get the id of the last inserted element
                # $this->{static::$primaryKey} = Db::dbh()->lastInsertId(); /* this doesn't want to work i don't know what's the reason */
                $lastIdStmt = Db::dbh()->query("SELECT MAX(" . static::$primaryKey . ") AS id FROM " . static::$tableName . ";");
                if ($lastIdStmt){
                    $this->{static::$primaryKey} = $lastIdStmt->fetch(\PDO::FETCH_OBJ)->id;
                    return true;
                }
            }
            return false;
        }
    }

    public function update()
    {
        $sql = "UPDATE " . static::$tableName . " SET ";
        foreach (static::$tableSchema as $culName => $type){
            $sql .= $culName . ' = :' . $culName . ', ';
        }
        $sql = trim($sql, ', ') . " WHERE " . static::$primaryKey . ' = ' . $this->{static::$primaryKey} . ';';

        if (Db::dbh()){
            $stmt = Db::dbh()->prepare($sql);
            foreach (static::$tableSchema as $culName => $type){
                $stmt->bindValue(':' . $culName, $this->$culName, $type);
            }
            if ($this->updateLast()){
                return $stmt->execute();
            }
        }
        return false;
    }

    public function save()
    {
        $resBool =  $this->{static::$primaryKey} ? $this->update() : $this->create();
        if ($resBool){
            $stmt = Db::dbh()->query("SELECT " . static::$_last . " FROM " . static::$tableName . " WHERE " . static::$primaryKey . ' = ' . $this->{static::$primaryKey} . ";");
            if ($stmt){
                $this->{static::$_last} = $stmt->fetch(\PDO::FETCH_OBJ)->{static::$_last};
            }
        }
        return $resBool;
    }

    public function updateLast()
    {
        $sql = "UPDATE " . static::$tableName . " SET " . static::$_last . " =  CURRENT_TIMESTAMP()
                WHERE " . static::$primaryKey . ' = ' . $this->{static::$primaryKey} . ';';
        if (Db::dbh()){
            return boolval(Db::dbh()->exec($sql));
        }
        return false;
    }


    public function delete()
    {
        $sql = "DELETE  FROM " . static::$tableName . " WHERE " . static::$primaryKey . ' = ' . $this->{static::$primaryKey} . ';';
        if (Db::dbh()){
            return boolval(Db::dbh()->exec($sql));
        }
        return false;
    }

    public static function getByPk($pk)
    {
        $sql = "SELECT * FROM " . static::$tableName . " WHERE " . static::$primaryKey . " = :" . static::$primaryKey . ';';
        if (Db::dbh()){
            $stmt = Db::dbh()->prepare($sql);
            $stmt->bindValue(':' . static::$primaryKey, $pk, self::DATA_TYPE_INT);
            if ($stmt->execute()){
                $className = get_called_class();
                $result = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className, array_keys($className::$tableSchema));
                if (!empty($result)){
                    return array_shift($result);
                }
            }
            return false;
        }
    }
}