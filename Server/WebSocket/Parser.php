<?php
namespace App\WebSocket;


use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

class Parser implements ParserInterface
{

    public function decode($raw, $client)
    {
        // TODO: Implement decode() method.
        $command = new CommandBean();
        $json = json_decode($raw,1);
        $command->setControllerClass(\App\WebSocket\Test::class);
        $command->setAction($json['action']);
        $command->setArg('content',$json['content']);
        return $command;

    }

    public function encode(string $raw, $client, $commandBean): ?string
    {
        // TODO: Implement encode() method.
        return $raw;
    }
}
