<?php
namespace App\Socket\Controller\WebSocket;

use EasySwoole\Core\Socket\Response;

use App\Socket\Logic\WebSocket\Room;

/**
 *
 */
class Live extends Base
{
    protected function _initialize(){

    }

    public function test()
    {
        $id = mt_rand(0, 10000);
        Room::getInstance()->intoRoom('5513', $id, $this->client()->getFd());
        Room::getInstance()->selectRoom('5513');
        $this->response()->write('your fd is '.$this->client()->getFd());
        // var_dump(Room::getInstance()->getUserFd('77'));
        Room::getInstance()->sendToRoom('5513', json_encode(['code' => 200, 'msg' => 'ok']));
    }

}
