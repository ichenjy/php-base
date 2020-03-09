<?php 

$pid = pcntl_fork();
if ( $pid > 0 ) {
	// 下面这个函数可以更改php进程的名称
	cli_set_process_title('php father pid');
	// 让主进程休息60秒
	sleep(60);
} elseif ( 0 == $pid ) {
	cli_set_process_title('php child process');
	// 让子进程休息10秒，但该进程结束后，父进程不对子进程做任何处理工资，这个子进程就会变成僵死进程
	sleep(10);
} else {
	exit('fork error'. PHP_EOL);
}