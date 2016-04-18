/**
 * Created by zhangxinxin on 16/1/6.
 */
$(document).ready(function(){

    $('#itemList').on("change","input",function() {

        if($(this).attr('type')=='file'){
            var inputId = $(this).attr('id').toString();
            var index = inputId.indexOf('_');
            var i=inputId.substring(index-1, index);
            var prefix=inputId.substring(0, index + 1);
            var that = this;
            $("form[name='upload"+ i +"']").ajaxSubmit({
                type: 'post',
                dataType: 'json',
                url: '/Home/FoundSet/uploadImage',
                success: function (data) {
                    if(data.data == 0){
                        alert(data.msg);
                        var id='preview'+ i +'_input';
                        $('#'+id).prev().text('上传');
                    }else{
                        previewImageForFoundList(that, prefix);
                    }

                }
            });
        }


    });
});

function previewImageForFoundList(file, key) {
    var MAXWIDTH = 200;
    var MAXHEIGHT = 50;
    var divId = key + 'div';
    var div = document.getElementById(divId);
    console.log(file.files);
    console.log(file.files[0]);
    if (file.files && file.files[0]) {
        var imgId = key + 'img';
        div.innerHTML = '<img id="' + imgId + '" >';
        var img = document.getElementById(imgId);
        img.onload = function () {
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            img.width = rect.width;
            img.height = rect.height;
            //img.style.marginLeft = rect.left+'px';
            //img.style.marginTop = rect.top+'px';
        }

        var reader = new FileReader();
        reader.onload = function (evt) {
            img.src = evt.target.result;

            //1113 添加获取选择图像的原始尺寸
            $("<img/>").attr("src", evt.target.result).load(function () {
                var imginfo = document.getElementById('imginfo');
                if (imginfo) {
                    imginfo.innerHTML = "当前尺寸" + this.width + "*" + this.height + " 要求尺寸800*600";
                }
            });
        }
        reader.readAsDataURL(file.files[0]);

    }
    else //兼容IE
    {
        var sFilter = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
        file.select();
        var src = document.selection.createRange().text;
        div.innerHTML = '<img id=imghead>';
        var img = document.getElementById('imghead');
        img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
        var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
        status = ('rect:' + rect.top + ',' + rect.left + ',' + rect.width + ',' + rect.height);
        div.innerHTML = "<div id=divhead style='width:" + rect.width + "px;height:" + rect.height + "px;" + sFilter + src + "\"'></div>";
    }
}