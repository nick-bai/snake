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
            'class' => 'Test',
            'action' => 'who'
        ];
        $this->response()->write(json_encode($array));
        // $this->response()->write("controller not found");
    }
}
