// JavaScript Document
	$(function(){
		$("#login").click(function(){
			var name = $("#username").val();
			var pwd = $("#password").val();
                        var remember = 0;
                        if($("#forpwd").is(":checked")){
                             remember = 1;
                        }
			$(".name_tips,.pwd_tips").empty()
			var flag = 0;
			if(name=='' || name=='请输入邮箱') {
				$(".name_tips").append('请输入邮箱');
				flag++;
			}
			if(pwd=='') {
				$(".pwd_tips").append('请输入密码');
				flag++;
			}
			if (flag>0) {
				return false;	
			} else {
				$.post(URL+'/checkLogin',{username:name,password:pwd,remember:remember},function(data) {
					var result = eval(data);
					if (result.status == 0) {
						$(".name_tips").append(result.prompt);	
						return false;	
					} else if(result.status == 1) {
						$(".pwd_tips").append(result.prompt);	
					} else {
						window.location.href = ROOT+'/Setting';
					}
				});
		     }
		})
	})