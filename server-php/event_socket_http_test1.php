<?php 

$host = '0.0.0.0';
$port = 9999;
$socket_resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket_resource, $host, $port);
socket_listen($socket_resource);

echo PHP_EOL."Http server ON: http://{$host}:{$port}" . PHP_EOL;

// 将服务器设置为非堵塞，当在非阻塞套接字上执行操作（例如，接收，发送，连接，接受...）时，脚本将不会暂停其执行，直到接收到信号或可以执行该操作为止。相反，如果该操作将导致一个块，则调用的函数将失败。
socket_set_nonblock($socket_resource);
// 创建事件基础结构
$eventBase = new EventBase();
// 我们将“监听socket”添加到事件监听中，触发条件是read，也就是说，一旦“监听socket”上有客户端来连接，就会触发这里，我们在回调函数里来处理接受到新请求后的反应

$event = new Event($eventBase, $socket_resource, Event::READ | Event::PERSIST, function ($socket_resource) {
	// 为什么写成这样比较执拗的方式？因为，“监听socket”已经被设置成了非阻塞，这种情况下，accept是立即返回的，所以，必须通过判定accept的结果是否为true来执行后面的代码。一些实现里，包括workerman在内，可能是使用@符号来压制错误，个人不太建议这样做；规范性的代码和调试代码错误异常
	if ( ($socket_conn = socket_accept($socket_resource)) != false ) {
		echo '有新客户端:' . intval($socket_conn) . PHP_EOL;
		$msg = "HTTP/1.0 200 OK\r\nContent-Length: 2\r\n\r\nHi";
		socket_write($socket_conn, $msg, strlen($msg));
		socket_close($socket_conn);
	}

}, $socket_resource);

$event->add();
$eventBase->loop();
