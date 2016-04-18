<?php
namespace Home\Model;
use Think\Cache\Driver\Redis2;
use Think\Cache\Driver\Redis;
use Think\Model;

class GridModel extends model {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
		$this->model = M('grid');
	}

    /**
     * 添加宫格信息
     */
	public function gridAdd($data) {
		$add = $this->model->add($data);
        //var_dump($this->model->getLastSql());
        return $add;
	}

    /**
     * 修改信息
     */
    public function gridSave($id, $data) {
        $modify = $this->model->where(array('id' => $id))->save($data);
        //var_dump($this->model->getLastSql());
        return $modify;
    }


	/**
     * 修改宫格信息
     */
	public function gridDisplayStatus($id, $status) {
		//更新是否展示
		$result = $this->model->where('id = ' . $id)->setField("status", $status);
        //var_dump($this->model->getLastSql());
        return $result;

	}

	/**
     * 显示所有宫格信息
     */
	public function gridList($page,$status) {
        $field = array('grid.id', 'grid.title', 'grid.icon', 'grid.url', 'grid.status', 'grid.desc');
		$result = $this->model->page($page, C('PER_PAGE'))->field($field)->where('grid.status=' . $status)->order('grid.desc')->select();
        //var_dump($this->model->getLastSql());
        return $result;
	}

	/**
     * 获取所有总数
     */
	public function gridCount($status) {
		return $this->model->where('grid.status=' . $status)->count();
	}
}