<?php 

// 给当前php进程安装一个alert信号处理器
// 当进程收到alert时钟信号后会做出动作
pcntl_signal(SIGALRM, function () {
	echo "tick." . PHP_EOL;
});

// 定义一个时钟间隔时间1s
$tick = 1;
while (true) {
	// 当过了tick时间后，向进程发送一个alert信号
	pcntl_alarm( $tick );
	// 分发信号，唤起安装好的各种信号处理器
	pcntl_signal_dispatch();
	// 睡个1s，继续
	sleep( $tick );
}
