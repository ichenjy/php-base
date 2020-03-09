<?php

$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error.' . PHP_EOL);
} elseif ( 0 < $pid ) {
	// 在父进程中
	// 给父进程安装一个SIGCHID信号处理器
	pcntl_signal(SIGCHLD, function() use ($pid) {
		echo "收到子进程 {$pid}，并安装信号SIGCHLD处理器" . PHP_EOL;
		pcntl_waitpid($pid, $status, WNOHANG);
	});

	// 给子进程$pid发送SIGCHLD信号
	// posix_kill($pid, SIGCHLD);

	cli_set_process_title('php father process');

	// 父进程不断while循环，去反复执行pcntl_waitpid()，从而试图解决已经退出的子进程
	while ( true ) {
		sleep(1);
		//注释掉原来老掉牙的代码，转而使用pcntl_signal_dispatch()
    	//pcntl_waitpid( $pid, $status, WNOHANG );
    	echo "循环调用安装的信号处理器进行分发...";
    	pcntl_signal_dispatch();
	}
} elseif ( 0 == $pid ) {
	// 在子进程中
	// 子进程休眠10s后直接退出
	cli_set_process_title('php child process');
	sleep(10);
	exit('chil process exit.');
}

// php里给进程安装信号处理器使用的函数是pcntl_signal()，让信号处理器跑起来的函数是pcntl_signal_dispatch()。
/** 
- pcntl_signal()，安装一个信号处理器，具体说明是pcntl_signal ( int $signo , callback $handler [, bool $restart_syscalls = true ] )，参数signo就是信号，callback则是响应该信号的代码段，返回bool值。
- pcntl_signal_dispatch()，调用每个等待信号通过pcntl_signal() 安装的处理器，参数为void，返回bool值。
*/
