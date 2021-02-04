<?php
namespace Site\models\helper;
use PDO;
if (!defined('URL')) {
    header("Location: /");
    exit();
}

class ModelsConn{
    public static $host = HOST;
    public static $dbname = DBNAME;
    public static $user = USER;
    public static $pass = PASS;
    public static $connection = null;

    private static function conectar(){
        try{
            if (self::$connection == null) {
                self::$connection = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname
                    .";charset=utf8", self::$user, self::$pass);
            }
        }catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return self::$connection;
    }

    public function getConn(){
        return self::conectar();
    }
}
