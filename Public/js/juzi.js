/**
 * Created by Villen on 15/4/6.
 */


/*
* 点击选择所有input
* 注意：点击点的class必须是 select-all
* @param name input的name值
*/
function selectall(name){
    var lead = $('.select-all');
    var select_all = $("input[name='"+name+"']");
    if(lead.hasClass('all')){
        select_all.each(function(){
            $(this).prop('checked', false);
            var val = $(this).attr('alt');
            $("#"+val).remove();
        });
        lead.removeClass('all');
    }else{
        select_all.each(function(){
            $(this).prop('checked',true);
            var val = $(this).attr('alt');
            $('#commInput').append("<input type='checkout' id='"+val+"' name='comments[]' value='"+val+"' style='display:none;' />");
        });
        lead.addClass('all');
    }
}
$(function(){

    //新闻  模板点击
    //模板选择
    $('.type-img').on('click',function(){
        $('.type-img').removeClass('img-thumbnail');
        $(this).addClass('img-thumbnail');
    })

    //上传图片的按钮
    $('input[type=file]').bootstrapFileInput();
})