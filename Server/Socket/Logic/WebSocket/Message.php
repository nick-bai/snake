<?php
namespace App\Socket\Logic\WebSocket;

/**
 *
 */
class Message
{
    private $message;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 检查设置参数
     * @return bool
     */
    public function checkData($checkNameArray)
    {
        if (empty($this->data)) {
            $this->message = $this->messageSerialize(400, 'error', 'not found data');
            return false;
        }

        foreach ($checkNameArray as $name) {
            if (empty($this->data[$name])) {
                $this->message = $this->messageSerialize(400, 'error', 'not found '. $name);
                return false;
            }
        }
        return true;
    }

    /**
     * 检查信息
     * @param  string $message 聊天内容
     * @return bool            是否错误
     */
    public function checkMessage($name)
    {
        $message = $this->getData($name);
        if (empty($message)) {
            $this->message = $this->messageSerialize(400, 'error', 'not found'. $name);
            return false;
        }

        // TODO: 这里应当进行信息过滤防止xss攻击
        $this->message = $this->messageSerialize(200, 'room_message', $message);
        return true;
    }

    /**
     * 获取消息
     * @return string 消息
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 获取参数的值
     * @param  string $name 参数名
     * @return
     */
    public function getData($name)
    {
        return $this->data[$name];
    }

    /**
     * 序列化返回信息
     * @param  int $code        状态码
     * @param  string $type     类型
     * @param  string $message  信息
     * @return string         json信息串
     */
    public function messageSerialize($code, $type, $message)
    {
        return json_encode(['code' => $code, 'type' => $type, 'data' => $message]);
    }
}
