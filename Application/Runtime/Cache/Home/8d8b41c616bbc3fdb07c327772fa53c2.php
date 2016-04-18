<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="min-width: 1200px !important;">
<head lang="en">
    <meta charset="UTF-8">
    <title>橘子后台管理</title>

    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/common.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/juzi_style.css">
    <link rel="shortcut icon" href="/Public/img/favicon.ico">
    <script src="/Public/js/jquery.min.js"></script>
</head>
<body>

<div class="header" style="text-align: end;">
    <div class="row">
        <!--<div class="col-xs-2 logo_pic">
            <img src="/Public/img/logo_new.png" height="70%" width="70%"/>
        </div>-->
        <div class="col-xs-3">
            <h3 class="logo" style="text-align: left;
            padding-left:19px; color:ghostwhite;">
                后台管理系统<!--<sub class="badge">CMS</sub>-->
            </h3>
        </div>
    </div>
    <div class="row">
        <!--<div class="col-xs-2" style="
        text-align: inherit;
        height: 100%;
        padding-bottom: 0;
        line-height: 10px;
        padding-top: 62px;">
            <div style="color:#FFFFFF">
            </div>

        </div>-->
        <div class="col-xs-7 col-xs-offset-2" style="text-align: inherit;height: 100%;padding-bottom: 0;line-height: 10px;padding-top: 11px;padding-left: 37px;">
            <ul class="nav nav-pills">
                <li class="Index">
                    <a href="/home/index/index">我的面板</a>
                </li>
                <li class="News Photo Comment CategoryTop Gif Topic Imgtxt">
                    <a href="/Home/News/newsList/status/1">宫格管理</a>
                </li>
                <li class="Member">
                    <a href="/home/member/memberlist">信息流管理</a>
                </li>
                <!--<li class="Admin Role Node Log Init Category Tag">-->
                    <!--<a href="/Home/Admin/adminList/status/1">设置</a>-->
                <!--</li>-->
                <!--<li class="Vote VoteGroup Guess Music Video Attitude Expression">-->
                    <!--<a href="/home/vote/votelist">插件</a>-->
                <!--</li>-->

            </ul>
        </div>
        <div class="col-xs-3 text-right login-msg">
            <ul class="nav navbar-right account" style="color: #efefeb;">
                欢迎您，<a href="/home/index/index" style="color: #efefeb"><b><?php echo (session('nickname')); ?></b></a>
                &nbsp;
                <a href="/home/index/logout" style="color:#efefeb">退出</a>

            <!--<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    用户名<span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="">修改密码</a></li>
                    <li class="divider"></li>
                    <li><a href="">退出</a></li>
                </ul>
            </li>-->

            </ul>
        </div>
    </div>
</div>


<div class="container-fluid">

    <div class="main row">
        <div class="left-box col-xs-2">

            <?php echo ($slider); ?>
            <!--<div class="left-box col-sm-3 col-md-2">
    <dl class="menu">
        <dt><span class="glyphicon glyphicon-th-large"></span> <strong>管理员</strong></dt>
        <dd class=""><a href="<?php echo U('Home/Admin/adminEdit',array('admin_id'=>$_SESSION['admin_id'],'nickname'=>$_SESSION['nickname']));?>">修改资料</a></dd>
    </dl>

    <dl class="menu">
        <dt><span class="glyphicon glyphicon-th-large"></span> <strong>管理员设置</strong></dt>
        <dd class=""><a href="<?php echo U('Home/Admin/adminList');?>">管理员列表</a></dd>
        <dd class=""><a href="<?php echo U('Home/Role/roleList');?>">角色列表</a>
        <dd class=""><a href="<?php echo U('Home/Node/nodeList');?>">节点列表</a>
        </dd>
    </dl>

</div>-->

        </div>

        <div class="main-box col-xs-10">
            <div class="container-fluid">

                <h4> 宫格信息管理 </h4>
<form class="form-horizontal col-sm-7" action="/home/Grid/edit" method="post"  enctype="multipart/form-data">
    <div class="form-group">
        <label for="icon" class="col-sm-2 control-label">宫格ICON</label>
        <div class="col-sm-5">
            <!--<img class="col-sm-12" src="<?php echo (C("IMG_HOST")); echo ($admin["portrait"]); ?>" style="margin-bottom: 5px;">-->
            <div id="preview">
                <img id="imghead" style="max-width: 100%; max-height: 150px;" border="0" src="<?php echo (C("IMG_HOST")); echo ($grid["icon"]); ?>">
            </div>
            <?php if(($grid["icon"] != '')): ?><br><?php endif; ?>
            <div class="">
                <input type="file" name="icon" id="icon" title="选择一张图片" class="btn-primary" data-filename-placement="inside" style="position: absolute;opacity:0;" >
            </div>

        </div>

    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">宫格名称</label>

        <div class="col-sm-6">
            <input name="id" type="hidden" class="form-control" required="true" value="<?php echo ($grid["id"]); ?>">
            <input name="title" type="text" class="form-control" id="title" required="true" value="<?php echo ($grid["title"]); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-sm-2 control-label">宫格链接</label>

        <div class="col-sm-6">
            <input name="url" type="text" class="form-control" id="url" value="<?php echo ($grid["url"]); ?>"><span class="label label-warning">完整都URL地址</span>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-sm-2 control-label">宫格排序</label>

        <div class="col-sm-6">
            <input name="desc" type="number" class="form-control" id="desc" value="<?php echo ($grid["desc"]); ?>"><span class="label label-warning">只能填写数字</span>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class="col-sm-2 control-label">是否展示</label>

        <div class="col-sm-6">
            <input name="status" type="checkbox" class="form-control" id="status" value="1">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-9">
            <button type="submit" class="btn btn-default">确定</button>
            <button type="reset" class="btn btn-default">重置</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="/Public/js/preview_img.js"></script>




            </div>

        </div>
    </div>
</div>


<script src="/Public/js/bootstrap.min.js"></script>
<script src="/Public/js/bootstrap.file-input.js"></script>
<script src="/Public/js/juzi.js"></script>


<!--[if lte IE 9]>
<script src="/Public/js/respond.min.js"></script>
<script src="/Public/js/html5shiv.min.js"></script>
<![endif]-->

<script>

    var controller = "<?php echo ($controller); ?>";
    var sidebar = "<?php echo ($sidebar); ?>";
    $(function(){
        //导航点击变色的结构
        if(sidebar=='Public:sidebar'){
            $('.Index').addClass('active');
        }else{
            //sidebar的样式
            $('.'+controller).addClass('active');
        }

    })
</script>



</body>
</html>