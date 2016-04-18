<?php
namespace Vendor;

class Huanxin {
    
    const HOST = "https://a1.easemob.com/happyjuzi/caochang";
    const FROM_UID = "156129"; //槽厂小喇叭
    const CLIENT_ID = "YXA6fx054JbgEeSjVt0J0jppYg";
    const CLIENT_SECRET = "YXA65Luk69VsjZHXius5iqTjcIUHhV0";
    
    public static  function curlPost($url, $data, $timeout = 30, $headers = array()) {
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        if (!empty($headers)) {
            $headerArr = array();
            foreach( $headers as $n => $v ) {
                $headerArr[] = $n .':' . $v;
            }
            curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr );
        }
        curl_setopt ($ch, CURLOPT_URL , $url );
        curl_setopt ($ch, CURLOPT_POST , 1 );
        curl_setopt ($ch, CURLOPT_HEADER , 0 );
        curl_setopt ($ch, CURLOPT_POSTFIELDS , $data[0] );
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt ($ch, CURLOPT_TIMEOUT , $timeout );
        if ($ssl) {
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST , 2 );
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER , false );
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    public static  function sendMessage($text, $toUids) {
        $headers['Authorization'] = 'Bearer ' . self::getToken();
        $headers['Content-Type'] = "application/json";
        $data = self::curlPost(self::HOST . '/messages', array('{"target_type" : "users","target" : ["' .join('","', $toUids). '"],"msg" : {"type" : "txt","msg" : "' . $text . '"},"from" : "'. self::FROM_UID .'" }'), 30, $headers);
        var_dump($data);
    }
    
    public static function getToken() {
        //如果不存在文件则新建文件
        if (file_exists("token.txt")) {
            $result = file_get_contents("token.txt");
            $info = json_decode($result, true);
            if ($info['expires_at'] > time() + 600) {
                return $info['access_token'];
            }
        }
        $data = array('{"grant_type":"client_credentials","client_id":"' . self::CLIENT_ID . '","client_secret":"' . self::CLIENT_SECRET . '"}');
        $result = self::curlPost("https://a1.easemob.com/happyjuzi/caochang/token", $data);
        if (!empty($result)) {
            $info = json_decode($result, true);
            if (json_last_error() == JSON_ERROR_NONE)
                $info['expires_at'] = $info['expires_in'] + time();
                file_put_contents("token.txt", json_encode($info));
            }
            return $info['access_token'];
    }
    
}

