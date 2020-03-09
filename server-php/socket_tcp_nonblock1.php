<?php 

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, 0, 9999);
socket_listen($sock);
// 设置非堵塞的运行模式
socket_set_nonblock($sock);

while ( true ) {
	// 连接socket也是非堵塞的
	$sock_conn = socket_accept($sock);
	if ($sock_conn) {
		echo "有新的客户端" . PHP_EOL;
	} else {
		echo "客户端连接失败" . PHP_EOL;
	}
}