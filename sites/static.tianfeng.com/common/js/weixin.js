
 var weixin_wait=2;
    function downLoadApk(){
    	if(is_weixin()==false && 0)  
    		location.href="http://www.winfield-tech.com/apps/ulocker.apk";
    	else{
    		location.href="http://a.app.qq.com/o/simple.jsp?pkgname=com.doplatform.dolocker";
    		//$("<div id='loginiframe' style='position:fixed; _position:absolute; top:14px; z-index:20000; width:100%;left:100px;  '><img src='http://static.dolocker.com/mobile/img/wechat_Tip.png'></div>").appendTo("body");
    		//$("#touming").addClass("touming");
    		//weixin_time();
    	}
    }
    
    function is_weixin(){
		var ua = navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i)=="micromessenger") {
			return true;
	 	} else {
			return false;
		}
	}
    
    function weixin_time() {//获取验证码时间等待	 
			if (weixin_wait == 0) {
				$("#loginiframe").remove();
				$("#touming").removeClass("touming");
				weixin_wait = 2;
			} else {
					weixin_wait--;
					setTimeout(function() {
					    weixin_time();
					},
					1000)
			}
	}
