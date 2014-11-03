document.write('<script src="/Public/Js/person.js"><\/script>');
var NOW = (function(my,$){
    my.refresh = function(opt,action){
        $.ajax({
            type:'get',
            url:sUrl+"/Works/member_info",
            data:{friend_member_id:friend_member_id},
            async:false,
            success:function(data){
                uid = $(data).find("#login_info .top_person").attr("uid");
                $("#login_info").html($(data).find("#login_info").html());
                var likerow = $(data).find("#login_info .top_person").attr("likeRow");
                var collectrow = $(data).find("#login_info .top_person").attr("collectRow");
                var is_follow = $(data).find("#login_info .top_person").attr("is_follow");
                if(likerow !== ""){
                     likerow = likerow.split(",");
                     for(var i=0;i<likerow.length;i++){
                         $("#js_like_"+likerow[i]).attr("class","likebtn liked");
                     }
                }
                if(collectrow !== ""){
                     collectrow = collectrow.split(",");
                     for(var i=0;i<collectrow.length;i++){
                         $("#js_collect_"+collectrow[i]).attr("class","collectbtn collected");
                     }
                }
                if(is_follow == 1){
                    $("#gz_follow").attr('class',"ygz_btn");
                    $("#gz_follow").html("已关注");
                }
            }
        });
        if(action === 'collect'){
            var obj = $("#js_collect_"+opt.id);
            if(obj.hasClass("collected")){
                INIT.error('您已收藏过了喔');
            }else{
                PERSON.collect(opt.id,opt.type,function(status){
                    if(status === 2){
                        obj.addClass('collected');
                        var collect_count = $("#js_collect_count_"+opt.id);
                        collect_count.html(parseInt(collect_count.html()) + 1);                          
                    }
                });                               
            }
        }else if(action === 'like'){
            var obj = $("#js_like_"+opt.id);
            if(obj.hasClass("liked")){
                INIT.error('您已喜欢过了喔');
            }else{
                PERSON.like(opt.id,opt.type,function(status){
                    if(status === 2){
                        obj.addClass('liked');
                        var like_count = $("#js_like_count_"+opt.id);
                        like_count.html(parseInt(like_count.html()) + 1);                          
                    }
                });                               
            }
        }else if(action === 'gz'){
                var obj = $("#gz_follow");
                if(obj.hasClass("ygz_btn")){
                    INIT.error('您已关注了喔');
                }else{
                    PERSON.follow(opt.id,function(status){
                        if(status === 2){
                           obj.html('已关注');
                           obj.attr('class','ygz_btn'); 
                        }
                    });
                }
        }else if(action === 'sx'){
                $.post(sUrl+'/Message/check_letter',opt,function(data){
                    var res = eval(data);
                    if(res.status === 2){
                        var login = $('#js_sx_box');
                        var floats = $('.floats');
                        floats.css({width:$(document).width()+'px',height:$(document).height()+'px'}).show();
                        var w = ($(window).width() - $('#js_sx_box').width())/2;
                        var h = ($(window).height() - $('#js_sx_box').height())/2 + $(document).scrollTop();
                        login.css({top:h+"px", left:w+"px"}).show();
                        $('.tb_sx_username').html(opt.to_member_name);
                        $("#js_close_sx").bind("click", function(){ login.hide();floats.hide(); });    
                    }else if(res.status === 1){
                        INIT.error('不能给自己发送私信喔');
                    }else{
                        INIT.error('系统错误！');
                    }
                });
        }
    };
    return my;
})(NOW || {},jQuery); 


function is_login(opt,action){
    if(uid === ""){
       //INIT.error('您还没登录哦');
       INIT.login(NOW.refresh,opt,action);
       return false;
    }
}

