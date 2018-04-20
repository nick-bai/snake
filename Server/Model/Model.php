<?php
namespace App\Model;

use EasySwoole\Core\Component\Di;

/**
 *
 */
class Model
{
    private static $db;

    public function __construct()
    {
        $db = Di::getInstance()->get("MYSQL");
        if($db instanceof \MysqliDb){
            self::$db = $db;
        }
    }

    public function dbConnector()
    {
        return self::$db;
    }
}
