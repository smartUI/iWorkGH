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
class Redis4 extends Cache {

    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = array()) {
        if (!extension_loaded('redis')) {
            E(L('_NOT_SUPPERT_') . ':redis');
        }
        if (empty($options)) {
            $options = array(
                'host' => C('DATA_REDIS_HOST4') ? C('DATA_REDIS_HOST4') : '127.0.0.1',
                'port' => C('DATA_REDIS_PORT4') ? C('DATA_REDIS_PORT4') : 6379,
                'timeout' => C('DATA_CACHE_TIME4') ? C('DATA_CACHE_TIME4') : false,
                'persistent' => C('DATA_PERSISTENT4') ? C('DATA_PERSISTENT4') : false,
                'auth' => C('DATA_REDIS_AUTH4') ? C('DATA_REDIS_AUTH4') : false,
            );
        }
        $options['host'] = explode(',', $options['host']);
        $options['port'] = explode(',', $options['port']);
        $options['auth'] = explode(',', $options['auth']);
        foreach ($options['host'] as $key => $value) {
            if (!isset($options['port'][$key])) {
                $options['port'][$key] = $options['port'][0];
            }
            if (!isset($options['auth'][$key])) {
                $options['auth'][$key] = $options['auth'][0];
            }
        }
        $this->options = $options;
        $this->options['expire'] = isset($options['expire']) ? $options['expire'] : C('DATA_EXPIRE4');
        $this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : C('DATA_CACHE_PREFIX4');
        $this->options['length'] = isset($options['length']) ? $options['length'] : 0;
        $this->handler = new \Redis;
    }

    /**
     * 连接Redis服务端
     * @access public
     * @param bool $is_master : 是否连接主服务器
     */
    public function connect($is_master = true, $options = array()) {
        if ($is_master) {
            $i = 0;
        } else {
            $count = count($this->options['host']);
            if ($count == 1) {
                $i = 0;
            } else {
                $i = rand(1, $count - 1); //多个从服务器随机选择
            }
        }
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';
        $this->options['timeout'] === false ?
            $this->handler->$func($this->options['host'][$i], $this->options['port'][$i]) :
            $this->handler->$func($this->options['host'][$i], $this->options['port'][$i], $this->options['timeout']);
        if ($this->options['auth'][$i]) {
            $this->handler->auth($this->options['auth'][$i]);
        }
    }

    public function rpush($name,$value){
        self::connect(true);
        $this->handler->rPush($name,$value);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name, $isPrefix =FALSE) {
        self::connect(false);
        N('cache_read', 1);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->get($key);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function del($name, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->delete($key);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }
    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null, $isPrefix = FALSE) {
        self::connect(true);
        N('cache_write', 1);
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        //$name = $this->options['prefix'] . $name;
        $name = $isPrefix ? $this->options['prefix'] . $name : $name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if (is_int($expire) && $expire > 0) {
            $result = $this->handler->setex($name, $expire, $value);
        } else {
            $result = $this->handler->set($name, $value);
        }
        if ($result && $this->options['length'] > 0) {
            // 记录缓存队列
            $this->queue($name);
        }
        return $result;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        self::connect(true);
        return $this->handler->delete($this->options['prefix'] . $name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        self::connect(true);
        return $this->handler->flushDB();
    }

    /**
     * 修改hash类型单个值
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function hincrby($name, $field, $num, $isPrefix = FALSE) {
        self::connect(true);
        //N('cache_write', 1);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->hincrby($key, $field, $num);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * hash设置key中的field的值
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function hset($name, $field, $val, $isPrefix = FALSE) {
        self::connect(true);
        //N('cache_write', 1);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->hset($key, $field, $val);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    public function hmset($key, $array,$time=''){
        self::connect(true);
        $this->handler->hmset($key, $array);
        if($time!=''){
            $this->handler->expire($key,$time*24*60*60);
        }
        return true;
    }

    //获取最热里面的文章ID
    public function hmget($name, $array, $isPrefix = FALSE){
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return $this->handler->hmget($key, $array);
    }
    //获取最热里面的一篇文章
    public function zrange($name, $start, $end, $showScore, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return  $this->handler->zrange($key, $start, $end, $showScore);
    }

    public function hget($name, $field, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return  $this->handler->hget($key, $field);
    }

    public function hdel($name, $field, $isPrefix = FALSE) {
        self::connect(true);
        //N('cache_write', 1);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return $this->handler->hdel($key, $field);
    }

    /**
     * set添加key中的值
     * @access public
     * @param string $name 缓存变量名
     * @param string $val 缓存值
     * @return mixed
     */
    public function sadd($name, $val, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->sadd($key, $val);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * zset添加key中的值
     * @access public
     * @param string $name 缓存变量名
     * @param string $key 缓存值
     * @param string $score 分数
     * @return mixed
     */
    public function zadd($name, $member, $score, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return  $this->handler->zadd($key, $score, $member);
    }

    /**
     * zset删除key中的值
     * @access public
     * @param string $name 缓存变量名
     * @param string $key 缓存值
     * @return mixed
     */
    public function zrem($name, $member, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->zrem($key, $member);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }
    public function zremrangebyscore($name, $member, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return  $this->handler->zremrangebyscore($key, $member, $member);
    }

    /**
     * zset查找key中的值
     * @access public
     * @param string $name 缓存变量名
     * @param string $key 缓存值
     *
     * @return mixed
     */
    public function zscore($name, $member, $isPrefix = FALSE) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $value = $this->handler->zscore($key, $member);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }


    /**
     * set删除key中的值
     * @access public
     * @param string $name 缓存变量名
     * @param string $val 缓存值
     * @return mixed
     */
    public function srem($name, $val) {
        self::connect(true);
        $value = $this->handler->srem($this->options['prefix'] . $name, $val);
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    public function ping() {
        self::connect(true);
        $value = $this->handler->ping();
        $jsonData = json_decode($value, true);
        return ($jsonData === NULL) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /*
    * 设置时间
    */
    public function expire($name,$time, $isPrefix = FALSE){
        self::connect(true);
        //$key = $isPrefix ? $this->options['prefix'] . $name : $name;
        $key = $name;
        return $this->handler->expire($key,$time*24*60*60);
    }

    /**
     * 关闭长连接
     * @access public
     */
    public function __destruct() {
        if ($this->options['persistent'] == 'pconnect') {
            $this->handler->close();
        }
    }

    //list删除一个key
    public function lrem($name,$value, $isPrefix=false){
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return $this->handler->lRem($key,$value);
    }
    //list push
    public function lpush($name,$value, $isPrefix=false){
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return $this->handler->lPush($key,$value);
    }

    public function exists($name, $isPrefix = false) {
        self::connect(true);
        $key = $isPrefix ? $this->options['prefix'] . $name : $name;
        return $this->handler->exists($key);
    }

    public function reName($key, $newkey) {
        self::connect(true);
        return $this->handler->rename($key, $newkey);
    }

}
