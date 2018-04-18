<?php
namespace App\Socket\Logic\WebSocket;

use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;
use EasySwoole\Core\Component\Di;

/**
 *
 */
class Room
{
    private static $instance;
    private function __construct(){}

    /**
     * 获取实例
     * @return self Room
     */
    public static function getInstance()
    {
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 获取Redis实例
     * @return object   redis
     */
    public static function getRedis()
    {
        return Di::getInstance()->get('REDIS');
    }

    /**
     * 进入房间
     * @param  string $roomId 房间id
     * @param  string $userId 用户id
     * @param  string $fd     链接id
     */
    public function intoRoom($roomId, $userId, $fd)
    {
        //全局在线 Redis zSet
        self::getRedis()->handler()->zAdd('online', $fd, $userId);
        //关系映射 Redis zSet
        self::getRedis()->handler()->zAdd('room_map', $roomId, $fd);
        //房间在线 Redis list
        self::getRedis()->handler()->lPush('room:'. $roomId, $fd);
    }

    /**
     * 查询房间
     * @param  string $roomId 房间id
     * @return array          房间内人的fd
     */
    public function selectRoom($roomId)
    {
        return self::getRedis()->handler()->lrange('room:'. $roomId, 0, -1);
    }

    /**
     * 获取用户fd
     * @param  string $userId 用户id
     * @return string         用户fd
     */
    public function getUserFd($userId)
    {
        return self::getRedis()->handler()->zScore('online', $userId);
    }

    /**
     * 获取roomId
     * @param  string $fd fd
     * @return string     roomId
     */
    public function getRoomId($fd)
    {
        return self::getRedis()->handler()->zScore('room_map', $fd);
    }

    /**
     * 删除房间内的fd
     * @param  string $roomId roomId
     * @param  string $fd     fd
     * @return int            删除数量
     */
    public function removeRoomFd($roomId, $fd)
    {
        self::getRedis()->handler()->lRem('room:'. $roomId, $fd, 0);
        self::getRedis()->handler()->zRem('room_map', $fd);
    }

    /**
     * 关闭连接
     * @param  string $fd 链接id
     */
    public function close($fd)
    {
        $roomId = $this->getRoomId($fd);
        $this->removeRoomFd($roomId, $fd);
        self::getRedis()->handler()->zRemRangeByScore('online', $fd, $fd);
    }

    /**
     * 发送信息给房间里的所有人
     * @param  string $roomId  roomId
     * @param  string $message 信息
     * @return
     */
    public function sendToRoom($roomId, $message)
    {
        //异步推送
        TaskManager::async(function ()use($roomId, $message){
            $list = Room::getInstance()->selectRoom($roomId);
            foreach ($list as $fd) {
                ServerManager::getInstance()->getServer()->push($fd, $message);
            }
        });
    }
}
