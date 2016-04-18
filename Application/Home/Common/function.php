<?php
/**
 * Created by PhpStorm.
 * User: Villen
 * Date: 15/4/2
 * Time: 上午00:50
 */

/*递归重组节点信息为多维数组
 * @param type $node 要处理的节点数组
 * @param $pid 父级ID
 *
 * */

function node_merge($node, $access = null, $pid = 0) {
	$arr = array();

	foreach ($node as $v) {
		if (is_array($access)) {
			$v['access'] = in_array($v['id'], $access) ? 1 : 0;
		}
		if ($v['pid'] == $pid) {
			$v['child'] = node_merge($node, $access, $v['id']);
			$arr[] = $v;
		}
	}
	return $arr;
}

//根据权限显示菜单
function menu() {
	$home = $_SESSION['_ACCESS_LIST']['HOME'];
	$menu = '<li class="Index"><a href="/home/index/index">我的面板</a></li>';
	$act = strtolower(CONTROLLER_NAME);

	//组合“内容管理菜单”
	if ($act == 'index') {
		$slider = '<dl class="menu"><dt><span class="glyphicon glyphicon-th-large"></span> <strong>管理员</strong></dt><dd class="Index Admin"><a href="/Home/Index/adminEdit/admin_id/' . $_SESSION["admin_id"] . '/nickname/' . $_SESSION["nickname"] . '/type/public">修改资料</a></dd>' .
			'<dd><a href="/Home/News/newsList?status=1&recommend=0&order=id&filter=1&author=' . $_SESSION["admin_id"] . '&category=&title=">我的稿件</a></dd></dl>';
	}
	$slider1 = '';
	

	//----------------------------------
	//echo "<div class='hidden'>".$act."</div>";
	return array('menu' => $menu, 'slider' => $slider);

}


//判断  创建路径
function makeDir($name) {
	//判断路径
	$root_dir = WEB_ROOT . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
	$dir_1 = $root_dir . date('Ym');
	$dir_2 = $dir_1 . DIRECTORY_SEPARATOR . date('d');
	try {
		if (!is_dir($dir_1)) {
			mkdir($dir_1);
		}
		if (!is_dir($dir_2)) {
			mkdir($dir_2);
		}
	} catch (\Think\Exception $e) {
		var_dump($e->getMessage());
		exit('目录创建失败！');
	}
}

//检查session
function isLogin() {
	if (!session('author') || !session('nickname')) {
		echo "<script> alert('登录超时，请重新登录！');parent.location.href='/Home/Index/login'; </script>";
	}
}







































































