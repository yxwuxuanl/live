<?php

include __DIR__ . '/live.php';

$config = json_decode(file_get_contents(__DIR__ . '/config.json'), TRUE);

define('frontendPort', $config['frontendPort']);
define('backendPort', $config['backendPort']);

$swoole = new swoole_websocket_server('0.0.0.0', frontendPort);
$swoole->addListener('0.0.0.0', backendPort, SWOOLE_SOCK_TCP);

$swoole->on('workerstart', function($swoole){
    $swoole->live = new Live($swoole);
});

$swoole->on('pipeMessage', function($swoole, $src_worker_id, $data){
    
});

$swoole->on('open', function($swoole, $request){
    $swoole->live->connect($request);
});

$swoole->on('message', function($swoole, $request){

});

$swoole->on('close', function($swoole, $fd){
    $swoole->live->close($fd);
});

function C($type, $msg = '')
{
    echo "[$type] " . date('H:i:s') . " $msg \n";
}

$swoole->start();