<?php if (!defined('THINK_PATH')) exit();?><!--加载页面头部文件-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkcms.css"/>
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkright.css"/>
        <script type="text/javascript" src="__ROOT__/Public/Js/jquery.min.js"></script>
        <script type="text/javascript">
            function init() { $("#loader").hide(); }
        </script>
    </head>
    <body onload="init()" >
        <div id="loader"> 页面加载中... </div>

<style type="text/css">
    select,input { outline: none; }
    .select { float: left; padding: 3px;  margin: 0 6px; height: 24px; line-height: 24px; }
    .input_keywords { padding-left: 5px; margin-left: 5px; outline: none; height: 22px; border: 1px solid #CCC; }
    .button { height: 23px; padding-right: 5px; background: #EEE; }
    
    .box_category { padding: 15px; display: none; }
    .box_category .box_first { width: 230px; height: 290px; float: left; background: #FFF; border: 1px solid #BBDDE5;  }
    
    .box_tree { padding: 5px; width: 220px; height: 280px; overflow-x: hidden; overflow-y: auto; }
    .box_tree ol li { color: #555; cursor: pointer; float: none; border: none; background: none; margin: 0; text-align: left; padding-left: 15px; width: 200px; height: 24px; line-height: 24px; }
    .box_tree ol li.current { background: #DFF1FB; }
    .box_tree ol li.hover { background: #EEEEEE; }
    
    .box_control { color: #444; border: 1px solid #FEDBAB; background: #FFFAF2; padding: 10px; margin-top: 10px; }
    .box_control dt { float: left; font-weight: bolder; width: 115px; }
    .box_control dd { float: left; }
    .box_control dd ol li { float: left; list-style: none; }
    
    .box_submit { padding: 0; margin-top: 10px; }
    .box_submit input { padding: 5px 10px; margin-right: 10px; border: 1px solid #278296; background: #DDEEF2; }
</style>

<div class="header">
    <span class="action_title"> <a href="__URL__/group">伙拼管理</a></span> <span class="action_module"> - 伙拼审核列表 </span>
    <div class="clear"> </div>
</div>
<div class="header">
    <form  method="get" action="__URL__/groupApply">
        <img  class="action_title" src="__PUBLIC__/Images/icon_search.gif" height="22" border="0" alt="SEARCH">
        <span class="action_title"> 
            <input id="text_keywords" class="input_keywords" type="text" name="keywords" value="输入关键字" />
        </span>

        <span class="action_title"> 
            <input id="btn_search" class="input_keywords button" type="button" value="点击搜索" />
        </span>
        <div class="clear"> </div>
    </form>
</div>

<div id="middle">
        <!--列表-->
        <div class="list_div">
            <table class="list_table" cellspacing="1" cellpadding="3">
                <tr>
                    <th width="65"> 申请编号 </th>
                    <th width="150"> 商品名称 </th>
                    <th width="60"> 申请人 </th>
                    <th width="60"> 申请时间 </th>
                    <th width="205"> 操作选项 </th>
                </tr>    
                
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="list_tr">
                        <td class="td_items"> <?php echo ($vo["id"]); ?> </td>
                        <td> <?php echo ($vo["name"]); ?> </td>
                        <td> <?php echo ($vo["member_name"]); ?>  </td>
                        <td> <?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?>  </td>
                        <td class="td_center_show"> 
                            <span class="control_span">
                                <a href="__URL__/groupApplyCheck/id/<?php echo ($vo["id"]); ?>" title="审核">审核</a>
                            </span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            
            <table class="control_table">
            <tr>
                <td align="right" style="padding-right: 10px; text-align: right;" > <?php echo ($page); ?> </td>
            </tr> 
        </table>
        
            
        </div>
</div>

<script type="text/javascript">
    var isSubmitButton = false;
    
    $(function(){
        //点击
        $("#btn_search").bind("click", function(){
            var cat = $("#wh_cat").val();
            var key = $("#text_keywords").val();         
            var words = "";
            if(key==="输入关键字") {
                words = "";
            }else {
                words = key;
            }
            window.location.href="__URL__/groupApply/cid/"+ cat + "/keywords/" + words;
        });
        
        //搜索
        $("#text_keywords").bind("click", function(){
            var val = $(this).val();
            if(val==="输入关键字") {
                $(this).val("");
            }
        });
        
        //表格变色
        $(".list_tr").hover(function(){
            $(this).find("td").css("background", "#F4FAFB");
        },function(){
            $(this).find("td").css("background", "#FFFFFF");
        });  
     
    });
    
</script>

<!--加载页面脚部文件-->
<!--        <div id="footer">
            共执行 29 个查询，用时 0.158485 秒，Gzip 已禁用，内存占用 3.546 MB
            <br/>
            版权所有 © 2012-2012 xxxx科技有限公司，并保留所有权利。
        </div>-->

    </body>
</html>