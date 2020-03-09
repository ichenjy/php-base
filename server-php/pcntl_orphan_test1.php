<?php 

$pid = pcntl_fork();
if ($pid > 0) {
	// 显示父进程进程ID，函数可以用getmypid()，也可以用posix
	echo "Father PID" . getmypid() . PHP_EOL;
	// 让父进程停止2秒，在这2秒内，子进程的父进程还是它
	sleep(2);
} elseif ($pid == 0) {
	// 让子进程循环10次，每次睡1s，然后每秒获取下子进程的父进程的ID
	for ($i=0; $i < 10; $i++) { 
		sleep(1);
		// posix_getppid()函数用于获取当前进程的父进程进程ID
		echo posix_getppid() . PHP_EOL;
	}
} else {
	echo "fork error." . PHP_EOL;
}