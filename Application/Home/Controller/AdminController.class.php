<?php
namespace Home\Controller;
use Home\Controller\CommonController;
use Think\Controller;

class AdminController extends CommonController {

	public function __construct() {
		parent::__construct();
	}

	//显示管理员列表
	public function index() {

		//dump($_SESSION['node']);
		$this->adminList();
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
				$this->success('管理员修改成功！', U('Home/Index/index'));
			} else {
				$this->error('修改失败！');
			}

		} else if ($_GET) {
			//查询原始数据
			$id = I('admin_id', 0, 'int');
			if ($id) {
                $where = "`id`='1'";
                $result = M('Admin')->where($where)->find();
				$this->assign('admin', $result);
			}
			$this->assign('admin_id', session('admin_id'));
			$this->display();
		}
	}

	//显示所有管理员
	public function adminList() {
		$p = I('get.p', 1, 'int');
		$status = I('get.status');
		$author = I('get.author', '', 'string');
		$roleid = I('get.roleid');

		$search['admin.status'] = $status;
		$search['admin.nickname'] = $author;
		$search['role.id'] = $roleid;

		foreach ($search as $p_i => $p_v) {
			if ($p_v != '') {
				//$search[$p_i] = urldecode($p_v);
				$this->assign(explode('.', $p_i)[1], $p_v);
				if ($p_i == 'admin.nickname') {
					$search[$p_i] = array('LIKE', '%' . $p_v . '%');
				} else {
					$search[$p_i] = array('eq', $p_v);
				}

			} else {
				unset($search[$p_i]);
			}
		}

		$model = D('Admin');

		$total = $model->adminCount($search);
		$page = new \Think\Page($total, C('PER_PAGE')); // 实例化分页类 传入总记录数和每页显示的记录数(20)
		$show = $page->show();

		$admin = $model->adminList($p, $search);
		foreach ($admin as $i => $a) {
			$admin[$i]['last_ip'] = long2ip($a['last_ip']);
		}
		$rolelist = M('role')->where('status=1')->select();

		$this->assign('rolelist', $rolelist);
		$this->assign('roleid', $roleid);
		$this->assign('admin', $admin);
		$this->assign('status', $status);
		$this->assign('show_page', $show);
		$this->display();
	}

	//管理员信息
	public function adminDetail() {
		$id = I('get.id', 0, 'int');
		$model = new \Home\Model\AdminModel();
		$field = array("admin.admin_id,admin.portrait, admin.name, admin.nickname,admin.app_user, admin.email,admin.description,admin.status, role.id,role.name");
		$table = array('juzi_admin' => 'admin', 'juzi_role_admin' => 'admin_role', 'juzi_role' => 'role');
		$result = $model->table($table)->field($field)->where('admin.admin_id=' . $id . ' AND admin_role.admin_id=' . $id . ' AND role.id=admin_role.role_id')->find();
		if (session('nickname')) {
			$this->assign('admin', $result);
			$this->display();
		} else {
			$this->redirect('Home/Index/login');
		}
	}

	//删除管理员
	public function adminDel() {
		$id = I('get.admin_id', 0, 'int');
		if ($id && D('Admin')->adminDel($id)) {
			$this->success('管理员删除成功！', U('Home/Admin/adminList'));
		} else {
			$this->error('删除失败！');
		}
	}

	//给管理员添加操作节点权限（暂时无用）
	public function adminColumn() {
		$admin_id = I('get.admin_id', 0, 'int');
		$admin_name = I('get.name', 0);

		if ($admin_id && $admin_name) {
			$nodes = M('Node')->order('sort')->where('level>1')->field()->select();
			$this->assign('node', node_merge($nodes));
			$this->assign('admin_id', $admin_id);
			$this->assign('admin_name', $admin_name);
			$this->display();
		}
	}

	//取消通行，修改status
	public function adminStatus() {
		$id = I('get.admin_id', 0, 'int');
		$status = I('get.status', 0, 'int');
		$tip = $status == 1 ? '解冻' : '冻结';
		if ($id && D('Admin')->adminStatus($id, $status)) {
			$this->success('管理员' . $tip . '成功！');
		} else {
			$this->error($tip . '失败！');
		}
	}
	
	
	//修改管理员信息
	public function qrCode() {
	    $this->model = D('Admin');
	    //执行修改操作
	    if (IS_POST) {
	        $post_data = $_POST;
	        $admin_id = I('get.id', 0, 'int');
	
	        //上传二维码
	        $portrait = uploadImg('upload/qrcode');
	        $data['qrcode'] = $portrait;
	        $update = $this->model->adminSave($admin_id, $data);
	
	        //修改管理员
	        if ($update >= 0) {
	            $this->success('二维码修改成功！', U('Home/Admin/adminList'));
	        } else {
	            $this->error('修改失败！');
	        }
	
    	} else if ($_GET) {
        	    //查询原始数据
        	$id = I('admin_id', 0, 'int');
        	if ($id) {
    				$field = array("admin_id,qrcode");
    				$result = M()->table('juzi_admin')->field($field)->where('admin_id=' . $id)->find();
    				$this->assign('admin', $result);
        	}
        	$this->display();
    	}
    }
	

}