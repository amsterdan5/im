<?php
if(file_exists('config.php')) {
    $config = require_once 'config.php';
} else {
    //throw new Exception('Cannot find config file!');
    die('Cannot find config file!');
}

if( !$config['SERVER_IP'] || !$config['SERVER_PORT']) {
    //throw new Exception('Must be setting ip and port!');
    die('Must be setting ip and port!');
}

$serv = new swoole_websocket_server($config['SERVER_IP'],$config['SERVER_PORT']);

$serv->on('Open',function($server, $req){
    echo 'connection open:'. $req->fd.' -- ';
});

$serv->on('Message',function($server,$frame){
    if( !empty($frame->data)) {
        $server->push($frame->fd, $frame->data);
    }
});

$serv->on('Close',function($server,$fd){
    echo 'connection close:'.$fd.' -- ';
});

$serv->start();