<?php 

// 查看当前系统平台支持的IO多路复用的方法都有哪些？
$method = Event::getSupportedMethods();
print_r( $method );

// 查看当前用的方法是哪一个
$eventBase = new EventBase();
echo "当前event的方法是:" . $eventBase->getMethod() . PHP_EOL;
// 初始化event配置信息
$eventConfig = new EventConfig();
// libevent supports /dev/poll, Mac kqueue(2), event ports, POSIX select(2), Windows select(), poll(2), and epoll(4)
// mac <- kqueue | select | poll
// *nix <- epoll | poll | select 
// window <- I/O Completion Ports (Windows) | select
// event配置中过滤掉使用的方法
$eventConfig->avoidMethod('epoll');
// 利用config初始化eventbase
$eventBase = new EventBase($eventConfig);
echo "当前event的方法是：" . $eventBase->getMethod() . PHP_EOL;
