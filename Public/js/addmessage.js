/**
 * Created by Sorci on 15/10/12.
 */

$(function(){
    /**
     * 发送类型的单选效果
     */
    $(".send-type").click(function(){
        var type = $(this).attr("attr");
        $("#send_type").val(type);
        $(this).addClass("disabled").siblings("a.disabled").removeClass("disabled");
        $("#div-" + type).show().siblings("div").hide();
    });

    /**
     * 消息类型的单选效果
     */
    $(".msg-type").click(function(){
        var type = $(this).attr("attr");
        $("#message_type").val(type);
        $(this).addClass("disabled").siblings("a.disabled").removeClass("disabled");
        $(".div-" + type).show().siblings("." + type).hide();
        if (type == 'image') {
            $("#content").removeAttr("required");
            $("#uploadImg").attr("required", "required");
        } else {
            $("#content").attr("required", "required");
            $("#uploadImg").removeAttr("required");
        }
    });

    /**
     * 添加一个用户
     */
    $("#add-user").click(function(){
        var uid = $("#user-id").val();
        $.ajax({
            type: "post",
            url: "/Home/Message/getuser",
            dataType: "json",
            data: {
                uid: uid
            },
            success: function(data) {
                if (data.data == 0) {
                    alert(data.msg);
                } else {
                    $("#user-panel").append('<input type="checkbox" name="user_id[]" value="' + uid + '" checked="checked" /> ' + data.data + ' ');
                    $("#user-id").val("");
                }
            }

        });
    });

    /**
     * 上传一张图片
     */
    $('#uploadImg').change(function(){
        $("#uploadImg").prev('span').text('努力上传ing...');
        $("form[name='addmessage']").ajaxSubmit({
            type: 'post',
            dataType: 'json',
            url: '/Home/message/upload',
            success: function(data){
                if(data.data == 0) {
                    alert(data.msg);
                } else {
                    $('#preview').empty().append('<img src="http://images11.app.happyjuzi.com' + data.filename + '"  />').parent("div").parent("div").show();
                    $('#hidden').empty().append('<input type="hidden" name="img" value="' + data.filename + '">');
                }
                $("#uploadImg").prev('span').text('选择图片');

            }
        })
    });

    /**
     * 打开蒙层
     */
    $('.link-text').click(function(){
        var name = $(this).attr("name");
        $("#myModal").modal("show");
        $("#view-title").text(get_title(name));
        $("#view-body").empty().prepend(get_body(name));
        $("#add-link").attr("text", $(this).attr("st")).attr("type", name);
    });

    /**
     * 添加超链
     */
    $("#add-link").click(function(){
        if ($("#text1").val() == "" || $("#text2").val() == "") {
            alert("输入框都不能为空！");
        } else {
            var type = $(this).attr("type");
            if (type == 'article') {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/Home/message/checked',
                    data: {
                        id: $("#text1").val()
                    },
                    success: function (data) {
                        if (data.data == 1) {
                            alert('该文章需要对低版本进行屏蔽，不能作为消息发送。');
                        } else {
                            var text = '<a href="' + $("#add-link").attr("text") + $("#text1").val() + '">' + $("#text2").val() + '</a>';
                            var content = $("#content").val();
                            $("#myModal").modal("hide");
                            $("#content").val(content + text);
                            $("#preview-text").html($("#content").val());
                            $("#content").focus();
                            $("#link-type").val($(this).attr("type"));
                        }
                    }
                });
            }
            if(type == 'star' || type == 'tag' || type == 'feature') {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/Home/message/get_title',
                    data: {
                        type: type,
                        id: $("#text1").val()
                    },
                    success: function (data) {
                        if(data.status == 0) {
                            alert(data.msg);
                        } else {
                            var text = '<a href="' + $("#add-link").attr("text") + $("#text1").val() + '&name=' + data.name + '">' + $("#text2").val() + '</a>';
                            var content = $("#content").val();
                            $("#myModal").modal("hide");
                            $("#content").val(content + text);
                            $("#preview-text").html($("#content").val());
                            $("#content").focus();
                            $("#link-type").val($(this).attr("type"));
                        }
                    }
                });
            } else if (type != 'article') {
                var text = '<a href="' + $("#add-link").attr("text") + $("#text1").val() + '">' + $("#text2").val() + '</a>';
                var content = $("#content").val();
                $("#myModal").modal("hide");
                $("#content").val(content + text);
                $("#preview-text").html($("#content").val());
                $("#content").focus();
                $("#link-type").val($(this).attr("type"));
            }

        }
    });

    /**
     * 文本预览
     */
    $("#content").keyup(function(){
        $("#preview-text").html($(this).val());
    });

    /**
     * 切换输入框类型
     */
    $(".link-image").click(function(){
        var type = $(this).attr("attr");
        var t = get_type(type);
        $("#btn-image-type").html(t + " <span class=\"caret\"></span>");
        $("#image-text").attr("placeholder", get_pl(type));
        $("#link-type").val(type);
        if (type == 'web') {
            $("#image-text").val("http://").focus();
        } else {
            $("#image-text").val("").focus();
        }
    });

    function get_type(t) {
        var ti = "";
        switch (t) {
            case 'article':
                ti = "文章";
                break;
            case 'web':
                ti = "html5";
                break;
            case 'star':
                ti = "明星";
                break;
            case 'tag':
                ti = "Tag";
                break;
            case 'feature':
                ti = "栏目";
                break;
            case 'specialreport':
            	ti = "专题";
                break;
            default : break;
        }
        return ti;
    }

    function get_title(t) {
        var title = "";
        switch (t) {
            case 'article':
                title = "添加文章超链";
                break;
            case 'web':
                title = "添加html5超链";
                break;
            case 'star':
                title = "添加明星超链";
                break;
            case 'tag':
                title = "添加Tag超链";
                break;
            case 'feature':
                title = "添加栏目超链";
                break;
            case 'specialreport':
                title = "添加专题超链";
                break;
            default : break;
        }
        return title;
    }

    function get_pl(t) {
        var pl = '';
        switch (t) {
            case 'article':
                pl = "请输入文章编号。";
                break;
            case 'web':
                pl = "请输入html5访问链接。";
                break;
            case 'star':
                pl = "请输入明星编号。";
                break;
            case 'tag':
                pl = "请输入Tag编号。";
                break;
            case 'feature':
                pl = "请输入栏目编号。";
                break;
            case 'specialreport':
            	pl = "请输入专题编号";
                break;
            default : break;
        }
        return pl;
    }

    function get_body(t) {
        var body = "";
        switch (t) {
            case 'article':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">文章</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入文章编号。" aria-describedby="basic-addon1">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            case 'web':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">URL</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入html5访问链接。" aria-describedby="basic-addon1" value="http://">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            case 'star':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">明星</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入明星编号。" aria-describedby="basic-addon1">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            case 'tag':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">Tag</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入Tag编号。" aria-describedby="basic-addon1">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            case 'feature':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">栏目</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入栏目编号。" aria-describedby="basic-addon1">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            case 'specialreport':
                body =  '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">专题</span>';
                body += '<input type="text" class="form-control" id="text1" placeholder="请输入专题编号。" aria-describedby="basic-addon1">';
                body += '</div>';
                body += '<br />';
                body += '<div class="input-group">';
                body += '<span class="input-group-addon" id="basic-addon1">内容</span>';
                body += '<input type="text" class="form-control" id="text2" placeholder="请输入超链内的文本内容。" aria-describedby="basic-addon1">';
                body += '</div>';
                break;
            default : break;
        }
        return body;
    }



});