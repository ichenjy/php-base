<?php 

// 管道文件绝对路径
$pipe_file = dirname(__DIR__).DIRECTORY_SEPARATOR.'test.pipe';
// 如果这个文件存在，那么使用posix_mkfifo()的时候是返回false，否则，成功返回true
if ( !file_exists($pipe_file) ) {
	if ( !posix_mkfifo($pipe_file, 666) ) {
		exit('create pipe error.' . PHP_EOL);
	}	
}

// fork出一个子进程
$pid = pcntl_fork();
if ( 0 > $pid ) {
	exit('fork error'. PHP_EOL);
} else if ( 0 == $pid ) {

	echo "111" . PHP_EOL;
	// 在子进程
	// 打开命名管道，并写入一段文本
	$file = fopen($pipe_file, 'w');
	fwrite($file, 'hello world.');
	exit();
} else if ( 0 < $pid ) {
	// 在父进程中
	// 打开命名管道，然后读取文本
	echo "222" . PHP_EOL;
	$file = fopen($pipe_file, 'r');
	echo "333" . PHP_EOL;
	// 注意此处read会被堵塞
	$content = fread($file, 1024);
	echo "444" . PHP_EOL;
	echo $content . PHP_EOL;
	// 注意此处再次堵塞，等待回收子进程，避免僵死进程
	pcntl_wait($status);
}