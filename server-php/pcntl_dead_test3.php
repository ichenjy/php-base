<?php 

$pid = pcntl_fork();
if ($pid > 0) {
	// 下面这个函数可以更改php进程的名称
	cli_set_process_title('php father process');

	// 返回值保存在 $wait_result 中
	// $pid参数表示 子进程的进程ID
	// 子进程状态则保存在参数 $status 中
	// 将第三个option参数设置为常量WNOHANG，则可以避免主进程堵塞挂起，此处父进程将立即返回继续往下执行剩下代码
	$wait_result = pcntl_waitpid($pid, $status);
	var_dump($wait_result);
	var_dump($status);

	echo "进程阻塞，运行到这里没有".PHP_EOL;

	// 让主进程休息60s
	sleep(60);
} elseif ( 0 == $pid ) {
	cli_set_process_title('php child process');
	// 让子进程休息10秒，但该进程结束后，父进程不对子进程做任何处理工资，这个子进程就会变成僵死进程
	sleep(10);
} else {
	exit('fork error'. PHP_EOL);
}