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

                <div class="row panel panel-default" style="padding: 15px;">
    <div class="panel-body">
        <div class="row"><h4>欢迎来到定制后台！</h4></div>
        <div class="row">
            <div id="preview">
                <?php if(!empty($admin["portrait"])): ?><img id="imghead" style="max-width: 10%; max-height: 200px;" border=1 src="<?php echo (C("IMG_HOST")); echo ($admin["portrait"]); ?>"><?php endif; ?>
            </div><br>
            登录管理员：<b><?php echo session('nickname');?></b>
            <br>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content col-xs-10">
    <form id="setpasswdform" name="setpasswdform" action="/Home/Index/setpasswd" method="post">
        <input type="hidden" name="issetpasswd" value="<?php echo ($issetpasswd); ?>" />
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="myModalLabel">修改密码</h4>
      </div>

      <div class="modal-body">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php if($issetpasswd == 1): ?>您是第一次登录本系统，请设置一个属于自己的密码吧！
        <?php elseif($issetpasswd == 2): ?>
            由于您的账户有安全隐患，所以请重设一个密码！
        <?php elseif($issetpasswd == 3): ?>
            您的密码已被管理员重置，现在请设置一个属于自己的密码吧！
        <?php else: endif; ?>
      </div>
        <div class="form-group">
            <label for="inputpasswd" class='col-xs-12'>输入密码</label>
            <div class="col-xs-8">
            <input type="password" class="form-control" id="inputpasswd" name="inputpasswd" placeholder="输入密码">
            </div><span class="" id="inputpasswdtxt"></span>
        </div><br/>
        <div class="form-group">
            <label for="inputrepasswd" class='col-xs-12'>再输一次</label>
            <div class="col-xs-8">
            <input type="password" class="form-control" id="inputrepasswd" name="inputrepasswd" placeholder="再输一次">
            </div><span class="" id="inputrepasswdtxt"></span>
        </div>
        <div class='clearfix'></div><br/>
        <div class='col-xs-12'><p class="text-muted glyphicon glyphicon-info-sign">请输入6~16位数字，字母或常用字符，字母区分大小写，密码强度至少<i class="text-danger">中级</i></p></div><br/>
        
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        <button type="submit" id="btn01" form="setpasswdform" class="btn" disabled>设置密码...</button>
      </div>
    </form>
    </div>
  </div>
</div>
<?php if($issetpasswd > 0): ?><script>
$(function(){
    $('#myModal').modal({backdrop: 'static', keyboard: false});
    $('#inputpasswd').keyup(function(){
            var r= passwordLevel($(this).val());
            txtLevel(r, $("#inputpasswdtxt"));
            checkrepasswd($("#inputrepasswd").val(), $("#inputrepasswdtxt"));
    });
    $('#inputrepasswd').keyup(function(){
            //var r= passwordLevel($(this).val());
            //txtLevel(r, $("#inputrepasswdtxt"));
            checkrepasswd($(this).val(), $("#inputrepasswdtxt"));
    });
});

function checkrepasswd(val, obj){
    if(val != $('#inputpasswd').val()){
        obj.text("密码不一致");
        obj.removeClass().addClass("text-danger");
        changeBtn(false);
    }else if(passwordLevel(val)<2){
        obj.text("密码强度不足");
        obj.removeClass().addClass("text-danger");
        changeBtn(false);
    }else{
        obj.text("密码一致");
        obj.removeClass().addClass("text-success");
        changeBtn(true);
    }
}

function changeBtn(isdisable){
    if(isdisable){
        $("#btn01").attr("disabled", false);
        $("#btn01").addClass("btn-default");
        $("#btn01").text('保存');
    }else{
        $("#btn01").attr("disabled", true);
        $("#btn01").text('设置密码...');
    }
}

function txtLevel(level, obj){
    if (level < 2){
        obj.text("密码强度：低");
        obj.removeClass().addClass("text-danger");
    }else if(level <3){
        obj.text("密码强度：中");
        obj.removeClass().addClass("text-warning");
    }else{
        obj.text("密码强度：高");
        obj.removeClass().addClass("text-success");
    }
}
function passwordLevel(password) {
    var Modes = 0;
    for (i = 0; i < password.length; i++) {
        Modes |= CharMode(password.charCodeAt(i));
    }
    var result = bitTotal(Modes);
    if(password.length<6){
        if(result>=2){
            result = 1;
        }
    }
    return result;

    //CharMode函数
    function CharMode(iN) {
        if (iN >= 48 && iN <= 57)//数字
            return 1;
        if (iN >= 65 && iN <= 90) //大写字母
            return 2;
        if ((iN >= 97 && iN <= 122) || (iN >= 65 && iN <= 90)) //大小写
            return 4;
        else
            return 8; //特殊字符
    }

    //bitTotal函数
    function bitTotal(num) {
        modes = 0;
        for (i = 0; i < 4; i++) {
            if (num & 1) modes++;
            num >>>= 1;
        }
        return modes;
    }
}
</script><?php endif; ?>


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