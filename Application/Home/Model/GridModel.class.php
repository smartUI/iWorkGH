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
		$this->model = new Model('grid');
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
		$result = $this->model->where('`is_banner`=0 and `id`=' . $id)->setField("status", $status);
        //var_dump($this->model->getLastSql());
        return $result;

	}

    public function gridGetOne($id){
        $result = $this->model->where('`is_banner`=0 and `id`=' . $id)->find();
        //var_dump($this->model->getLastSql());
        return $result;
    }

    public function gridDeleteOne($id){
        $result = $this->model->where('`is_banner`=0 and `id`=' . $id)->delete();
        //var_dump($this->model->getLastSql());
        return $result;
    }

	/**
     * 显示所有宫格信息
     */
	public function gridList($page,$page_id,$status=1) {
        $field = array('id', 'page_id', 'is_banner', 'icon', 'url', 'status', 'rank');
		$result = $this->model->page($page, C('PER_PAGE'))->field($field)->where('`page_id`=\''. $page_id .'\' and `is_banner`=0 and `status`=' . $status)->order('id,rank')->select();
        //var_dump($this->model->getLastSql());
        return $result;
	}

	/**
     * 获取所有宫格总数
     */
	public function gridCount($status) {
		return $this->model->where('`is_banner`=0 and status=' . $status)->count();
	}
}