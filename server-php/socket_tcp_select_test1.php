<?php 

// BEGIN 创建一个tcp socket服务器
$host = '0.0.0.0';
$port = 9999;
$socket_resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket_resource, $host, $port);
socket_set_option($socket_resource, SOL_SOCKET, SO_REUSEADDR, 1);
socket_listen($socket_resource);
// END 

// 将监听socket放入到read fd_set组中，因为select也要监听socket_resource上发生事件
$clients = [ $socket_resource ];
// 先暂时只引入读事件
$write = [];
$exp = [];

// 开始进入循环
while ( true ) {
	$read = $clients;
	// 当select监听到fd变化，注意第四个参数为null
	// 如果大于0的整数，那么表示将在规定的时间内超时
	// 如果等于0的整数，那么表示不断调用select()，执行后立即返回，然后继续
	// 如果为null，那么表示select会堵塞一直到监听发生变化，不定超时时间
	if (socket_select($read, $write, $exp, null) > 0) {
		// 判断socket_resource有没有发生变化，如果有就是客户端发送连接操作了
		if ( in_array($socket_resource, $read) ) {
			// 将客户端socket加入到client数组中
			$clients[] = $socket_client = socket_accept($socket_resource);
			// 给连接上来的客户端发送信息
			socket_write($socket_client, "no noobs, but ill make an exception :)\n".
            "There are ".(count($clients) - 1)." client(s) connected to the server\n");

            socket_getpeername($socket_client, $ip);

            echo "New client connected: {$ip} \n";

			// 然后将socket_resource从read除去掉
			$key = array_search($socket_resource, $read);
			unset($read[$key]);
		}

		// 除去socket_resource中是否存在连接的socket_client
		if ( count($read) > 0 ) {

			foreach ($read as $socket_rd) {
				// 遍历从可读取的fd中读取出来数据内容，主动式的
				$content = socket_read($socket_rd, 1024, PHP_NORMAL_READ);

				// 检测客户端断开连接，是否消息过来
				if ($content === false) {
					$key = array_search($socket_rd, $clients);
					unset($clients[$key]);
					echo "client disconnect.\n";
					continue;
				}

				if ($content) {
					// 循环clients数组，将内容发送给其余所有客户端
					foreach ($clients as $socket_sd) {
						// clients数组中可能包含了socket_resource以及当前发给自己的socket，所以要排除二者
						if ($socket_sd != $socket_resource && $socket_sd != $socket_rd) {
							socket_write($socket_sd, $content, strlen($content));
						}
					}
				}
			}
		}
	} else {
		// 当select没有监听到可操作fd的时候，直接continue进入下一次循环
		continue ;
	}
}

socket_close($socket_resource);