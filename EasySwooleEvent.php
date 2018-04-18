<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventHelper;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Component\Di;

use \MysqliDb;
use \App\Utility\Redis;

use App\Socket\Parser\WebSocket;
use App\Socket\Logic\WebSocket\Room;

Class EasySwooleEvent implements EventInterface {

    public function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        //注册WebSocket处理
        EventHelper::registerDefaultOnMessage($register, new WebSocket());
        //注册onClose事件
        $register->add($register::onClose, function (\swoole_server $server, $fd, $reactorId) {
            //清除Redis fd的全部关联
            Room::getInstance()->close($fd);
        });

        Di::getInstance()->set('MYSQL', MysqliDb::class, Config::getInstance()->getConf('MYSQL'));
        Di::getInstance()->set('REDIS', new Redis(Config::getInstance()->getConf('REDIS')));
    }
    
    public function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}
