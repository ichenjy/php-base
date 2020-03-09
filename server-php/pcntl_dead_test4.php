<?php 

$pid = pcntl_fork();
if ($pid > 0) {
	// 下面这个函数可以更改php进程的名称
	cli_set_process_title('php father process');

	// 返回值保存在 $wait_result 中
	// $pid参数表示 子进程的进程ID
	// 子进程状态则保存在参数 $status 中
	// 将第三个option参数设置为常量WNOHANG，则可以避免主进程堵塞挂起，此处父进程将立即返回继续往下执行剩下代码
	// Z+   13:19   0:00 [php] <defunct>
	// 此时子进程没有被父进程堵塞接收到，[Z+]标记就是僵尸进程
	$wait_result = pcntl_waitpid($pid, $status, WNOHANG);
	var_dump($wait_result);
	var_dump($status);

	echo "进程非阻塞，立即运行到这里".PHP_EOL;

	// 让主进程休息60s
	sleep(60);
} elseif ( 0 == $pid ) {
	cli_set_process_title('php child process');
	// 让子进程休息10秒，但该进程结束后，父进程不对子进程做任何处理工资，这个子进程就会变成僵死进程
	sleep(10);
} else {
	exit('fork error'. PHP_EOL);
}

/*
我们看到子进程是睡眠了十秒钟，而父进程在执行pcntl_waitpid()之前没有任何睡眠且本身不再阻塞，所以，主进程自己先执行下去了，而子进程在足足十秒钟后才结束，进程状态自然无法得到回收。如果我们将代码修改一下，就是在主进程的pcntl_waitpid()前睡眠15秒钟，这样就可以回收子进程了。但是即便这样修改，细心想的话还是会有个问题，那就是在子进程结束后，在父进程执行pcntl_waitpid()回收前，有五秒钟的时间差，在这个时间差内，php child process也将会是僵尸进程。那么，pcntl_waitpid()如何正确使用啊？这样用，看起来毕竟不太科学。
那么，是时候引入信号学了！
*/