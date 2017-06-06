<?php

define('frontendPort', 8081);
define('backendPort', 8082);

include __DIR__ . '/live.php';

$swoole = new swoole_websocket_server('0.0.0.0', frontendPort);
$swoole->addListener('0.0.0.0', backendPort, SWOOLE_SOCK_TCP);

$swoole->on('workerstart', function($swoole){

});

$swoole->on('pipeMessage', function($swoole, $src_worker_id, $data){
    
});

$swoole->on('open', function($swoole, $request){
    
});

$swoole->on('message', function($swoole, $request){
    echo $request->data;
});

$swoole->on('close', function($swoole, $fd){

});

function C($type, $msg = '')
{
    echo "[$type] " . date('H:i:s') . " $msg \n";
}

$swoole->start();