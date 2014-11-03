// JavaScript Document
$("input[type='text']").focus(function(){
		$(this).val("");
	});
	$(".subnav_list").hover(function(){
			$(".subnav_list a").next(".nav_kinds").hide();
			$(this).find(".nav_kinds").show();
			$(this).addClass("subnav_list_cur");
	}).mouseleave(function(){
			$(this).find(".nav_kinds").hide();
			$(this).removeClass("subnav_list_cur");
				
	}); 
  
	$(".upClass a").click(function(){
	  $(".upClass a").removeClass("uploadcur");
	  $(this).addClass("uploadcur");	 
	});
	$(".mypageTagesresult li").mouseenter(function(){
			$(this).find(".layerContent:eq(0)").show(); 
		}).mouseleave(function(){
			$(this).find(".layerContent:eq(0)").hide();
	});
	$(".albumshow_list").mouseenter(function(){
			$(this).find(".layerContent:eq(0)").show(); 
		}).mouseleave(function(){
			$(this).find(".layerContent:eq(0)").hide();
	});
	
	 $(".sellerGoods_list").mouseenter(function(){
			$(this).find(".layerContent:eq(0)").show(); 
		}).mouseleave(function(){
			$(this).find(".layerContent:eq(0)").hide();
	});
	
	$(".hotprobox li").mouseenter(function(){
		$(this).find(".layer").fadeIn();	
	}).mouseleave(function(){
		$(this).find(".layer").fadeOut();	
	});

//var main_left = Math.round($(".w1280").offset().left+1200)+"px";
var main_left = $(window).width()-70+"px";
var back_top_ele = "<a href='javascript:;' id='back_top' class='backup'><i>返回顶部</i></a>";
var back_top_sty = { bottom:"50px", left:main_left, position:"fixed", display:"none"};
$("body").append(back_top_ele);
$("#back_top").css(back_top_sty);
$(window).scroll(function(){
	if( $(window).scrollTop() > 200 ){
		if ($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
			$("#back_top").css({ display:"block", position:"absolute", top:$(window).scrollTop()+450+"px"});
		}else{
			$("#back_top").css({ display:"block", position:"fixed"});
		}
	}else{
		$("#back_top").hide();
	}
});

$(window).resize(function(){
	if($(window).width() <= 1000){
		//main_left = $(window).width()-20+"px";
	}else{
		//main_left = Math.round($(".main").offset().left+980)+"px";
	}
	main_left = $(window).width()-70+"px";
	$("#back_top").css("left",main_left);
});

$("#back_top").click(function(){
	$("html, body").animate({scrollTop:0}, 500);
	return false;
});