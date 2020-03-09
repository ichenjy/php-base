<?php 

$host = '0.0.0.0';
$port = 9999;
// 创建一个tcp socket
$socket_resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// 将socket 绑定到host:port上面
socket_bind($socket_resource, $host, $port);
// 开始监听socket
socket_listen($socket_resource);
// 进入while循环，不用担心死循环死机，因为程序将会堵塞在下面的socket_accept()函数上
while ( true ) {
	// 此处将堵塞住,一直到有客户端来连接服务端。堵塞状态的进程是不会占据cpu的资源
	$socket_conn = socket_accept($socket_resource);
	// 当accpet了新的客户端连接后，就fork出一个子进程来专门处理
	$pid = pcntl_fork();
	// 在子进程中处理当前连接的请求业务
	if ( 0 == $pid ) {
		// 向客户端发送一个消息
		$msg = "hello world\n\n";
		socket_write($socket_conn, $msg, strlen($msg));

		// 休眠10秒钟，在来观察时候可以同时为多个客户端服务
		echo time() . " : a new client run into " . PHP_EOL;
		sleep(10);
		socket_close($socket_conn);

		// 退出当前进程
		exit('current process exit, pid ' . posix_getpid());
	}
}

socket_close($socket_resource);
