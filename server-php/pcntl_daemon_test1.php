<?php

$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error.');
} elseif ( 0 < $pid ) {
	// 主进程退出
	exit();
}

// 子进程继续执行

// 最关键一步，执行setsid()设置会话ID
if ( !posix_setsid() ) {
	exit('setsid error.');
}

// 理论上一次fork就可以了
// 但是，二次fork，这里的历史渊源是这样的：在基于system V的系统中，通过再次fork，父进程退出，子进程继续，保证形成的daemon进程绝对不会成为会话首进程，不会拥有控制终端。

$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error');
} elseif ( 0 < $pid ) {
	// 主进程退出
	exit;
}

// 子进程继续
// 变成daemon的守护进程，在*nix后台运行
cli_set_process_title('daemon child process');

for ($i=0; $i <= 1000; $i++) {
	sleep(1);
	echo 'test' . $i . PHP_EOL; 
}

