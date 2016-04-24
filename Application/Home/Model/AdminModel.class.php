<?php
namespace Home\Model;
use Think\Model;

class AdminModel extends model {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
		$this->model = M('Admin');
	}

	//修改信息
	public function adminSave($id, $data) {
		$modify = $this->model->where(array('id' => $id))->save($data);
		return true;
	}

}