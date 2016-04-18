<?php
namespace Vendor;

require_once(dirname(__FILE__) . "/Qiniu/qiniu.php");

class Qiniu_RS_PutPolicy
{
    public $Scope;                  //必填
    public $Expires;                //默认为3600s
    public $CallbackUrl;
    public $CallbackBody;
    public $ReturnUrl;
    public $ReturnBody;
    public $AsyncOps;
    public $EndUser;
    public $InsertOnly;             //若非0，则任何情况下无法覆盖上传
    public $DetectMime;             //若非0，则服务端根据内容自动确定MimeType
    public $FsizeLimit;
    public $SaveKey;
    public $PersistentOps;
    public $PersistentPipeline;
    public $PersistentNotifyUrl;
    public $FopTimeout;
    public $MimeLimit;

    public function __construct($scope)
    {
        $this->Scope = $scope;
    }

    public function Token($mac) // => $token
    {
        $deadline = $this->Expires;
        if ($deadline == 0) {
            $deadline = 3600;
        }
        $deadline += time();

        $policy = array('scope' => $this->Scope, 'deadline' => $deadline);
        if (!empty($this->CallbackUrl)) {
            $policy['callbackUrl'] = $this->CallbackUrl;
        }
        if (!empty($this->CallbackBody)) {
            $policy['callbackBody'] = $this->CallbackBody;
        }
        if (!empty($this->ReturnUrl)) {
            $policy['returnUrl'] = $this->ReturnUrl;
        }
        if (!empty($this->ReturnBody)) {
            $policy['returnBody'] = $this->ReturnBody;
        }
        if (!empty($this->AsyncOps)) {
            $policy['asyncOps'] = $this->AsyncOps;
        }
        if (!empty($this->EndUser)) {
            $policy['endUser'] = $this->EndUser;
        }
        if (!empty($this->InsertOnly)) {
            $policy['exclusive'] = $this->InsertOnly;
        }
        if (!empty($this->DetectMime)) {
            $policy['detectMime'] = $this->DetectMime;
        }
        if (!empty($this->FsizeLimit)) {
            $policy['fsizeLimit'] = $this->FsizeLimit;
        }
        if (!empty($this->SaveKey)) {
            $policy['saveKey'] = $this->SaveKey;
        }
        if (!empty($this->PersistentOps)) {
            $policy['persistentOps'] = $this->PersistentOps;
        }
        if (!empty($this->PersistentPipeline)) {
            $policy['persistentPipeline'] = $this->PersistentPipeline;
        }
        if (!empty($this->PersistentNotifyUrl)) {
            $policy['persistentNotifyUrl'] = $this->PersistentNotifyUrl;
        }
        if (!empty($this->FopTimeout)) {
            $policy['fopTimeout'] = $this->FopTimeout;
        }
        if (!empty($this->MimeLimit)) {
            $policy['mimeLimit'] = $this->MimeLimit;
        }


        $b = json_encode($policy);
        return Qiniu_SignWithData($mac, $b);
    }
}
class Qiniu_PutExtra
{
    public $Params = null;
    public $MimeType = null;
    public $Crc32 = 0;
    public $CheckCrc = 0;
}

class Qiniu{
    const BUCKET = "images11";
    const ACCESS_KEY = 'XwqBAtp5etrb_-U_9WbzYfKoMtZyXJLwGadD_bYC';
    const SECRET_KEY = 'zK1TWarZwNQbXPJO--Ci4_S_jZWCWRUbfguU6rlO';
    public function __construct() {
        Qiniu_SetKeys(self::ACCESS_KEY, self::SECRET_KEY);
    }

    public function upload($key, $localfile) {
        //$key1 = "uploads/".date('Y-m-d')."/tag/". time() .".jpg";
        $putPolicy = new Qiniu_RS_PutPolicy(self::BUCKET);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key, $localfile, $putExtra);
        if ($err !== null) {
           return $err;
        } else {
            return true;
        }
    }

    public function upload_gif2mp4($key, $localfile) {
        //$key1 = "uploads/".date('Y-m-d')."/tag/". time() .".jpg";
        $putPolicy = new Qiniu_RS_PutPolicy(self::BUCKET);
        $putPolicy->PersistentOps = 'avthumb/mp4/ab/128k/ar/44100/acodec/libfaac/r/30/vb/1200k/vcodec/libx264/s/854x480/autoscale/1/stripmeta/0';//video-Generic-480P-16:9
        $putPolicy->PersistentPipeline = 'gif2mp4';
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key, $localfile, $putExtra);
        if ($err !== null) {
            return false;
        } else {
            return $ret;
        }
    }

    public function get_token($bucket){
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
        return $putPolicy->Token(null);
    }
}

