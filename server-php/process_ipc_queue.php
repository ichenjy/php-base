<?php 

// 使用ftok创建一个键名，注意这个函数的第二个参数‘需要一个字符的字符串’
$key = ftok(__DIR__, 'a');
// 然后使用msg_get_queue创建一个消息队列
$queue = msg_get_queue($key, 0666);
// 使用msg_stat_queue函数可以查看这个消息队列的信息，而使用msg_set_queue函数则可以修改这些信息
$_queue_stats = msg_stat_queue( $queue );

print_r($_queue_stats);

// fork进程
$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error' . PHP_EOL);
} else if ( 0 < $pid ) {
	// 在父进程中
	// 使用msg_receive()函数获取消息
	msg_receive( $queue, 0, $msgtype, 1024, $message );
	echo $message . PHP_EOL;
	// 使用完记得清除删除消息队列
	msg_remove_queue( $queue );
	pcntl_wait($status);
} else if ( 0 == $pid ) {
	// 在子进程中
	// 向消息队列中写入消息
	// 使用msg_send()向消息队列中写入消息，参考文档
	msg_send($queue, 1, 'hello world - 2020');
	exit();
}