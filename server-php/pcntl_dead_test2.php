<?php 

$pid = pcntl_fork();
if ( $pid > 0 ) {
	// 下面这个函数可以更改php进程的名称
	cli_set_process_title('php father process');

	// 返回$wait_result，就是子进程的进程号，如果子进程已经是僵死进程则为0
	// 子进程状态则保存在$status参数中，通过pnctl_wexitstatus()等一系列函数来查看$status的状态信息是什么
	$wait_result = pcntl_wait($status);
	print_r($status);
	print_r($wait_result);

	// 让主进程休息60s
	sleep(60);

} elseif ( 0 == $pid ) {
	cli_set_process_title('php child process');
	// 让子进程休息10秒，但该进程结束后，父进程不对子进程做任何处理工资，这个子进程就会变成僵死进程
	sleep(10);
} else {
	exit('fork error'. PHP_EOL);
}