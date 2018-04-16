<?php
namespace App\Utility;

use EasySwoole\Config;
use EasySwoole\Core\Swoole\Coroutine\Client\Redis as RedisBase;

/**
 *
 */
class Redis
{
    public function __construct($conf)
    {
        var_dump($conf);
        $redis = new RedisBase($conf['host'], $conf['port'], $conf['serialize'], $conf['auth']);
        if (is_callable($conf['errorHandler'])) {
            $redis->setErrorHandler($conf['errorHandler']);
        }
        $redis->exec('select', $conf['db_name']);
        return $redis;
    }
}
