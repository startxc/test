<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
    <head>
        <title>『PANSI管理平台』</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkcms.css"/>
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkmain.css"/>
        <script type="text/javascript" src="__ROOT__/Public/Js/jquery.min.js"></script>
    </head>
    <body scroll="no" class="objbody">

        <div id="header">
            <div id="logo_img">
                <!-- <img width="135" height="79" src="__ROOT__/Public/Images/logo2.png"/>-->
            </div>
            <div id="login_menu">
                <span>welcome to pansi</span>  
                <span>-</span>  
                <span><a href="javascript:;">槃思科技</a></span>  
                <span>-</span>
                <!--<span><a href="#">用户中心</a></span>-->  
                <!--<span>-</span>-->
                <span><a href="__APP__/Index" target="_blank">站点首页</a></span>  
                <span>-</span>
                <span><a href="__APP__/Admin/Common/clearCache">清除缓存</a></span>
                <span>-</span>
                <span><a href="__APP__/Admin/Public/logout">退出系统</a></span>
            </div>
            <div id="main_menu"> 
                <ul>
                    <?php if(is_array($listMenu)): $k = 0; $__LIST__ = $listMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li <?php if($k == count($listMenu)): ?>class="chosed"<?php endif; ?> ><a target="right" href="__APP__/Admin/<?php echo ($vo["url"]); ?>" hidefocus="true"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>

        <div id="middle" style="width: auto;" >

            <div class="col_menu col_left">
                <div id="scroll_menu" >
                    <?php if(is_array($listMenu)): $k = 0; $__LIST__ = $listMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="left_menu" <?php if($k != $listLen): ?>style="display: none;"<?php endif; ?> >
                            <h3> <?php echo ($vo["name"]); ?></h3>
                            <ul>
                                <?php if(is_array($vo["sub"])): $ck = 0; $__LIST__ = $vo["sub"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cd): $mod = ($ck % 2 );++$ck;?><li><a href="__APP__/Admin/<?php echo ($cd["name"]); ?>" target="right" hidefocus="true" <?php if($ck == 1): ?>class="hover"<?php endif; ?> > <?php echo ($cd["title"]); ?> </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>                                             
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>

                </div>
                <a href="javascript:void();" id="open_close" style="outline-style: none; outline-color: invert; outline-width: medium;" hideFocus="hidefocus" class="open" title="展开或收缩"><span style="display: none;" class="hidden">展开</span></a>
                <div class="clear"></div>
            </div>

            <div class="col_main col_mr8">
                <div id="nav_menu"> 位置 ： 网站首页 </div>
                <div id="col_out">
                    <div id="content" style="position: relative; overflow: hidden;"> 
                        <iframe name="right" id="right_main" src="__URL__/main" flag="网站首页" link="__URL__/main" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
                        <div  class="panel_nav">
                            <div id="panel_list"> 
                                <?php if(is_array($quick)): $i = 0; $__LIST__ = $quick;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span> 
                                        <a target="right" hidefocus="true" target="right" class="panel_link" href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a> 
                                        <a href="javascript:;" onclick="delete_panel(this);" flag="<?php echo ($vo["id"]); ?>" class="panel_delete" hidefocus="true"></a> 
                                    </span><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                            <div class="panel_add">
                                <a href="javascript:add_panel();" id="btn_add_panel" submiturl="__URL__/quickInsert" deleteurl="__URL__/quickDelete" class="panel_add" hidefocus="true"><em>添加</em></a>
                            </div>
                            <!--<div id="help" class="fav_help">
                                <a href="javascript:add_panel();" class="panel_news">新信息</a>
                            </div>-->
                        </div>
                    </div>   
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div id="btn_scroll" class="scroll">
            <a href="javascript:void(0)" class="prev" title="使用鼠标滚动右栏" onclick="menuScroll(1)"  hidefocus="true"></a>
            <a href="javascript:void(0)" class="next" title="使用鼠标滚动右栏" onclick="menuScroll(0)"  hidefocus="true"></a>
        </div>
        <div id="locker"> 
            <div class="lock_pop">锁屏状态</div> 
        </div>
        <div id="pop_window" style="display: none;" ></div>
        <script type="text/javascript" src="__ROOT__/Public/Js/thinkmain.js"></script>
    </body>
</html>