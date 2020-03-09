<?php

// 设置umask(0)，当前创建的文件权限为777
umask(0);

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


// 一般服务器软件都有写配置项，比如以debug模式运行还是以daemon模式运行。如果以debug模式运行，那么标准输出和错误输出大多数都是直接输出到当前终端上，如果是daemon形式运行，那么错误输出和标准输出可能会被分别输出到两个不同的配置文件中去
// 连工作目录都是一个配置项目，通过php函数chdir可以修改当前工作目录
//chdir( $dir );