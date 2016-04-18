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

	//ueditor编辑器
	public function ueditor() {
		$data = new \Org\Util\Ueditor();
		echo $data->output();
	}

	//添加管理员（显示表单和处理）
	public function adminAdd() {
		if (IS_POST) {
			$admin = array(
				'name' => I('post.name', '', 'string'),
				'nickname' => I('post.nickname', '', 'string'),
				'app_user' => I('post.app_user', '', 'string'),
				'asname' => I('post.asname', '', 'string'),
				'email' => I('email', '', 'string'),
				'password' => md5('123456'),
				'add_time' => date('Y-m-d H:i:s'),
				'last_time' => date('Y-m-d H:i:s'),
				'description' => I('post.description', '', 'string'),
				'category_id' =>I('post.category_id'),
			);
			//插入到数据库
			if ($id = D('Admin')->adminAdd($admin)) {
				$role = array(
					'role_id' => I('role_id'),
					'admin_id' => $id,
				);
				//添加角色关系
				if (M('role_admin')->add($role)) {
					$this->success('添加成功！', U('Home/Admin/adminList'));
				}
			} else {
				$this->error('添加失败！');
			}

		} else {
			//角色分配，先查询所有角色
			$this->roles = M('Role')->select();
			$this->categorys = M('Category')->where('pid = 0 and id != 93')->field('name, id')->select();
			$this->display();

		}
	}

	//修改管理员信息
	public function adminEdit() {
		$this->model = D('Admin');
		//执行修改操作
		if (IS_POST) {
			$post_data = $_POST;
			$pwd = $post_data['password'];
			$admin_id = I('get.id', 0, 'int');

			//上传头像
			$portrait = uploadImg('portrait');
			$portrait == '' ? '' : $post_data['portrait'] = $portrait;

			//密码判断
			if ($pwd == '') {
				unset($post_data['password']);
			} else {
				$post_data['password'] = md5($pwd);
			}
			//拆分数据-role_admin
			$role_admin['role_id'] = $post_data['role_id'];
			//拆分数据－admin
			unset($post_data['role_id']);

			//修改角色
			M('role_admin')->where(array('admin_id' => $admin_id))->save($role_admin);

			$update = $this->model->adminSave($admin_id, $post_data);

			//修改管理员
			if ($update >= 0) {
				$this->success('管理员修改成功！', U('Home/Admin/adminList'));
			} else {
				$this->error('修改失败！');
			}

		} else if ($_GET) {
			//如果是首页面开始的修改，重置侧栏加载地址
			/*if(isset($_GET['type'])) {
			$this->assign('sidebar', 'Public:sidebar');
			//$this->sidebar = "Public:sidebar";
			}*/

			//显示修改的表单
			//查询角色
			$this->assign('roles', M('Role')->select());
			$this->assign('categorys',M('Category')->where('pid = 0 and id != 93')->field('name, id')->select());
			//查询原始数据
			$id = I('admin_id', 0, 'int');
			if ($id) {
				//$this->admin = $this->model->where(array('admin_id'=>$id))->find();
				$field = array("admin.admin_id,admin.portrait, admin.name, admin.nickname,admin.asname,admin.app_user, admin.email,admin.description,admin.status,admin.category_id, role.role_id");
				$table = array('juzi_admin' => 'admin', 'juzi_role_admin' => 'role');
				$result = M()->table($table)->field($field)->where('admin.admin_id=' . $id . ' AND role.admin_id=' . $id)->find();
				$this->assign('admin', $result);
				//dump($result);die;
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