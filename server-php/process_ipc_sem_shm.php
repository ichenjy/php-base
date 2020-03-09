<?php 

// Semaphore, Shared Memory and IPC
// sem*是信号量相关函数，shm*是共享内存相关函数。
/*

// 创建信号量唯一标识符
$ftok = ftok(__FILE__, 'a');

// 创建信号量资源ID
$sem_resouce_id = sem_get($ftok);

// 授权信号量
sem_acqure($sem_resource_id);

// 释放信号量
sem_release($sem_resource_id);

// 销毁信号量
sem_remove($sem_resource_id);


=====================Shared Memory========================

//创建一个共享内存，大小为1024字节，权限为755
$shm_id = shm_attach($key, 1024, 0755);

//将一个key=>value放进共享内存
shm_put_var($shm_id, $share_key, $message1);

//重复使用key ,前一个设置的值会被后一个设置的值覆盖掉。
shm_put_var($shm_id, $share_key, $message2);

//读取一个共享内存值
$read_message = shm_get_var($shm_id, $share_key);

//判断共享内存中，某个值是否存在
$isexists = shm_has_var($shm_id, $share_key);

//删除一个值
shm_remove_var($shm_id, $share_key);

//删除一个共享内存
shm_remove($shm_id);

//关闭共享内存的连接
shm_detach($shm_id);

*/

// 创建共享内存区域
$shm_key = ftok( __FILE__, 'b' );
$shm_id = shm_attach( $shm_key, 1024, 0666 );

// 加入信号量
$sem_key = ftok( __FILE__, 'c' );
$signal = sem_get( $sem_key );

const SHM_VAR = 1;
$child_pid = [];
// for 2 child process
for ($i=0; $i < 2; $i++) { 
	$pid = pcntl_fork();
	if ( 0 > $pid ) {
		exit('fork fail ' . PHP_EOL);
	} else if ( 0 == $pid ) {
		// 获取锁
		sem_acquire($signal);

		if ( shm_has_var($shm_id, SHM_VAR) ) {
			$counter = shm_get_var($shm_id, SHM_VAR);
			$counter++;
			shm_put_var($shm_id, SHM_VAR, $counter);
		} else {
			$counter = 1;
			shm_put_var($shm_id, SHM_VAR, $counter);
		}

		// 释放锁
		sem_release($signal);
		exit('child process ' . posix_getpid() . ' end' . PHP_EOL);
	} else if ( 0 < $pid ) {
		$child_pid[] = $pid;
	}
}

while ( !empty($child_pid) ) {
	foreach ($child_pid as $pid_key => $pid_val) {
		$pid_ret = pcntl_waitpid($pid_val, $status, WNOHANG);
		if ($pid_ret == $pid_val || $pid_ret == -1 ) {
			unset($child_pid[$pid_key]);
		}
	}
}

// 休眠2s，2个子进程都执行完毕
sleep(2);
echo '最终结果' . shm_get_var($shm_id, SHM_VAR);

// 销毁信号量
sem_remove($signal);

// 记得删除共享内存数据，删除共享内存是有顺序的，先remove后detach，顺序反过来php可能会报错
shm_remove($shm_id);
shm_detach($shm_id);