<?php

// Redis Keys

define('C_MAX_CONNECT', 'c_max_connect');
define('C_DENY_CONNECT', 'c_deny_connect');
define('C_DELAY_MIN', 'c_delay_min');
define('C_DELAY_MAX', 'c_delay_max');

define('ONLINE', 'online');

define('L_ASSET', 'l_asset');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$redis->set(C_MAX_CONNECT, 10240);

$redis->set(C_DENY_CONNECT, 0);
$redis->set(C_DELAY_MIN, 0);
$redis->set(C_DELAY_MAX, 10);

$redis->set(ONLINE, 0);

$redis->close();

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