<?php
return array(
    //'配置项'=>'配置值'
    'URL_MODEL'          => '2', //URL模式
    'SESSION_AUTO_START' => true, //是否开启session
    //'DEFAULT_MODULE'     => 'Admin', // 默认模块
    'URL_PARAMS_BIND'    => true, // URL变量绑定到操作方法作为参数
    //模板
    'LAYOUT_ON'          => true,
    'LAYOUT_NAME'        => 'layout',
    //U方法访问路径，伪静态，去掉后面的html
    'URL_HTML_SUFFIX'    => '',
    //每页
    'PER_PAGE'           => '20',
    'URL_CASE_INSENSITIVE' => true,
    
);