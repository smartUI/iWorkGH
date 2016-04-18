 
 /**
 * Created on 15/8/13.
 */
$(function(){
    	
    	//添加选中的作者信息
    	$(".add-author").on('click',function() {

            var myselect = document.getElementById("select-author");
            var index = myselect.selectedIndex;
            var author_value = $('#select-result').val();
            var author_name = $('.select-input').val();
            if (author_name == '全部' || author_name == '') 
            {

            }
            else
            {
                    var author_button = '<div class="btn btn-primary news-tag pull-left" style="margin: 3px" role="alert"><input name="authors[]" type="hidden" value="'+ author_value+'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ author_name+'</div>';
                    $('.author-container').append(author_button);  
            }


      });



        //添加推荐搜索词
        $(".add-word").on('click',function() {

            //获取当前已经设置的热搜词数量，如果大于6个则提示不能再设置
            var wordnum = $('.search-word').length;
            if (wordnum >= 6) 
            {
                alert("请注意，最多只能设置6个热搜词！");
                return;
            }

            //
            var word_name = document.getElementById("input2").value;
            if (word_name == '') 
            {
            }
            else
            {
                var word_button = '<div class="btn btn-primary search-word pull-left" style="margin: 3px" role="alert"><input name="searchwords[]" type="hidden" value="'+ word_name+'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ word_name+'</div>';
                $('.word-container').append(word_button);  
            }


      });


        //添加自动上线kol
        $(".add-auto-kol").on('click',function() {

            //
            var kol_value = $('#select-kol-result').val();
            var kol_name = $('#select-kol-input').val();

            if (kol_name == '' || kol_name == '选择KOL') 
            {
            }
            else
            {
                var button_name = kol_name;
                var button_value = kol_value;
                var kol_button = '<div class="btn btn-primary kol-cat pull-left" style="margin: 3px" role="alert"><input name="kols[]" type="hidden" value="'+ button_value +'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ button_name +'</div>';
                $('.kol-container').append(kol_button);  
            }

            
      });
        
        //添加自动上线kol
        $(".add-auto-ch").on('click',function() {

            //
            var ch_id = $('#channel option:selected').val();
            var ch_name = $('#channel option:selected').text();

            if (ch_id == '') 
            {
            }
            else
            {
                var button_name = ch_name;
                var button_value = ch_id;
                var kol_button = '<div class="btn btn-primary kol-cat pull-left" style="margin: 3px" role="alert"><input name="chs[]" type="hidden" value="'+ button_value +'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ button_name +'</div>';
                $('.chs-container').append(kol_button);  
            }


        });

})