<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\Rbac;

/**
 * Class GridController
 * @package Home\Controller
 */
class GridController extends Controller {

    private $model;
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

        if(!session('nickname')){
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


    /**
     * 编辑
     */
    public function edit(){
        $this->model = D('Grid');
        $id = I('id', 0, 'int');

        if (IS_POST && !$id) {//执行添加操作

            $post_data = $_POST;
            unset($post_data['id']);

            //上传ICON
            $config = array(
                'maxSize'    =>    3145728,
                'rootPath'   =>    './Uploads/',// 设置附件上传根目录
                'savePath'   =>    '',// 设置附件上传（子）目录
                'saveName'   =>    array('uniqid',''),
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub'    =>    true,
                'subName'    =>    array('date','Ymd'),
            );
            $upload = new \Think\Upload($config);// 实例化上传类

            // 上传文件
            $info = $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                $post_data['icon'] = $info['icon']['savepath'] . $info['icon']['savename'];
                $update = $this->model->gridAdd($post_data);
                if ($update >= 0) {
                    $this->success('添加成功！', U('Home/Grid/gridList'));
                } else {
                    $this->error('添加失败！');
                }
            }


        }else if(IS_POST && $id){//执行修改操作

        }else{
            $this->error('非法操作！');
        }
    }
}