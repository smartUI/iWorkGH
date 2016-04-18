/****************************
 * 作者：smm             	*
 * 时间：2015-11-10			*
 * version：0.1				*
 * test for chrome firefox	ie	*
 ****************************/


		window.onload=function()
		{
			document.getElementsByTagName("body")[0].onkeydown = function(evt){
				
				evt = evt ? evt : ((window.event) ? window.event : "");
				var key = evt.keyCode?evt.keyCode:evt.which;

				//获取事件对象

				if(key==8){//判断按键为backSpace键
				
						//获取按键按下时光标做指向的element
						var elem = evt.srcElement || evt.target; 
						
						//判断是否需要阻止按下键盘的事件默认传递
						var name = elem.nodeName;
						
						if(name!='INPUT' && name!='TEXTAREA'){
								if(window.event)
								{
									evt.returnValue = false ;
								}
								else
								{
									evt.preventDefault();
								}	
								return false;
						}
						var type_e = elem.type.toUpperCase();

						if(name=='INPUT' && (type_e!='TEXT' && type_e!='TEXTAREA' && type_e!='PASSWORD' && type_e!='FILE')){
								if(window.event)
								{
									evt.returnValue = false ;
								}
								else
								{
									evt.preventDefault();
								}	
								return false;
						}
						if(name=='INPUT' && (elem.readOnly==true || elem.disabled ==true)){
								if(window.event)
								{
									evt.returnValue = false ;
								}
								else
								{
									evt.preventDefault();
								}	
								return false;
						}
					}
				}
			}


		function _stopIt(e)
		{
				if(window.event)
				{
					e.returnValue = false ;
				}
				else
				{
					e.preventDefault();
				}				

				return false;
		}