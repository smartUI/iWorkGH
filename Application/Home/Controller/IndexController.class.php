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
        if(!session('nickname'))
        {
            $this->redirect('/Home/Index/login');
        }
    }

    public function index(){

        $issetpasswd = I('get.issetpasswd',0,'int');
        $nickname=I('session.nickname','','string');
        $id=I('session.admin_id',0,'int');

        $model = new \Home\Model\AdminModel();
        $field = array("admin.admin_id,admin.portrait, admin.name, admin.nickname,admin.app_user, admin.email,admin.description,admin.status, role.id,role.name");
        $table = array('juzi_admin'=>'admin','juzi_role_admin'=>'admin_role','juzi_role'=>'role');
        $result = $model->table($table)->field($field)->where('admin.admin_id='.$id.' AND admin_role.admin_id='.$id.' AND role.id=admin_role.role_id')->find();
        //var_dump($model->getLastSql());
        $this->assign('admin',$result);
        $this->assign('issetpasswd', $issetpasswd);
        $this->display();

    }

    public function setpasswd(){
        $inputpasswd   = I('post.inputpasswd','','string');
        $inputrepasswd = I('post.inputrepasswd','','string');
        $issetpasswd = I('post.issetpasswd',0,"int");
        $id            = I('session.admin_id',0,'int');
        $md5inputpasswd = md5($inputpasswd);

        $str = '';
        if ($issetpasswd>0){
            $str = '&issetpasswd='.$issetpasswd;
        }

        if($inputpasswd != $inputrepasswd){
                $this->error('密码不一致', U('Home/Index/index', $str));
        }

        $result = M('Admin')->where(array('admin_id' => $id, 'issetpasswd' => array('gt', 0)))->find();
        
        if($result){
            if($result['password'] == $md5inputpasswd){
                $this->error('新密码和原密码是一样的', U('Home/Index/index', $str));
            }

            $update = D('Admin')->adminSave($id, array('password' => $md5inputpasswd, 'issetpasswd'=>0));
            if ($update > 0) {
                $this->success('管理员修改成功！', U('Home/Index/index'));
            } else {
                $this->error('修改失败！', U('Home/Index/index', $str));
            }
        }else{
                $this->error('非法操作，请联系管理员', U('Home/Index/index', $str));
        }
    }

    public function login(){
        if(IS_POST){
            //$where = array('nickname'=>I('post.nickname',0,'string'), 'password'=>substr(md5(I('post.password',0,'string')),15),'status'=>'1');
            $nickname = trim(I('post.nickname','','string'));
            $password = md5(I('post.password','','string'));
            $where = "`status`='1' AND `nickname`='{$nickname}'  AND `password`='{$password}'";
            $result = M('Admin')->where($where)->find();

            if($result){
                $str = '';
                if(isset($result['issetpasswd']) && $result['issetpasswd'] > 0){//跳转到第一次重设密码页面
                    $str = '&issetpasswd='.$result['issetpasswd'];
                }
                //如果是超级管理员，就查询所有
                /*if(I('nickname')==C('RBAC_SUPERADMIN')){
                    $node_msg = M('Node')->field('id,name,title,pid')->select();
                }else{
                    //查找功能节点－id
                    $nodes = M()->table(array('juzi_access'=>'access', 'juzi_role_admin'=>'radmin'))->field('access.node_id')->where('access.role_id=radmin.role_id AND  radmin.admin_id=1')->select();
                    foreach($nodes as $id){
                        $ids[] = $id['node_id'];
                    }
                    $in['id'] = array('in',$ids);
                    //查找功能节点－名称
                    $node_msg = M('Node')->where($in)->field('id,name,title,pid')->select();
                }*/

                //session设置
                session('admin_id',$result['id']);
                session('nickname' ,$result['nickname']);
                //session('last_ip',long2ip($result['last_ip']));
                //session('now_ip',get_client_ip());
                //session('author',$result['admin_id']);

                //$_SESSION['menu'] =node_merge($node_msg); //查找权限节点
                //$_SESSION['_ACCESS_LIST'] = '';


                /*if($result['nickname']==C('RBAC_SUPERADMIN')){
                    //$_SESSION[C('ADMIN_AUTH_KEY')] =  true;
                    session(C('ADMIN_AUTH_KEY'),true);
                }*/
                //RBAC::saveAccessList();

                //RBAC::getAccessList('10') ;

                //更新最新登录IP和时间信息
                //$data['last_time'] = date('Y-m-d H:i:s');
                //$data['last_ip'] = ip2long(get_client_ip());
                //M('Admin')->where($where)->save($data);

                //日志纪录
                //$this->load_model('logs_model')->add_content('退出系统');
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
        $this->redirect('/Home/Index/login');
    }

    //修改个人信息
    public function adminEdit(){
        isLogin();
        if(IS_POST){

            $post_data = $_POST;
            $pwd = $post_data['password'];

           //上传头像
            $p = uploadImg('portrait');
            $p ? $post_data['portrait']=$p : '';

            //密码判断
            if($pwd==''){
                unset($post_data['password']);
            }else {
                $post_data['password'] = md5($pwd);
            }

            $update = D('admin')->adminSave(session('admin_id'),$post_data);
            //修改管理员
            if($update){
                $this->success('管理员修改成功！',U('Home/Index/index'));
            }else{
                $this->error('修改失败！');
            }
        }else{

            $this->admin = M('Admin')->where(array('admin_id'=>$_SESSION['admin_id']))->find();

            $this->display();
        }

    }

    //活动数据
    public function activity(){
        layout(false);
        $id = I('get.id',0,'int');
        //判断是否有活动
        $file_url = 'http://'.$_SERVER['SERVER_NAME'].'/Application/Home/View/Index/act'.$id.'.html';
        if(!$id || file_get_contents($file_url)==''){
            $this->display('act');
            die;
        }
        //$time = M('Activity')->field('end_time')->find($id);
        //$allow = strtotime($time['end_time']);
        $this->assign('allow','false');
        $this->assign('id',$id);
        $this->assign('url','http://'.$_SERVER['SERVER_NAME'].'/Home/Index/activity_do');
        $this->display('act'.$id);
    }

    public function activity_do(){
        $model = M('ActivityResult');
        $data['a_id'] = I('get.a_id',0,'int');
        $data['name'] = I('get.name','','string');
        $data['tel'] = I('get.tel','','string');
        $data['address'] = I('get.address','','string');

        try{
            $model->add($data);
            echo 1;
        }catch (\Exception $e){
            echo 0;
        }
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