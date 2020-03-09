<?php 

// msg protocol
// | ---- dataLen ---- | data |
// | - fixed 2bytes  - |

// 模拟客户端连续发送2条消息
$foo = "hello world";
$bar = "i am sqrt_cat";

$package = "";

// 使用 n 打包 固定2bytes
$fooLenn = pack("n", strlen($foo));
$package = $fooLenn . $foo;

$barLenn = pack("n", strlen($bar));
$package .= $barLenn . $bar;

// 解析第1条消息 取前 2bytes 按 n 解包
$fooLen = unpack("n", substr($package, 0, 2))[1];
// 使用包消息体长度定义读取消息体
// 从第 3byte 开始读 前 2bytes表示长度
$foo = substr($package, 2, $fooLen);
echo $foo . PHP_EOL;


// 解析第2条消息 取前 2bytes 按 n 解包
// 0 ~ (2 + fooLen) - 1 字节序为 fooLen . foo
// (2 + fooLen) ~ (2 + fooLen) + 2 - 1 为 barLen
$barLen = unpack("n", substr($package, (2 + $fooLen), 2))[1];
$bar    = substr($package, (2 + $fooLen) + 2, $barLen);
echo $bar . PHP_EOL;

