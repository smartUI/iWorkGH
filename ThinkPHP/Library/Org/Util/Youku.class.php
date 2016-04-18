<?php
namespace Org\Util;
class Youku {

    const USER_AGENT = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36";
    const REFERER = "http://www.youku.com";
    const FORM_ENCODE = "GBK";
    const TO_ENCODE = "UTF-8";
    private static $base = "http://play.youku.com/play/get.json?ct=12&vid=";
    private static $source = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/\\:._-1234567890";
    private static $sz = '-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1';
    private static $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    public static function parse($url){
        preg_match("#id\_([\w=]+)#", $url, $matches); //id里可以有=号
        if (empty($matches)){
            $html = self::_cget($url);
            preg_match("#videoId2\s*=\s*\'(\w+)\'#", $html, $matches);
            if(!$matches) return false;
        }
        //根据you vid 获取相应的视频地址
//         return self::_getYouku(trim($matches[1]));
        return self::_getYouku2(trim($matches[1]));
    }
    /**
     * [_cget curl获取数据]
     * @param  [type]  $url     [url地址]
     * @param  boolean $convert [是否转换编码]
     * @param  integer $timeout [超时时间]
     * @return [type]           [description]
     */
    public static function _cget($url,$convert=false,$timeout=10){
        $cookie_file = dirname(__FILE__).'/cookie.txt';
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($ch,CURLOPT_USERAGENT,self::USER_AGENT);
        curl_setopt($ch,CURLOPT_REFERER,self::REFERER);       
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); //跟随301跳转
        curl_setopt($ch,CURLOPT_AUTOREFERER,1); //自动设置referer   
        curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file);
        $res=curl_exec($ch);
        curl_close($ch);
        if($convert){
            $res=mb_convert_encoding($res,self::TO_ENCODE,self::FORM_ENCODE);
        }
        return $res;
    }    

    //start 获得优酷视频需要用到的方法
    private static function getSid(){
        $sid = time().(mt_rand(0,9000)+10000);
        return $sid;
    }

    private static function getKey($key1,$key2){
        $a = hexdec($key1);
        $b = $a ^0xA55AA5A5;
        $b = dechex($b);
        return $key2.$b;
    }

    private static function getFileid($fileId,$seed){
        $mixed = self::getMixString($seed);
        $ids = explode("*",rtrim($fileId,'*')); //去掉末尾的*号分割为数组
        $realId = "";
        for ($i=0;$i<count($ids);$i++){
            $idx = $ids[$i];
            $realId .= substr($mixed,$idx,1);
        }  
        return $realId;
    } 

    private static function getMixString($seed){
        $mixed = "";
        $source = self::$source;
        $len = strlen($source);
        for($i=0;$i<$len;$i++){
            $seed = ($seed * 211 + 30031)%65536;
            $index = ($seed / 65536 * strlen($source));
            $c = substr($source,$index,1);
            $mixed .= $c;
            $source = str_replace($c,"",$source);
        }
        return $mixed;
    }

    private static function yk_d($a){
        if (!$a) {
            return '';
        }
        $f = strlen($a);
        $b = 0;
        $str = self::$str;
        for ($c = ''; $b < $f;) {
            $e = self::charCodeAt($a, $b++) & 255;
            if ($b == $f) {
                $c .= self::charAt($str, $e >> 2);
                $c .= self::charAt($str, ($e & 3) << 4);
                $c .= '==';
                break;
            }
            $g = self::charCodeAt($a, $b++);
            if ($b == $f) {
                $c .= self::charAt($str, $e >> 2);
                $c .= self::charAt($str, ($e & 3) << 4 | ($g & 240) >> 4);
                $c .= self::charAt($str, ($g & 15) << 2);
                $c .= '=';
                break;
            }
            $h = self::charCodeAt($a, $b++);
            $c .= self::charAt($str, $e >> 2);
            $c .= self::charAt($str, ($e & 3) << 4 | ($g & 240) >> 4);
            $c .= self::charAt($str, ($g & 15) << 2 | ($h & 192) >> 6);
            $c .= self::charAt($str, $h & 63);
        }
        return $c;
    }
    private static function yk_na($a){
        if (!$a) {
            return '';
        }

        $h = explode(',', self::$sz);
        $i = strlen($a);
        $f = 0;
        for ($e = ''; $f < $i;) {
            do {
                $c = $h[self::charCodeAt($a, $f++) & 255];
            } while ($f < $i && -1 == $c);
            if (-1 == $c) {
                break;
            }
            do {
                $b = $h[self::charCodeAt($a, $f++) & 255];
            } while ($f < $i && -1 == $b);
            if (-1 == $b) {
                break;
            }
            $e .= self::fromCharCode($c << 2 | ($b & 48) >> 4);
            do {
                $c = self::charCodeAt($a, $f++) & 255;
                if (61 == $c) {
                    return $e;
                }
                $c = $h[$c];
            } while ($f < $i && -1 == $c);
            if (-1 == $c) {
                break;
            }
            $e .= self::fromCharCode(($b & 15) << 4 | ($c & 60) >> 2);
            do {
                $b = self::charCodeAt($a, $f++) & 255;
                if (61 == $b) {
                    return $e;
                }
                $b = $h[$b];
            } while ($f < $i && -1 == $b);
            if (-1 == $b) {
                break;
            }
            $e .= self::fromCharCode(($c & 3) << 6 | $b);
        }
        return $e;
    }
    private static function yk_e($a, $c){
        for ($f = 0, $i, $e = '', $h = 0; 256 > $h; $h++) {
            $b[$h] = $h;
        }
        for ($h = 0; 256 > $h; $h++) {
            $f = (($f + $b[$h]) + self::charCodeAt($a, $h % strlen($a))) % 256;
            $i = $b[$h];
            $b[$h] = $b[$f];
            $b[$f] = $i;
        }
        for ($q = ($f = ($h = 0)); $q < strlen($c); $q++) {
            $h = ($h + 1) % 256;
            $f = ($f + $b[$h]) % 256;
            $i = $b[$h];
            $b[$h] = $b[$f];
            $b[$f] = $i;
            $e .= self::fromCharCode(self::charCodeAt($c, $q) ^ $b[($b[$h] + $b[$f]) % 256]);
        }
        return $e;
    }
    
    private static function fromCharCode($codes){
        if (is_scalar($codes)) {
            $codes = func_get_args();
        }
        $str = '';
        foreach ($codes as $code) {
            $str .= chr($code);
        }
        return $str;
    }
    private static function charCodeAt($str, $index){
        static $charCode = array();
        $key = md5($str);
        $index = $index + 1;
        if (isset($charCode[$key])) {
            return $charCode[$key][$index];
        }
        $charCode[$key] = unpack('C*', $str);
        return $charCode[$key][$index];
    }

    private static function charAt($str, $index = 0){
        return substr($str, $index, 1);
    }

    
    /**
     * 2015-12-01
     */
    public static function _getYouku2($vid){
        $blink = self::$base.$vid;
        $info = self::_cget($blink);
        if ($info) {
            $rs = json_decode($info, true);
            if(empty($rs['data']['stream'])){
                return false;  //有错误返回false
            }

            $ip = $rs['data']['security']['ip'];
            $ep = $rs['data']['security']['encrypt_string'];
            list($sid, $token) = explode('_', self::yk_e('becaf9be', self::yk_na($ep)));
            foreach ($rs['data']['stream'] as $streamtype) {
                if ($streamtype['stream_type'] != 'mp4hd') {
                    continue;
                }
                $fileType = 'mp4';
                $ep = urlencode(self::yk_d(self::yk_e('bf7e5f01', $sid . '_' . $vid . '_' . $token)));
                $addr = "http://pl.youku.com/playlist/m3u8?ctype=12&ep={$ep}&ev=1&keyframe=1&oip={$ip}&sid={$sid}&token={$token}&type={$fileType}&vid={$vid}";
                $cookie_file = dirname(__FILE__).'/cookie.txt';
                $ch = curl_init($addr);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies
                $response = curl_exec($ch);
                curl_close($ch);
                if (preg_match("#http://.*\.mp4#", $response, $match) ) {
                    return array('img' => $rs['data']['video']['logo'], 'mp4' => $match);
                }
            }
            return false;
        } else {
            return false;
        }
    }
}