$(function(){
        $(".review_box li").hover(function(){
                $(this).find(".layer").show();
        },function(){
                $(this).find(".layer").hide();
        });
	$(".collectbtn").click(function(event){
                var obj = this;
                var dataid = $(obj).attr('dataid');        
                var type = parseInt($(obj).attr('type'))+1;
                if(is_login({id: dataid, type: type},'collect') === false){
                    return false;
                }
                var length = $(obj).parent().find('.collected').length;
                // 取消收藏
                if (length > 0) {
                        PERSON.uncollect(dataid,type,function(status){
                            if(status === 2){
                                    $(obj).removeClass('collected');
                                    var favorite = $("#js_collect_count_"+dataid);
                                    favorite.html(parseInt(favorite.html()) - 1);                                
                            }
                        });
                }
                // 添加收藏
                else {
                        PERSON.collect(dataid,type,function(status){
                            if(status === 2){
                                    $(obj).addClass('collected');
                                    var favorite = $("#js_collect_count_"+dataid);
                                    favorite.html(parseInt(favorite.html()) + 1);                                
                            }
                        });
                }
                event.preventDefault();
                event.stopPropagation();
	 });
	 
	 $(".likebtn").click(function(event){
                var obj = this;
                var dataid = $(obj).attr('dataid');
                var type = parseInt($(obj).attr('type'))+1;
                if(is_login({id: dataid, type: type},'like') === false){
                    return false;
                }
                var length = $(obj).parent().find('.liked').length;
                // 取消喜欢
                if (length > 0) {
                        PERSON.unlike(dataid,type,function(status){
                            if(status === 2){
                                    $(obj).removeClass('liked');
                                    var love = $("#js_like_count_"+dataid);
                                    love.html(parseInt(love.html()) -1);                                
                            }
                        });
                }
                // 添加喜欢
                else {
                        PERSON.like(dataid,type,function(status){
                            if(status === 2){
                                    $(obj).addClass('liked');
                                    var love = $("#js_like_count_"+dataid);
                                    love.html(parseInt(love.html()) +1);                                
                            }
                        });
                }
                event.preventDefault();
                event.stopPropagation();
	 });
	 
	//关注、取消关注
	$('#gz_follow').click(function(event){
                var obj = this;
		var follow_id = $(obj).attr('dataid');
		var className = $(obj).attr('class');
                if(is_login({follow_id:follow_id},'gz') === false){
                    return false;
                }
		if(className == 'gz_btn'){
                        PERSON.follow(follow_id,function(status){
                            if(status === 2){
                                 $(obj).html('已关注');
                                 $(obj).attr('class','ygz_btn');
                            }
                        });
		}else{
			if(confirm('确定要取消关注？')){
                                PERSON.unfollow(follow_id,function(status){
                                    if(status === 2){
                                        $(obj).html('关注');
					$(obj).attr('class','gz_btn');
                                    }
                                });
			}
		}
		event.preventDefault();
	});
	
	//发送私信
	$(".sx_btn").click(function(event){
                var obj = this;
		var to_member_id = $(obj).attr('to_member_id');
		var to_member_name = $(obj).attr('to_member_name');
                if(is_login({'to_member_id':to_member_id,'to_member_name':to_member_name},'sx') === false){
                    return false;
                }
		$.post(sUrl+'/Message/check_letter',{'to_member_id':to_member_id},function(data){
			var res = eval(data);
			if(res.status === 2){
				var login = $('#js_sx_box');
				var floats = $('.floats');
				floats.css({width:$(document).width()+'px',height:$(document).height()+'px'}).show();
				var w = ($(window).width() - $('#js_sx_box').width())/2;
				var h = ($(window).height() - $('#js_sx_box').height())/2 + $(document).scrollTop();
				login.css({top:h+"px", left:w+"px"}).show();
				$('.tb_sx_username').html(to_member_name);
				$("#js_close_sx").bind("click", function(){ login.hide();floats.hide(); });    
			}else if(res.status === 1){
				INIT.error('不能给自己发送私信');
			}else{
				INIT.error('系统错误！');
			}
		});
		event.preventDefault();
	});
	
	$("#js_now_sx").click(function(event){
		if(is_login() === false){
                    return false;
                }
                var to_member_id = $('.sx_btn').attr('to_member_id');
		var content = $('#tb_sx_content').val();
		if(content === ''){
			alert('私信内容不能为空');
			$('#tb_sx_content').focus();
			return false;
		}
                PERSON.send_letter(to_member_id,content,token,prefix,function(status){
                    if(status === 2){
                        $('#tb_sx_content').val('');
                        $('#js_sx_box').hide();
                        $('.floats').hide();
                    }
                });
		event.preventDefault();
	});
});