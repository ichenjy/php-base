<?php
	
$number = 1;
$pid = pcntl_fork();
if ( 0 == $pid) {
	$number += 2;
	echo "子进程，number+2: { $number } " . PHP_EOL;
} elseif ( $pid > 0 ) {
	$number += 1;
	echo "父进程，number+1: { $number }" . PHP_EOL;
	echo "父进程，number+1: { $number }" . PHP_EOL;
	echo "父进程，number+1: { $number }" . PHP_EOL;
	echo "父进程，number+1: { $number }" . PHP_EOL;

} else {
	echo "fork失败" . PHP_EOL;
}