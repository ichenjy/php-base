<?php

$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error.' . PHP_EOL);
} elseif ( 0 < $pid ) {
	// 在父进程中
	cli_set_process_title('php father process');
	// 父进程不断while循环，去反复执行pcntl_waitpid()
	while ( true ) {
		pcntl_waitpid($pid, $status, WNOHANG);
		sleep(1);
	}
} elseif ( 0 == $pid ) {
	// 在子进程中
	// 子进程休眠10s后直接退出
	cli_set_process_title('php child process');
	sleep(20);
	exit('chil process exit.');
}