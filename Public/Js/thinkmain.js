/* 
 * Document   : thinkmain.js
 * Created on : 2012-6-2, 8:46:27
 * Author     : kewen
 * Description: 主框架初始化文件
*/
if(!Array.prototype.map) {
    Array.prototype.map = function(fn,scope) {
        var result = [],ri = 0;
        for (var i = 0,n = this.length; i < n; i++){
            if(i in this){
                result[ri++]  = fn.call(scope ,this[i],i,this);
            }
        }
        return result;
    };
}
var getWindowSize = function(){
    return ["Height","Width"].map(function(name){
        return window["inner"+name] ||
        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
    });
}
window.onload = function (){
    if(!+"\v1" && !document.querySelector) { // for IE6 IE7
        document.body.onresize = resize;
    } else { 
        window.onresize = resize;
    }
    function resize() {
        wSize();
        return false;
    }
}
function wSize(){
    //这是一字符串
    var str=getWindowSize();
    var strs= new Array(); //定义一数组
    strs=str.toString().split(","); //字符分割
    var heights = strs[0]-150,Body = $('body');
    $('#right_main').height(heights);   
    //iframe.height = strs[0]-46;
    if(strs[1]<980){
        $('#header').css('width',980+'px');
        $('#middle').css('width',980+'px');
        Body.attr('scroll','');
        Body.removeClass('objbody');
    }else{
        $('#header').css('width','auto');
        $('#middle').css('width','auto');
        Body.attr('scroll','no');
        Body.addClass('objbody');
    }
	
    var openClose = $("#right_main").height()+39;
    //$('#center_frame').height(openClose+9);
    $("#open_close").height(openClose+30);	
    $("#scroll_menu").height(openClose-20);
    windowW();
}
function windowW(){
    if($('#scroll_menu').height()<$(".left_menu").height()){
        $("#btn_scroll").show();
    }else{
        $("#btn_scroll").hide();
    }
}

//参数初始化
function body_init() {
    wSize();
    windowW();
    $("#open_close").data("clicknum", 0);
    $(".panel_nav").css("width", $("#content").width()-5);
    //兼容chrome
    if(window.navigator.userAgent.indexOf("Chrome")>=0) {
        $("#auto_menu").css("padding-top","11px");
    }
}
body_init();
            
//鼠标滚轮事件
(function(){
    var addEvent = (function(){
        if (window.addEventListener) {
            return function(el, sType, fn, capture) {
                el.addEventListener(sType, fn, (capture));
            };
        } else if (window.attachEvent) {
            return function(el, sType, fn, capture) {
                el.attachEvent("on" + sType, fn);
            };
        } else {
            return function(){};
        }
    })(),
    Scroll = document.getElementById('scroll_menu');
    // IE6/IE7/IE8/Opera 10+/Safari5+
    addEvent(Scroll, 'mousewheel', function(event){
        event = window.event || event ;  
        if(event.wheelDelta <= 0 || event.detail > 0) {
            Scroll.scrollTop = Scroll.scrollTop + 29;
        } else {
            Scroll.scrollTop = Scroll.scrollTop - 29;
        }
    }, false);

    // Firefox 3.5+
    addEvent(Scroll, 'DOMMouseScroll',  function(event){
        event = window.event || event ;
        if(event.wheelDelta <= 0 || event.detail > 0) {
            Scroll.scrollTop = Scroll.scrollTop + 29;
        } else {
            Scroll.scrollTop = Scroll.scrollTop - 29;
        }
    }, false);
	
})();
            
//上下滚动按钮
function menuScroll(num){
    var Scroll = document.getElementById('scroll_menu');
    if(num==1){
        Scroll.scrollTop = Scroll.scrollTop - 60;
    }else{
        Scroll.scrollTop = Scroll.scrollTop + 60;
    }
}

//左侧开关
$("#open_close").click(function(){
    if($(this).data('clicknum')==1) {
        $("html").removeClass("on");
        $(".col_left").removeClass("left_menu_on");
        $(this).removeClass("close");
        $(this).data('clicknum', 0);
        $(".scroll").show();
    } else {
        $(".col_left").addClass("left_menu_on");
        $(this).addClass("close");
        $("html").addClass("on");
        $(this).data('clicknum', 1);
        $(".scroll").hide();
    }
    $(".panel_nav").css("width", $("#content").width()-5);
    return false;
});

//菜单变换
$("#main_menu ul li").click(function(){
    var index = $(this).index();
    $("#scroll_menu div").eq(index).show().siblings().hide();
    $(this).addClass("chosed").siblings().removeClass("chosed");
    var nav = $(this).find("a").text();
    var tmp = $("#scroll_menu div").eq(index).find("a:eq(0)");
    var second = tmp.text();
    tmp.addClass("hover").parent().siblings().find("a").removeClass("hover");
    $("#right_main").attr("flag", second).attr("link", tmp.attr("href"));
    $("#nav_menu").html("位置 ： "+ nav +" > "+ second);
});
$("#main_menu ul li").hover(function(){
    $(this).addClass("hoverd");
},function(){
    $(this).removeClass("hoverd");
});
    
//左侧菜单变换
$(".left_menu ul li a").click(function(){
    $(this).addClass("hover").parent().siblings().find("a").removeClass("hover");
    var three = $(this).text();
    var second = $(this).parent().parent().siblings().text();
    $("#nav_menu").html("位置 ： "+second+'  >  '+three);
    $("#right_main").attr("flag", three).attr("link", $(this).attr("href"));;
});

//添加快捷链接
function add_panel() {
    var url = $("#right_main").attr("link");
    var title = $("#right_main").attr("flag");
    var isExist = false;
    if(url=="" || title=="") {
        return ;
    }
    $("#panel_list span .panel_link").each(function(k, v){
        var u = $(this).attr("href");
        if(url==u) {
            isExist = true;
        }
    });
    if(!isExist) {
        var action = $('#btn_add_panel').attr('submiturl');
        $.get(action, {url:url,name:title}, function(data){
            var d = eval(data);
            if(parseInt(d.status)==1) {
                var c = '<span> <a hidefocus="true" target="right" class="panel_link" href="'+ url +'">'+ title +'</a> <a href="javascript:;" onclick="delete_panel(this);" class="panel_delete" flag="'+ d.id +'" hidefocus="true"></a></span>';
                $("#panel_list").append(c);
            } else {
                alert(d.prompt);
            }
        });
    }
}

//删除链接地址
function delete_panel(t) {
    var id = parseInt($(t).attr("flag"));
    if(id>0) {
        var action = $('#btn_add_panel').attr('deleteurl');
        $.get(action, {id:id}, function(data){
            var d = eval(data);
            var s = parseInt(d.status);
            if(s==1) {
                $(t).parent().remove();
            }else {
                alert(d.prompt);
            }
        });
    }
}


