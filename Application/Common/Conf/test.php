<?php
/**
 * Created by PhpStorm.
 * User: Villen
 * Date: 15/5/6
 * Time: 下午1:42
 */
return array(
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    //'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_HOST'               =>  '192.168.20.18', // 服务器地址
    'DB_NAME'               =>  'test',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '.rk',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'z_',    // 数据库表前
    'DB_CHARSET' => 'utf8mb4',

    //模板
    'PAGE_TYPE' => array(
        'qiyejieshao' => array(
            'title' => '企业介绍',
            'type' => '宫格'
        ),
        'wenhua' => array(
            'title' => '企业文化',
            'type' => '宫格'
        ),
        'zeren' => array(
            'title' => '社会责任',
            'type' => '宫格'
        ),
        'huodong' => array(
            'title' => '员工活动',
            'type' => '信息流'
        ),'dongtai' => array(
            'title' => '最新动态',
            'type' => '信息流'
        ),'baodao' => array(
            'title' => '媒体报道',
            'type' => '信息流'
        ),'guancha' => array(
            'title' => '行业观察',
            'type' => '信息流'
        ),'touzi' => array(
            'title' => '投资我们',
            'type' => '宫格'
        ),'caibao' => array(
            'title' => '企业财报',
            'type' => '信息流'
        ),'gonggao' => array(
            'title' => '企业公告',
            'type' => '信息流'
        ),'yanbao' => array(
            'title' => '企业研告',
            'type' => '信息流'
        )
    )
);