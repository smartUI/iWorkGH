<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,maximum-scale=1.0 initial-scale=1.0, user-scalable=0">
    <title>橘子后台登录</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/Public/img/favicon.ico">
    <style>
        .login-body {
            background-color: #444 !important;
            padding: 15px;
        }
        .login-container {
            background-color:#fff;
            max-width: 360px;
            margin: 5% auto 0;
            padding: 10px 30px;
            position: relative;
            border-radius: 4px;
        }
        h3 {
            margin: 0px;
            padding: 20px 0 30px;
        }
        .copyright {
            width: 100%;
            color: #999;
            font-size: 12px;
            text-align: center;
            position: absolute;
            bottom: -40px;
            left: 0px;
        }

    </style>
</head>
<body class="login-body">

<div class="login-container">
    <h3>橘子内容管理系统</h3>

    <form class="login-form form-validate" action="<?php echo U('Home/Index/login');?>" method="post" novalidate="novalidate">

        <div class="form-group">
            <label class="control-label">帐 号</label>
            <div class="input-group">
                <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                <input class="form-control required" id="LoginAccount" type="text" placeholder="请填写帐号" name="nickname" value="" autofocus="" aria-required="true">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">密 码</label>
            <div class="input-group">
                <div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                <input class="form-control required" id="LoginPwd" type="password" placeholder="请填写密码" name="password" aria-required="true">
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-success">
                登 录 &nbsp; <i class="glyphicon glyphicon-circle-arrow-right"></i>
            </button>
        </div>
    </form>

    <hr>

    <div class="forget-password">
        <h4>寄语：</h4>
        <p>
            橘子娱乐，给你快乐！
        </p>
    </div>

    <div class="copyright">
        <p>©2015</p>
    </div>
</div>

<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
</body>
</html>