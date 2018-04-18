<?php
namespace App\Socket\Controller\WebSocket;

use App\Socket\Logic\WebSocket\Room;
use App\Socket\Logic\WebSocket\Message;

/**
 *
 */
class Live extends Base
{
    protected $data;
    protected $userId;
    protected $roomId;

    protected function _initialize(){
        if (!$this->checkSetData()) {
            //关闭连接
            $this->close($this->client()->getFd());
        }

        $this->checkOnline($roomId, $userId);
    }

    /**
     * 检查在线
     * @param  string $roomId roomId
     * @param  string $userId $userId
     */
    protected function checkOnline($roomId, $userId)
    {
        //如果不在线
        if (!Room::getInstance()->getUserFd($userId)) {
            Room::getInstance()->intoRoom($roomId, $userId, $this->client()->getFd());
        }
    }

    /**
     * 检查设置参数
     * @return bool
     */
    protected function checkSetData()
    {
        $this->data = $this->request()->getArgs();
        if (empty($this->data)) {
            return false;
        }

        $this->userId = $this->data['userId'];
        $this->roomId = $this->data['roomId'];
        if (empty($this->userId) || empty($this->roomId)) {
            return false;
        }

        return true;
    }

    public function sendToRoom()
    {
        $message = $this->data['message'];

        
    }


    public function test()
    {
        $id = mt_rand(0, 10000);
        Room::getInstance()->intoRoom('5513', $id, $this->client()->getFd());
        $this->response()->write('your fd is '.$this->client()->getFd());
        var_dump(Room::getInstance()->getUserFd('77'));
        Room::getInstance()->sendToRoom('5513', json_encode(['code' => 200, 'msg' => 'ok']));
    }

}
