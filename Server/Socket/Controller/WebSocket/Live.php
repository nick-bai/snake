<?php
namespace App\Socket\Controller\WebSocket;

use App\Socket\Logic\WebSocket\Room;
use App\Socket\Logic\WebSocket\Message;

/**
 *
 */
class Live extends Base
{
    /**
     * 进入房间
     * @return string
     */
    public function joinRoom()
    {
        $Message = new Message($this->request()->getArg('data'));

        //检查必要参数
        if (!$Message->checkData(['userId', 'roomId'])) {
            $this->close($this->client()->getFd(), $Message->getMessage());
            return;
        }

        Room::getInstance()->intoRoom($Message->getData('roomId'), $Message->getData('userId'), $this->client()->getFd());
        //提示用户成功
        $this->response()->write($Message->messageSerialize(200, 'prompt', '加入聊天室成功'));
    }

    /**
     * 发送信息到房间
     * @return  string
     */
    public function sendToRoom()
    {
        $Message = new Message($this->request()->getArg('data'));

        //检查必要参数
        if (!$Message->checkData(['userId', 'roomId'])) {
            $this->close($this->client()->getFd(), $Message->getMessage());
            return;
        }

        //检查信息
        if ($Message->checkMessage('message', 'room_message')) {
            //提示用户成功
            $this->response()->write($Message->messageSerialize(200, 'prompt', '发送成功'));
            //发送到房间
            Room::getInstance()->sendToRoom($Message->getData('roomId'), $Message->getMessage());
        }else {
            //发送错误信息
            $this->response()->write($Message->getMessage());
        }
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
