<?php
namespace App\Utility;

use EasySwoole\Config;
use EasySwoole\Core\Swoole\Coroutine\Client\Redis as RedisBase;

/**
 *
 */
class Redis
{
    private static $redis;

    private function __construct()
    {
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new RedisBase($conf['host'], $conf['port'], $conf['serialize'], $conf['auth']);
        if (is_callable($conf['errorHandler'])) {
            $redis->setErrorHandler($conf['errorHandler']);
        }
        $redis->exec('select', $conf['db_name']);
        return $redis;
    }

    public function getInstance()
    {
        if(!isset(self::$redis)){
            self::$redis = new static;
        }
        return self::$redis;
    }
}
