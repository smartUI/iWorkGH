<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Think\Cache\Driver;

use Think\Cache;

defined('THINK_PATH') or exit();

/**
 * Redis缓存驱动 
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 * @category   Think
 * @package  Cache
 * @subpackage  Driver
 * @author guizhiming <sd2536888@163.com>
 */
class Localredis  {

    static $instance = NULL;
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public static  function getInstance($options = array()) {
        if (empty(self::$instance)) {
            $redis = new \Redis();
            $redis->connect(C("TASK_REDIS_HOST"), C("TASK_REDIS_PORT"), C("TASK_REDIS_TIMEOUT"));
            $auth = C("TASK_REDIS_AUTH");
            if (!empty($auth)) {
                $redis->auth($auth);
            }
            self::$instance = $redis;
        }
        return self::$instance;
    }

}
