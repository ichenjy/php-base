<?php 

/** 

同步/异步是多个进程/线程间的业务处理，强调过程;以串行/并行的方式运行。
堵塞与非堵塞是一个进程/线程的状态，强调结果。
-堵塞&同步
-堵塞&异步，在堵塞的事务中以并行方式来处理多事情
-非堵塞&同步
-非堵塞&异步，在非堵塞的事务中以并行方式来处理多事情

###
不推荐会采用异步阻塞的IO方式去写程序．其余三种方式，更多的人都会选择同步阻塞或者异步非阻塞．同步非阻塞最大的问题在于，你需要不断在各个任务中忙碌着，导致你的大脑混乱，非常累．

*/

// 创建一个监听的socket，这是个堵塞IO的socket
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, 0, 9999);
socket_listen($sock);
socket_set_block($sock);
//socket_set_blocking();

while (true) {
	// 连接socket也是堵塞的，虽然有while，但是由于accpet是堵塞的，所以这段代码不会进入无线死循环中
	$sock_conn = socket_accept($sock);
	if ($sock_conn) {
		echo "有新的客户端" . PHP_EOL;
	} else {
		echo "客户端连接失败" . PHP_EOL;
	}
}


