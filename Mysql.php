<?php


class Mysql
{
    const DEFAULT_USER = "user";
    const DEFAULT_HOST = "localhost";
    const DEFAULT_PASS = "root";
    const DEFAULT_DBNAME = "ORM";

    private $PDOInstance = null;

    private static $MysqlInstance = null;

    public function __construct()
    {
        $this->PDOInstance = new PDO('mysql:dbname='.self::DEFAULT_DBNAME.';host='.self::DEFAULT_HOST, self::DEFAULT_USER, self::DEFAULT_PASS);
    }

    public static function getInstance(){

        if(is_null(self::$MysqlInstance)){
            self::$MysqlInstance = new Mysql();
        }
        return self::$MysqlInstance;

    }

    public function getConnection(){
        return $this->PDOInstance;
    }


    public function query($query){
        return $this->PDOInstance->query($query);
    }

}