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

        $this->model = new \Home\Model\GridModel();

        $this->pageModel = I('get.pageModel','','string');//页面模板文件名字
        $this->pageModelInfo = C('PAGE_TYPE');


        if( !isset( $this->pageModelInfo[ $this->pageModel ] ) ){
            //$this->error('模板ID错误');
        }else{
            $this->assign('pageModel' , $this->pageModel);
            $this->assign('pageModelInfo',$this->pageModelInfo[ $this->pageModel ]);
        }

    }

    /**
     * PAGE－宫格列表
     */
    public function index(){
        $this->gridList();
    }

    /**
     * PAGE－宫格列表
     */
    public function gridList(){
        $offset = I('get.page',1,'int');
        $list = $this->model->gridList($offset,$this->pageModel);
        $this->assign('list',$list);

        $this->buildPage($this->pageModel,$list);
        $this->display();
    }

    /**
     * 返回表格数据结构
     * @return array
     */
    private function getGridFromData(){
        return array(
            'page_id' => I('post.page_id','','htmlspecialchars'),
            'icon' => I('post.icon','','htmlspecialchars'),
            'url' => I('post.url','','htmlspecialchars'),
            'rank' => I('post.rank',1,'int'),
            'status' => I('post.status',1,'int'),
            'is_banner' => I('post.is_banner',0,'int'),
        );
    }

    /**
     * ACTION－添加宫格
     */
    public function subEdit(){
        $id = I('get.id', 0, 'int');
        if (IS_POST && !$id) {//执行添加操作
            $this->upload(function($upload,$info){
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功

                    $data = $this->getGridFromData();
                    $data['icon'] = $info['icon']['savepath'] . $info['icon']['savename'];

                    $update = $this->model->gridAdd($data);
                    if ($update >= 0) {
                        $this->success('添加成功！', U(cookie('grid_list_url')));
                    } else {
                        $this->error('添加失败！');
                    }
                }
            });

        }else if (IS_POST && $id){//修改
            $data = $this->getGridFromData();

            if( $_FILES['icon']['error'] ===0 && $_FILES['icon']['size'] > 0 ){
                $this->upload(function($upload,$info){
                    if(!$info) {// 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{// 上传成功
                        $data['icon'] = $info['icon']['savepath'] . $info['icon']['savename'];
                        $update = $this->model->gridSave( I('get.id', 0, 'int'), $data );
                        if ($update) {
                            $this->success('修改成功！', U(cookie('grid_list_url')));
                        } else {
                            $this->error('修改失败！');
                        }
                    }
                });

            }else{
                $data['icon'] = I('post.pre_icon','','htmlspecialchars');
                $res = $this->model->gridSave($id,$data);
                if ($res) {
                    $this->success('修改成功！', U(cookie('grid_list_url')));
                } else {
                    $this->error('修改失败！');
                }
            }
        }else{
            $this->error('非法操作！');
        }
    }


    /**
     * PAGE－编辑
     */
    public function edit(){
        $id = I('id', '', 'int');
        $detail = $this->model->gridGetOne($id);
        $this->assign('grid',$detail);
        cookie('grid_list_url',$_SERVER['HTTP_REFERER']);
        $this->display();
    }

    /**
     * ACTION－删除
     */
    public function delete(){
        $id = I('id', '', 'int');
        $res = $this->model->gridDeleteOne($id);
        if ($res) {
            $this->success('删除成功！', U(cookie('grid_list_url')));
        } else {
            $this->error('删除失败！');
        }
    }

    public function banner(){

        $id = I('id',0,'int');
        $type = I('get.type','','string');
        if( $type == 'set' ){

            $res = $this->model->gridDeleteOne($id);
            if ($res) {
                $this->success('删除成功！', U(cookie('grid_list_url')));
            } else {
                $this->error('删除失败！');
            }

        }elseif( $type=='cancel' ){
            $res = $this->model->gridDeleteOne($id);
            if ($res) {
                $this->success('删除成功！', U(cookie('grid_list_url')));
            } else {
                $this->error('删除失败！');
            }
        }else{
            $this->error('操作失败！');
        }
    }


    private function buildPage($page_id,$data,$mod='gongge'){
        //layout(false);
        $this->assign('data',$data);
        $this->buildHtml($page_id.'.html','Html/',$mod.'_model');
    }

    /**
     * 上传文件封装
     * @param $callback
     * @return array|bool
     */
    private function upload($callback){
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
        $callback($upload,$info);
        return $info;
    }
}