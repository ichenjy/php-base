<?php 

/*

LT: 水平触发模式，这种方式下，如果监听到了有X个事件发生，那么内核态会将这些事件拷贝到用户态，但是可惜的是，如果用户只处理了其中一件，剩余的X-1件出于某种原因并没有理会，那么下次的时候，这些未处理完的X-1个事件依然会从内核态拷贝到用户态。如:select,poll采用轮询方式
ET: 边缘触发模式，这种情况下，如果发生了X个事件，然而你只处理了其中1个事件，那么剩余的X-1个事件就算“丢失”了。性能是上去了，与之俱来的就是可能的事件丢失。如:epoll采用回调方式

*/

// event配置中过滤掉使用的方法
$eventConfig = new EventConfig();
//$eventConfig->avoidMethod('epoll'); select|poll|epoll|kqueue
$eventConfig->requireFeatures(EventConfig::FEATURE_ET);
$eventConfig->requireFeatures(EventConfig::FEATURE_FDS);

$eventBase = new EventBase($eventConfig);
$features = $eventBase->getFeatures();

echo "特性:" . PHP_EOL;
// 位运算
if ( $features & EventConfig::FEATURE_ET ) {
	echo '边缘触发' . PHP_EOL;
}
if ( $features & EventConfig::FEATURE_O1 ) {
	echo '01添加删除事件' . PHP_EOL;
}
if ( $features & EventConfig::FEATURE_FDS ) {
	echo '任意文件描述符，socket句柄也是文件描述符中一种' . PHP_EOL;
}