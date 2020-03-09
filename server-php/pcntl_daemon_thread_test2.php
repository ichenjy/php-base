<?php 

// 由于*NIX好像并没有（如果有，请告知）可以获取父进程fork出所有的子进程的ID们的功能，所以这个需要我们自己来保存

$child_pid = [];

// 父进程安装SIGCHLD信号处理器并分发
pcntl_signal(SIGCHLD, function () {
	// 将child_pid全局化global
	global $child_pid;
	// 如果子进程的数量大于0，也就说如果还有子进程存活未退出，那么执行回收
	$child_pid_num = count($child_pid);
	if ( $child_pid_num > 0 ) {
		// 循环子进程数组
		foreach ($child_pid as $pid_key => $pid_val) {
			$wait_ret = pcntl_waitpid($pid_val, $status, WNOHANG);
			// 如果子进程被成功回收了，那么一定要将其进程ID从child_pid中移除
			if ($wait_ret == $pid_val || -1 == $wait_ret) {
				unset($child_pid[$pid_key]);
			}
		}
	}
});

// fork出5个子进程出来，并给每个子进程重命名
for ($i=0; $i < 5; $i++) { 
	$_pid = pcntl_fork();
	if ( 0 > $_pid) {
		exit('process error.');
	} elseif ( 0 == $_pid ) {
		// 重命名子进程
		cli_set_process_title('php worker process');
		// 业务代码
		// do something

		// 子进程退出执行,要exit(),不然就不会fork出5个而是多于5个任务进程了
		exit('exit process id ' . posix_getpid() . PHP_EOL);

	} elseif ( 0 < $_pid ) {
		// 将fork出任务进程的进程ID保存到数组
		// 
		$child_pid[] = $_pid;
	}
}

// 主进程继续循环不断分派信号
while ( true ) {
	pcntl_signal_dispatch();
	// 每次分发就休眠1s
	sleep(2);
}