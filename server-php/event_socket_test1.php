<?php 

$host = '0.0.0.0';
$port = 9999;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $host, $port);
socket_listen($sock);
// 将‘监听socket’设置为非堵塞模式
socket_set_nonblock($sock);

// 声明两个数组用来保存 事件 和 连接socket
$event_arr = [];
$conn_arr = [];

echo PHP_EOL . "欢迎来到ch-chat聊天室!发言注意遵守当地法律法规!" . PHP_EOL;
echo PHP_EOL . " tcp://{$host}:{$port}" . PHP_EOL;

$eventBase = new EventBase();
// 外部连接事件
$event_1 = new Event($eventBase, $sock, Event::READ | Event::PERSIST, function($sock) {
	// 使用全局的event_arr 和 conn_arr
	global $event_arr, $conn_arr, $eventBase;

	// 非阻塞模式下，注意accpet的写法会稍微特殊一些。
	if ( ($sock_conn = socket_accept($sock)) != false ) {
		echo date("Y-m-d H:i:s") . '欢迎'. intval($sock_conn) . '来到聊天室' . PHP_EOL;
		// 将连接socket也设置为非堵塞模式
		socket_set_nonblock($sock_conn);
		// 需要将连接socket保存到数组中去
		$conn_arr[intval($sock_conn)] = $sock_conn;
		// 内部读写事件
		$event_2 = new Event($eventBase, $sock_conn, Event::READ | Event::PERSIST, function($sock_conn) {
			// 全局透传到内部
			global $conn_arr;
			$buffer = socket_read($sock_conn, 65535);
			foreach ($conn_arr as $conn_key => $conn_val) {
				if ($conn_val != $sock_conn) {
					$msg = intval($sock_conn) . "说:" . $buffer;
					socket_write($conn_val, $msg, strlen($msg));
				}
			}
		}, $sock_conn);

		$event_2->add();
		// 此处值得注意，我们需要将事件本身存储到全局数组中，如果不保存，连接会话会丢失，也就是说服务端和客户端将无法保持持久会话
		$event_arr[intval($sock_conn)] = $event_2;
	}
}, $sock);

$event_1->add();
$eventBase->loop();
