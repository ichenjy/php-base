<?php 

$host = '0.0.0.0';
$port = 9999;
// 创建一个tcp socket
$socket_resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// 将socket 绑定到host:port上面
socket_bind($socket_resource, $host, $port);
// 开始监听socket
socket_listen($socket_resource);
// 重命名当前主进程
cli_set_process_title('phpserver master process');
// 按照数量fork出固定个数的子进程
for ($i=0; $i <= 10; $i++) { 
	$pid = pcntl_fork();
	if ( 0 == $pid ) {
		cli_set_process_title('phpserver worker process');
		while ( true ) {
			$socket_conn = socket_accept( $socket_resource );
      		$msg = "helloworld\r\n";
      		socket_write( $socket_conn, $msg, strlen( $msg ) );
      		socket_close( $socket_conn );
		}
	}
}

// 主进程不可以退出，代码演示比较粗暴，为了不保证退出直接走while循环，休眠1s，开启的子进程还没有退出在运行
// 实际上，主进程真正该做的应该是收集子进程pid，监控各个子进程的状态等等
// master <- worker [work_task]
while ( true ) {
	sleep(1);
}

socket_close($socket_resource);