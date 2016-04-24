<?php
namespace Home\Controller;
use Home\Controller\CommonController;
use Think\Controller;

class AdminController extends CommonController {

	public function __construct() {
		parent::__construct();
	}

	//修改管理员信息
	public function adminEdit() {
		$this->model = D('Admin');
		//执行修改操作
		if (IS_POST) {
			$post_data = $_POST;
			$pwd = $post_data['password'];
			$pwd2 = $post_data['password2'];
			$admin_id = I('get.id', 0, 'int');

            if($pwd!=$pwd2){
                $this->error('密码不一致！');
                die;
            }
			//密码判断
			if ($pwd == '') {
				unset($post_data['password']);
				unset($post_data['password2']);
			} else {
				$post_data['password'] = md5($pwd);
			}

			$update = $this->model->adminSave($admin_id, $post_data);

			//修改管理员
			if ($update >= 0) {
                session('nickname' ,$post_data['nickname']);
				$this->success('管理员修改成功！', U('Home/Index/index'));
			} else {
				$this->error('修改失败！');
			}

		} else if ($_GET) {
			//查询原始数据
			$id = I('admin_id', 0, 'int');
			if ($id) {
                $where = "`id`=".$id;
                $result = M('Admin')->where($where)->find();
				$this->assign('admin', $result);
			}
			$this->assign('admin_id', session('admin_id'));
			$this->display();
		}
	}

}