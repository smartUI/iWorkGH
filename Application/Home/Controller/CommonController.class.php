<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\Rbac;

class CommonController extends Controller {

    function _initialize() {
        isLogin();
        //$this->juzi_menu = juzi_menu();

        //左侧菜单分配
        $this->sidebar = CONTROLLER_NAME.":sidebar";
        $this->controller = CONTROLLER_NAME;


        if($_SESSION){
            //查找权限节点：
        }else{
            $this->redirect('Home/Index/login');
        }

        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            //  import('@.ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {
                //检查认证识别号,没有登录的情况
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    $this->error('没有权限',$_SERVER['HTTP_REFERER'] ? : C('RBAC_ERROR_PAGE'));exit;
//                     redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    unset($_SESSION [C('USER_AUTH_KEY')]);//不然在某些情况会在，登陆页面反复跳转
                    $this->error(L('_VALID_ACCESS_'),PHP_FILE . C('USER_AUTH_GATEWAY'));
                    //$this->error(L('_VALID_ACCESS_'),__APP__ . C('USER_AUTH_GATEWAY'));
                }
            }
        }

        //菜单显示
        $m = menu();
        $this->assign('menu',$m['menu']);
        $this->assign('slider',$m['slider']);
    }

    //验证码显示
    public function verify(){
        import("ORG.Util.Image");
        Image::buildImageVerify(4,1,"png",100,28,"verify");
    }

    //判断if和else
    public function check($condition, $text='操作',$url=''){
        if($condition){
            $this->success($text.'成功！',$url);
        }else{
            $this->error($text.'失败！');
        }
    }

    /**
     * 上传文件
     **/
    public function upload(){
        $type           = I('get.type', 'default');
        $unify           = I('get.unify', 'false');
        $rootfolder = 'upload';
        $config = array(
            'maxSize'   => 1024 * 1024 * 2,
            'exts'      => array('jpg', 'gif', 'png', 'jpeg'),
            'rootPath'  => './'.$rootfolder.'/',
            'savePath'  => $type.'/',
            'autoSub'   => true,
            'subName'   => array('date','Ymd'),
            'saveName'  => array('uniqid','')
            );
        $upload = new \Think\Upload($config);// 实例化上传类
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError(), '', true);
        }else{// 上传成功
            foreach ($info as $val) {
                $key = '/'.$rootfolder.'/'.$val['savepath'].$val['savename'];
                $realpath = realpath(__ROOT__) .$key;
                if($unify == 'true')    thumbimage($realpath);
                /*if($this->qiniuupload($key, $realpath)){
                    $files[] = $key;
                }else{
                    $files[] = '';
                }*/
                $files[] = $key;
            }
            //echo(json_encode($files));
            $this->success($files, '', true);
        }
    }
    /*private function qiniuupload($key, $realpath){
        $qiniu = new \Vendor\Qiniu();
        //$realpath = realpath(__ROOT__) .$key;
        $isUpload = $qiniu->upload($key, $realpath);
        if (!$isUpload) {
            return false;
        }
        return true;
    }*/
}