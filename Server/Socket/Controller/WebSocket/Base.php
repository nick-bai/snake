<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Component\Spl\SplStream;
use EasySwoole\Core\Socket\Client\WebSocket;
use EasySwoole\Core\Socket\Common\CommandBean;
use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;

class Base extends WebSocketController
{
    public function __construct(WebSocket $client,CommandBean $request,SplStream $response)
    {
        parent::__construct($client, $request, $response);
        $this->_initialize();
    }

    protected function _initialize(){}

    /**
     * 访问找不到的action
     * @param  ?string $actionName 找不到的name名
     * @return string
     */
    public function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }
}
