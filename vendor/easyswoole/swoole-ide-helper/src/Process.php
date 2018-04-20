<?php

namespace Swoole;

/**
 * swoole进程管理类
 * 内置IPC通信支持，子进程和主进程之间可以方便的通信
 * 支持标准输入输出重定向，子进程内echo，会发送到管道中，而不是输出屏幕
 * @method exit(int $code) int 退出子进程
 * Class swoole_process
 * @revise for easyswoole at 2018-01-29 15:45:40
 */
class Process
{
    /**
     * 进程的PID
     * @var int
     */
    public $pid;

    /**
     * 管道PIPE
     * @var int
     */
    public $pipe;

    /**
     * 创建子进程 构造函数
     * @param callable $callback              子进程的回调函数
     * @param bool     $redirect_stdin_stdout 是否重定向标准输入输出
     * @param bool     $create_pipe           是否创建管道
     */
    function __construct(callable $callback, $redirect_stdin_stdout = false, $create_pipe = true)
    {
    }

    /**
     * 向管道内写入数据
     * @param string $data
     * @return int
     */
    function write($data)
    {
    }

    /**
     * 从管道内读取数据
     * @param int $buffer_len 最大读取的长度 最大不超过64K
     * @return string
     */
    function read($buffer_len = 8192)
    {
    }

    /**
     * 执行另外的一个程序
     * @param string $execute_file 可执行文件的路径
     * @param array  $params       参数数组
     * @return bool
     */
    function exec($execute_file, array $params)
    {
    }

    /**
     * 阻塞等待子进程退出，并回收
     * 成功返回一个数组包含子进程的PID和退出状态码
     * 如array('code' => 0, 'pid' => 15001)，失败返回false
     * @param bool $blocking 是否阻塞等待
     * @return false | array
     */
    static function wait($blocking = true)
    {
    }

    /**
     * 使当前进程蜕变为一个守护进程
     * @param bool $nochdir 为true表示不要切换当前目录到根目录
     * @param bool $noclose 为true表示不要关闭标准输入输出文件描述符
     * @return bool
     */
    static function daemon($nochdir = false, $noclose = false)
    {
    }

    /**
     * 创建消息队列
     * @param int $msgkey 消息队列KEY
     * @param int $mode   模式
     * @return bool
     */
    function useQueue($msgkey = -1, $mode = 2)
    {
    }

    /**
     * 向消息队列推送数据
     * @param string $data
     * @return bool
     */
    function push($data)
    {
    }

    /**
     * 从消息队列中提取数据
     * @param int $maxsize 获取数据的最大尺寸
     * @return string
     */
    function pop($maxsize = 8192)
    {
    }

    /**
     * 向某个进程发送信号
     * @param     $pid
     * @param int $sig
     * @return bool
     */
    static function kill($pid, $sig = SIGTERM)
    {
    }

    /**
     * 注册信号处理函数
     * require swoole 1.7.9+
     * @param int   $signo
     * @param mixed $callback
     * @return bool
     */
    static function signal($signo, $callback)
    {
    }

    /**
     * 执行fork系统调用，启动进程
     * @return int|false 创建成功返回子进程的PID，创建失败返回false
     */
    function start()
    {
    }

    /**
     * 为工作进程重命名
     * @param $process_name
     * @return bool
     */
    function name($process_name)
    {
    }
}
