<?php
namespace Home\Model;
use Think\Cache\Driver\Redis2;
use Think\Cache\Driver\Redis;
use Think\Model;

class GridModel extends model {

	/**
	 * 构造函数
     * @params $db  grid|news
	 */
	function __construct($db) {
		parent::__construct();
        $this->isNewsDB = $db == 'news';
		$this->model = new Model($db);
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
		$result = $this->model->where('`id`=' . $id)->setField("status", $status);
        //var_dump($this->model->getLastSql());
        return $result;

	}

    public function gridGetOne($id){
        $result = $this->model->where('`id`=' . $id)->find();
        //var_dump($this->model->getLastSql());
        return $result;
    }

    public function gridDeleteOne($id){
        $result = $this->model->where('`id`=' . $id)->delete();
        //var_dump($this->model->getLastSql());
        return $result;
    }

	/**
     * 显示所有宫格信息
     */
	public function gridList($page,$page_id,$status=1) {
        $field = array('id', 'page_id', 'is_banner', 'icon', 'url', 'status', 'rank');
        if($this->isNewsDB){
            $field[]='title';
            $field[]='publish_time';
            $order = '`publish_time` desc';
        }else{
            $order = '`id`,`rank` desc';
        }
		$result = $this->model->page($page, C('PER_PAGE'))->field($field)->where('`page_id`=\''. $page_id .'\' and `is_banner`=0 and `status`=' . $status)->order($order)->select();
        //var_dump($this->model->getLastSql());
        return $result;
	}

    public function gridBrand($page_id,$status=1) {
        $field = array('id', 'page_id', 'is_banner', 'icon', 'url','title', 'status', 'rank');
        $result = $this->model->field($field)->where('`page_id`=\''. $page_id .'\' and `is_banner`=1 and `status`=' . $status)->order('`id` desc')->limit(1)->find();
        //var_dump($this->model->getLastSql());
        return $result;
    }

	/**
     * 获取所有宫格总数
     */
	public function gridCount($page_id,$status=1) {
		return $this->model->where('`page_id`=\''. $page_id .'\' and `is_banner`=0 and `status`=' . $status)->count();
	}
}