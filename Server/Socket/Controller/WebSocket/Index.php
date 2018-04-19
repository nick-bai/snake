<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;

class Index extends WebSocketController
{
    function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }

    public function controllerNotFound()
    {
        $array = [
            'class' => 'Live',
            'action' => 'sendToRoom',
            'data'  => [
                'userId' => 55,
                'roomId' => 5513,
                'message' => '我是谁'
            ]
        ];
        $this->response()->write(json_encode($array));
        // $this->response()->write("controller not found");
    }
}
