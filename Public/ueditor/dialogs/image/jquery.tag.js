/*  This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.*/
(function($){	
	$.extend($.fn,{
		tag: function(options) {
			var defaults = {
				minWidth: 100,
				minHeight : 100,
				defaultWidth : 200,
				defaultHeight: 100,
				maxHeight : null,
				maxWidth : null,
				save : null,
				remove: null,
				canTag: true,
				canDelete: true,
				autoShowDrag: false,
				autoComplete: null,
				defaultTags: null,
				clickToTag: true,
				draggable: true,
				resizable: true,
				showTag: 'hover',
				showLabels: false,
				debug: false,
        clickable: false,
        click: null
			};			
			var options = $.extend(defaults, options);  
			return this.each(function() {				
				var obj = $(this);				
				obj.data("options",options);				
				/* we need to wait for load because we need the img to be fully loaded to get proper width & height */
				//$(window).load(function(){	
				obj.click(function(){
					if($(".jTagContainer").length>=1){
						return;
					}								
					obj.wrap('<div class="jTagContainer" />');					
					obj.wrap('<div class="jTagArea" />');					
					$("<div class='jTagLabels'><div style='clear:both'></div></div>").insertAfter(obj.parent());					
					$('<div class="jTagOverlay"></div>').insertBefore(obj);					
					container = obj.parent().parent();
					overlay = obj.prev();
					obj.parent().attr("style","width:"+obj.width()+"px;height:"+obj.height()+"px;background:url("+obj.attr('src')+") no-repeat; background-size:"+obj.width()+"px "+obj.height()+"px");
					obj.parent().parent().attr("style","width:"+obj.width()+"px;height:"+obj.height()+"px;position:absolute;left:50%;top:50%;margin-left:-"+(obj.width()/2)+"px;margin-top:-"+(obj.height()/2)+"px;z-index:998");										
					obj.hide();					
					if(options.autoShowDrag){
						obj.showDrag();
					}		
							
					container.delegate('.jTagTag','mouseenter',function(){
						if($(".jTagDrag",container).length==0){
							//$(this).css("opacity",1);
							if(options.canDelete){
								$(".jTagDeleteTag",this).show();
							}
				            $(this).addClass("on");		
							obj.disableClickToTag();
						}
					});					
					container.delegate('.jTagTag','mouseleave',function(){
						if($(".jTagDrag",container).length==0){
							if(options.showTag == 'hover'){
								//$(this).css("opacity",1);
				                if(options.canDelete){
				                  $(".jTagDeleteTag",this).hide();
				                }								
							}
							$(this).removeClass("on");	
							obj.enableClickToTag();
						}
					});
					
					if(options.showLabels && options.showTag != 'always'){					
						container.delegate('.jTagLabels label','mouseenter',function(){
							//$("#"+$(this).attr('rel'),container).css('opacity',1).find("span").show();
				              if(options.canDelete){
				                $(".jTagDeleteTag",container).show();
				              }
						});						
						container.delegate('.jTagLabels label','mouseleave',function(){
							//$("#"+$(this).attr('rel'),container).css('opacity',1).find("span").hide();
				              if(options.canDelete){
				                $(".jTagDeleteTag",container).hide();
				              }
							
						});					
					}	
									
					if(options.canDelete){					
						container.delegate('.jTagDeleteTag','click',function(){							
							/* launch callback */
							if(options.remove){
								options.remove($(this).parent().parent().getId());
							}							
							/* remove the label */
							if(options.showLabels){
								$(".jTagLabels",container).find('label[rel="'+$(this).parent().parent().attr('id')+'"]').remove();
							}							
							/* remove the actual tag from dom */
							$(this).parent().parent().remove();							
							obj.enableClickToTag();							
						});					
					}
										        
          			if(options.clickable){
				 		container.delegate('.jTagTag','click',function(){
							/* launch callback */
							if(options.click){
								options.click($(this).find('span').html());
							}
						});
					}
					
					if(options.defaultTags){
						$.each(options.defaultTags, function (index,value){
							obj.addTag(value.width,value.height,value.top,value.left,value.label,value.link,value.id);
						});
					}					
					obj.enableClickToTag();						
				});			
			});
		},
		hideDrag: function(){
			var obj = $(this);			
			var options = obj.data('options');			
			obj.prev().removeClass("jTagPngOverlay");			
			obj.parent().parent().find(".jTagDrag").remove();			
			if(options.showTag == 'always'){
				obj.parent().parent().find(".jTagTag").show();
			}			
			obj.enableClickToTag();			
		},
		showDrag: function(e){
			var obj = $(this);			
			var container = obj.parent().parent();
			var overlay = obj.prev();			
			obj.disableClickToTag();			
			var options = obj.data('options');			
			var position = function(context){
				var jtagdrag = $(".jTagDrag",context);
				//border =   parseInt(jtagdrag.css('borderTopWidth'));
				left_pos = parseInt(jtagdrag.attr('offsetLeft')) + border;
				top_pos =  parseInt(jtagdrag.attr('offsetTop')) + border;
				return "-"+left_pos+"px -"+top_pos+"px";
			}			
			if($(".jTagDrag",overlay).length==1){
				return;
			}	
			if(!options.canTag){
				return;
			}
			
			if(options.showTag == 'always'){
				$(".jTagTag",overlay).hide();
			}
					
			$('<div class="jTagDrag" style="width:'+options.defaultWidth+'px;height:'+options.defaultHeight+'px;"><div class="jTagSave"><div class="jTagInput">文字：<input type="text" id="jTagLabel"></div><div class="jTagInput">链接：<input type="text" id="jTagLink"></div><div class="jTagSaveClose"></div><div class="jTagSaveBtn">保存</div><div style="clear:both"></div></div>').appendTo(overlay);
			
			overlay.addClass("jTagPngOverlay");			
			jtagdrag = $(".jTagDrag",overlay);
			jtagdrag.css("position", "absolute");
			
			
			if(e){				
				function findPos(someObj){
					var curleft = curtop = 0;
					if (someObj.offsetParent) {
						do {
							curleft += someObj.offsetLeft;
							curtop += someObj.offsetTop;
						} while (someObj = someObj.offsetParent);
						return [curleft,curtop];
					}
				}
				
				/* get real offset */
				pos = findPos(obj.parent()[0]);
				
				x = Math.max(0,e.pageX - pos[0] /*- (options.defaultWidth / 2)*/);
				y = Math.max(0,e.pageY - pos[1] /*- (options.defaultHeight / 2)*/);
				/*
				if(x + jtagdrag.width() > obj.parent().width()){
					x = obj.parent().width() - jtagdrag.width() - 2;
				}				
				
				if(y + jtagdrag.height() > obj.parent().height()){
					y = obj.parent().height() - jtagdrag.height() - 2;
				}
				*/

			} else {				
				x = 0;
				y = 0;				
			}
			
			jtagdrag.css("top",y)
						  .css("left",x);
			
			
			if(options.autoComplete){
				$("#jTagLabel",container).autocomplete({
					source: options.autoComplete
				});
			}
			
			$(".jTagSaveBtn",container).click(function(){				
				label = $("#jTagLabel",container).val();
				link = $("#jTagLink",container).val();				
				if(label==''){
					alert('文字不能为空');
					return;
				}								
				height = $(this).parent().parent().height();
				width = $(this).parent().parent().width();
				top_pos = $(this).parent().parent(".jTagDrag").position().top/obj.height()*100+"%";
				left = $(this).parent().parent(".jTagDrag").position().left/obj.width()*100+"%";
				tagobj = obj.addTag(width,height,top_pos,left,label,link);				
				if(options.save){
					options.save(width,height,top_pos,left,label,link,tagobj);
				}
				
			});
			
			$(".jTagSaveClose",container).click(function(event){				
				obj.hideDrag();
				event.stopPropagation();
			});
			
			if(options.resizable){			
				jtagdrag.resizable({
					containment: obj.parent().parent().parent()
				});
			
			};	
				
			if(options.draggable){					
				jtagdrag.draggable({
					containment: obj.parent().parent().parent()
				});			
			}
		},
		addTag: function(width,height,top_pos,left,label,link,id){			
			var obj = $(this);			
			var options = obj.data('options');			
			var count = $(".jTagTag").length+1;	
			var vlink,linkhtml="";	
			if(link){
				if(link.indexOf("//") <= -1){
					vlink = "http://"+link;	
					linkhtml = 	'<p><a href="'+vlink+'" target="_blank">'+link+'</a></p>';			
				}
			}
			//打印添加的锚点的结果
			//console.log(obj);
							
			tag = $('<div class="jTagTag" style="top:'+top_pos+';left:'+left+'" id="tag'+count+'"style="top:'+top_pos+';left:'+left+';" data-base="{purl:'+obj.attr("src")+',ptop:'+top_pos+',pleft:'+left+',plabel:'+label+',plink:'+link+',id:'+count+'}"><p class="jTagCircle"></p><div class="jTagWords"><p>'+label+'</p>'+linkhtml+'<span class="jTagDeleteTag"></span></div></div>')
						.appendTo(obj.prev());	

			if(id){
				tag.setId(id);
			}
			
			if(options.canDelete){
				obj.parent().find(".jTagDeleteTag").show();
			}
			
			if(options.showTag == "always"){
				$(".jTagTag").css("opacity",1);
			}
			
			if(options.showLabels){
				$("<label rel='tag"+count+"'>"+label+"</label>").insertBefore($(".jTagLabels div:last"));
			}
			
			obj.hideDrag();
			
			return tag;
			
		},
		setId: function(id){
			if($(this).hasClass("jTagTag")){
				$(this).data("tagid",id);
			} else {
				alert('Wrong object');
			}
		},
		getId: function(id){
			if($(this).hasClass("jTagTag")){
				return $(this).data("tagid");
			} else {
				alert('Wrong object');
			}
		},
		enableClickToTag: function(){
			
			var obj = $(this);
			var options = obj.data('options');
			
			if(options.clickToTag){
				
				obj.parent().mousedown(function(e){
					obj.showDrag(e);
					obj.parent().unbind('mousedown');
				});
			}
		},
		disableClickToTag: function(){
			
			var obj = $(this);
			var options = obj.data('options');
			
			if(options.clickToTag){
				obj.parent().unbind('mousedown');
			}
		}
	});
})(jQuery);
