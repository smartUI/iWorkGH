<?php
namespace Home\Model;
use Think\Cache\Driver\Redis2;
use Think\Cache\Driver\Redis;
use Think\Model;

class AdminModel extends model {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
		$this->model = M('admin');
	}

	//添加管理员
	public function adminAdd($data) {
		$add = $this->model->add($data);
		logW('添加新的管理员', $this->model->getLastSql(), 'action');
		//更新管理员redis
		self::adminUpdateRedis($add);
		return $add;
	}

	//删除管理员
	public function adminDel($id) {
		//删除管理员信息
		$this->model->delete($id);
		//删除权限关系
		$where = array('admin_id' => $id);
		M('role_admin')->where($where)->delete();
		logW('删除一名管理员', $this->model->getLastSql() . '---' . M('role_admin')->getLastSql());
		return true;

	}

	//修改管理员status
	public function adminStatus($id, $status) {
		//修改管理员信息
		$result = $this->model->where("admin_id = " . $id)->setField("status", $status);
		logW('管理员状态操作', $this->model->getLastSql());
		//更新管理员redis
		self::adminUpdateRedis($id);
		return true;

	}

	//显示所有管理员
	public function adminList($page, $status) {
		$stat = $status != '' ? ' AND admin.status=' . $status : '';
		$file = array('admin.admin_id', 'admin.name', 'admin.nickname', 'admin.email', 'admin.status', 'admin.last_time', 'admin.last_ip', 'admin.app_user', 'role.name as role', 'role.remark');
		$result = M()->table(array('juzi_admin' => 'admin', 'juzi_role' => 'role', 'juzi_role_admin' => 'radmin'))->page($page, C('PER_PAGE'))->field($file)->where('role.id=radmin.role_id AND admin.admin_id=radmin.admin_id AND admin.source!=3')->where($status)->order('admin.admin_id')->select();
		return $result;
	}

	//获取所有总数
	public function adminCount($status) {
		$stat = $status != '' ? ' AND admin.status=' . $status : '';
		return M()->table(array('juzi_admin' => 'admin', 'juzi_role' => 'role', 'juzi_role_admin' => 'radmin'))->where('role.id=radmin.role_id AND admin.admin_id=radmin.admin_id AND admin.source!=3')->where($status)->count();
	}

	//修改信息
	public function adminSave($id, $data) {
		$modify = $this->model->where(array('admin_id' => $id))->save($data);
		//var_dump($this->model->getLastSql());
		logW('修改一名管理员', $this->model->getLastSql());
		//更新管理员redis
		self::adminUpdateRedis($id);
		return true;
	}

	//初始化  管理员列表
	static function adminDataRedis() {
		$data = M('Admin')->field('admin_id,name,asname,nickname,description,portrait,status,app_user,source,email,qrcode')->select();
		$redis = new Redis();
		$redis2 = new Redis2();
		foreach ($data as $v) {
			$admin = array(
				'n' => $v['nickname'],
				'rn' => $v['name'],
				'an' => $v['asname'],
				's' => $v['source'],
				'con' => $v['description'],
				'p' => $v['portrait'],
                'qr' => $data['qrcode'],
			);
            if($v['source']>0){
                $admin['e'] = $v['email'];
                $admin['st'] = $v['status'];
            }
			//$redis->srem('app_user',$v['app_user']);
			$redis2->del('au#' . $v['admin_id']);
			//重新写入
			//$redis->sadd('app_user',$v['app_user']);
			$redis2->hmset('au#' . $v['admin_id'], $admin);

		}
		logW('管理员数据初始化', '$redis2->hmset');
		return true;
	}

	//修改管理员信息
	static function adminUpdateRedis($id) {
		//au#编辑id	hash	n:名称,con:描述,p:头像
		$data = M('Admin')->field('name,nickname,asname,description,portrait,status,app_user,source,email,qrcode')->where('admin_id=' . $id)->find();
		$redis = new Redis();
		$redis2 = new Redis2();
		$admin = array(
			'n' => $data['nickname'],
			'rn' => $data['name'],
			'an' => $data['asname'],
			's' => $data['source'],
			'con' => $data['description'],
			'p' => $data['portrait'],
		    'qr' => $data['qrcode'],
		);
        if($data['source']>0){
            $admin['e'] = $data['email'];
            $admin['st'] = $data['status'];
        }
        if($data['status']=='1'){
            $redis->sadd('app_user', $data['app_user'],false);
        }else{
            $redis->srem('app_user', $data['app_user'],false);
        }
		$redis2->hmset('au#' . $id, $admin);

		return true;
	}

	//KOL
	//显示所有kol
	public function kolList($page, $status) {
		$stat = $status != '' ? ' AND admin.status=' . $status : '';
		$file = array('admin.admin_id', 'admin.name', 'admin.nickname', 'admin.email', 'admin.status', 'admin.portrait');
		$result = M()->table(array('juzi_admin' => 'admin'))->page($page, C('PER_layout.htmlPAGE'))->field($file)->where('admin.source=3')->where($status)->order('admin.admin_id desc')->select();
		return $result;
	}

	//获取所有总数
	public function kolCount($status) {
		$stat = $status != '' ? ' AND admin.status=' . $status : '';
		return M()->table(array('juzi_admin' => 'admin'))->where('admin.source=3')->where($status)->count();
	}

	//获取一个kol详情
	public function kolDetail($id) {
		$field = array("admin.admin_id,admin.portrait, admin.name, admin.nickname,admin.asname, admin.email,admin.description, admin.status, admin.url,cat.category");
		$table = array('juzi_admin' => 'admin', 'juzi_kol_category' => 'cat');
		$result = M()->table($table)->field($field)->where('admin.admin_id=cat.kol_id AND admin.admin_id=' . $id)->find();
		return $result;
	}

	//KOL GIF
	//显示所有kol GIF
	public function kolGifList($page) {
		$file = array('admin.admin_id', 'admin.name', 'admin.nickname', 'admin.email', 'admin.status', 'admin.portrait');
		$result = M()->table(array('juzi_admin' => 'admin'))->page($page, C('PER_layout.htmlPAGE'))->field($file)->where('admin.source=4 and status=1')->order('admin.admin_id desc')->select();
		return $result;
	}
	
	//获取所有总数 KOL GIF
	public function kolGifCount($status) {
		return M()->table(array('juzi_admin' => 'admin'))->where('admin.source=4 and status=1')->count();
	}
	
	//获取一个kol详情
	public function kolGifDetail($id) {
		$field = array("admin.admin_id,admin.portrait, admin.name, admin.nickname,admin.asname, admin.email,admin.description, admin.status, admin.url");
		$table = array('juzi_admin' => 'admin');
		$result = M()->table($table)->field($field)->where('admin.admin_id=' . $id)->find();
		return $result;
	}
}