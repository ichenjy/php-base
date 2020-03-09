<?php
/**
 * PHP多进程和多线程的处理
 */

//创建socket监听
$socketserv = stream_socket_server('tcp://0.0.0.0:8000', $errno, $errstr);
//创建5个子进程
for ($i = 0; $i < 5; $i++) {
    //使用pcntl_fork()创建进程，会返回pid，如果pid==0，则表示主进程
    if (pcntl_fork() == 0) {
        //循环监听
        while (true) {
            $conn = stream_socket_accept($socketserv);
            //如果监听失败，则重新去监听
            if(!$conn){
                continue;
            }
            //读取流信息，读取的大小 是9000
            $request = fread($conn, 9000);
            //写入响应
            $response = 'hello';
            fwrite($conn, $response);
            //关闭流
            fclose($conn);
        }
        //创建完所有的子进程，然后退出
        exit(0);
    }
}