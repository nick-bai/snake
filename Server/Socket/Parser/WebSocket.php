<?php
namespace App\Socket\Parser;


use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

use App\Socket\Controller\WebSocket\Index;

class WebSocket implements ParserInterface
{

    public function decode($raw, $client)
    {
        if ('PING' === $raw) {
            return json_encode(['code' => 200, 'message' => 'ok']);
        }

        $commandLine = json_decode($raw, true);
        if (!$commandLine) {
            return 'unknown command';
        }

        $CommandBean = new CommandBean();

        $control = isset($commandLine['class']) ? 'App\\Socket\\Controller\\WebSocket\\'. ucfirst($commandLine['class']) : '';
        $action = $commandLine['action'] ?? 'none';
        $data = $commandLine['data'] ?? null;

        $CommandBean->setControllerClass(class_exists($control) ? $control : Index::class);
        $CommandBean->setAction(class_exists($control) ? $action : 'controllerNotFound');
        $CommandBean->setArg('data', $data);

        return $CommandBean;
    }

    public function encode(string $raw, $client, $commandBean): ?string
    {
        // TODO: Implement encode() method.
        return $raw;
    }
}
