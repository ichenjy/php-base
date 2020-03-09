<?php 

// 初始化一个EventConfig，用于演示的空配置
$eventConfig = new EventConfig();
// 根据EventConfig初始化一个EventBase
$eventBase = new EventBase( $eventConfig );
// 初始化event，把核心eventBase装入
$event = new Event($eventBase, SIGTERM, Event::SIGNAL | Event::PERSIST, function () {
	echo 'signal term.' . PHP_EOL;
});

// 挂起event对象
$event->add();
// 进入循环
echo "进入循环" . PHP_EOL;
$eventBase->loop();


