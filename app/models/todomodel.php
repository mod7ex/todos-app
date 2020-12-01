<?php

namespace TODOS\MODELS;

use TODOS\LIB\DB\Db;

class TodoModel extends AbstractModel implements \JsonSerializable
{
    private $id;
    private $user_id;
    private $td_title;
    private $td_content;
    private $created_at;
    private $last_updated;

    protected static $_last = 'last_updated';
    protected static $tableName = 'todos';
    protected static $primaryKey = 'id';
    protected static $tableSchema = array(
            'user_id' => self::DATA_TYPE_INT,
            'td_title' => self::DATA_TYPE_STR,
            'td_content' => self::DATA_TYPE_STR
        );

    public function __construct($user_id, $td_title, $td_content)
    {
        $this->user_id = $user_id;
        $this->td_title = $td_title;
        $this->td_content = $td_content;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __set($prop, $val)
    {
        $this->$prop = $val;
    }

    public static function getAll($user_id)
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE user_id = '$user_id';";
        $stmt = Db::dbh()->query($sql);
        $result = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, __CLASS__, array_keys(self::$tableSchema));
        if (!empty($result)){
            return $result;
        }
        return false;
    }

    public function jsonSerialize()
    {
        $todo = new \stdClass();

        $todo->id = $this->id;
        $todo->td_title = $this->td_title;
        $todo->td_content = $this->td_content;
        $todo->created_at = $this->created_at;
        $todo->last_updated = $this->last_updated;
        return $todo;
    }
}