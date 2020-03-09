<?php 

// 初始化一个EventConfig，用于演示的空配置
$eventConfig = new EventConfig();
// 根据EventConfig初始化一个EventBase
$eventBase = new EventBase( $eventConfig );
// 初始化一个定时器
$timer = new Event($eventBase, -1, Event::TIMEOUT | Event::PERSIST, function () use ( &$args ) {
	echo microtime( true ) . " 歼15，起飞！" . PHP_EOL;
}, $args = func_get_args());

// tick时间刻度为1s，我们还可以改成1秒钟甚至0.0001秒，也就是毫秒级定时器
$tick = 0.1;
// 将定时器event添加
$timer->add( $tick );
// eventBase进入loop状态
$eventBase->loop();

/*

public Event::__construct ( EventBase $base , mixed $fd , int $what , callable $cb [, mixed $arg = NULL ] )

第一个参数是一个eventBase对象即可
第二个参数是文件描述符，可以是一个监听socket、一个连接socket、一个fopen打开的文件或者stream流等。如果是时钟时间，则传入-1。如果是其他信号事件，用相应的信号常量即可，比如SIGHUP、SIGTERM等等
第三个参数表示事件类型，依次是Event::READ、Event::WRITE、Event::SIGNAL、Event::TIMEOUT。其中，加上Event::PERSIST则表示是持久发生，而不是只发生一次就再也没反应了。比如Event::READ | Event::PERSIST就表示某个文件描述第一次可读的时候发生一次，后面如果又可读就绪了那么还会继续发生一次。
第四个参数就熟悉的很了，就是事件回调了，意思就是当某个事件发生后那么应该具体做什么相应
第五个参数是自定义数据，这个数据会传递给第四个参数的回调函数，回调函数中可以用这个数据。

--------------------------------------------------------

1.创建EventConfig（非必需）
2.创建EventBase
3.创建Event
4.将Event挂起，也就是执行了Event对象的add方法，不执行add方法那么这个event对象就无法挂起，也就不会执行
5.将EventBase执行进入循环中，也就是loop方法

*/