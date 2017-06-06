<?php

class Live
{
    private $swoole;

    public function __construct($swoole)
    {
        $this->swoole = $swoole;
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
    }

    public function addAsset($data)
    {

    }

    public function push($data)
    {
        
    }

    public function connect($client)
    {
        $clientInfo = $this->swoole->connection_info($client->fd);
        $port = $clientInfo['server_port'];

        if($port === backendPort){
            C('Publisher Connect', "Worker ID : {$this->swoole->worker_id} / FD : {$client->fd} / Port : " . backendPort);
        }else{
            C('New Client', 'Online : ' . $this->redis->incr(ONLINE));
        }
    }

    public function close($fd)
    {
        $clientInfo = $this->swoole->connection_info($fd);
        $port = $clientInfo['server_port'];

        if($port === backendPort){
            C('Publisher Offline');
        }else{
            C('Client Offline', 'Online : ' . $this->redis->decr(ONLINE));
        }
    }
}