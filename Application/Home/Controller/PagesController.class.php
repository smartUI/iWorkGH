<?php
namespace Home\Controller;
use Home\Controller\CommonController;
use Think\Controller;

class PagesController extends CommonController {

	public function __construct() {
		parent::__construct();
        if( !session('nickname') ){
            $this->redirect('/Home/Index/login');
        }
	}

	//显示管理员列表
	public function index() {

		//dump($_SESSION['node']);
		$this->pagesList();
	}

	//显示所有管理员
	public function pagesList() {
        $this->display('pagesList');
	}

}