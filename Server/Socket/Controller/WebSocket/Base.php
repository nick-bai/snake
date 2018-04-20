<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\ServerManager;

class Base extends WebSocketController
{

    /**
     * 访问找不到的action
     * @param  ?string $actionName 找不到的name名
     * @return string
     */
    public function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }

    /**
     * 主动关闭连接
     * @param  string $fd       连接id
     * @param  string $message  信息
     */
    public function close($fd, $message = 'refuse')
    {
        $this->response()->write($message);
        ServerManager::getInstance()->getServer()->close($fd);
    }
}
