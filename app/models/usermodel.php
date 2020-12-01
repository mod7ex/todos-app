<?php

namespace TODOS\MODELS;

use TODOS\LIB\DB\Db;

class UserModel extends AbstractModel
{

    private $id;
    private $full_name;
    private $email;
    private $passwd_hash;
    private $last_login;
    private $profile_img_url;

    protected static $_last = 'last_login';
    protected static $tableName = 'users';
    protected static $primaryKey = 'id';
    protected static $tableSchema = array(
            'full_name' => self::DATA_TYPE_STR,
            'email' => self::DATA_TYPE_STR,
            'passwd_hash' => self::DATA_TYPE_STR,
            'profile_img_url' => self::DATA_TYPE_STR
        );

    public function __construct($full_name, $email, $passwd_hash)
    {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->passwd_hash = $passwd_hash;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __set($prop, $val)
    {
        $this->$prop = $val;
    }

    public function __isset($prop)
    {
        // TODO: Implement __isset() method.
        return isset($this->$prop);
    }

    public static function checkLogIn($email, $passwd)
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE email = :email;";
        if (Db::dbh()){
            $stmt = Db::dbh()->prepare($sql);
            $stmt->bindValue(':email', $email, self::$tableSchema['email']);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, __CLASS__, array_keys(self::$tableSchema));
                if (!empty(($result))){
                    $user =  array_shift($result);
                    $hashedPasswd = hash('sha256', $passwd, false);
                    if ($hashedPasswd === $user->passwd_hash){
                        return $user;
                    }
                }
            }
        }
        return false;
    }

    public function logOut()
    {

    }

    public function signUp($obj)
    {

    }
}
