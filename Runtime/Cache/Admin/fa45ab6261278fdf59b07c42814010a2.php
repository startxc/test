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
    <span class="action_title"> <a href="__URL__">订单管理</a></span> <span class="action_module"> - 订单回收站列表 </span>

   <!-- <span class="action_span" > 
        <a href="__URL__/add">添加会员</a>
    </span>-->
    <div class="clear"> </div>
</div>

<div class="header box_category" id="box_category_list">
    <ul>
        <li class="box_first"> 
            <div class="box_tree"> 
                <ol> 
                    <?php if(is_array($first)): $i = 0; $__LIST__ = $first;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li pid="<?php echo ($vo["id"]); ?>" onclick="C.change(this);" > <?php echo ($vo["name"]); ?> </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ol>
            </div>
        </li>
        <li class="box_first"> 
            <div class="box_tree"> 
                
            </div>
        </li>
        <li class="box_first">
            <div class="box_tree"> 
                
            </div>
        </li>
    </ul>
    <div class="clear"> </div>
    <div class="box_control"> 
        <dt>您当前的选择的是:</dt>
        <dd> 
            <ol id="box_choose_category">
            <!--<li> 家具</li>
                <li> &nbsp;>&nbsp;家具</li>
                <li> &nbsp;>&nbsp;家具</li>-->
            </ol>
        </dd>
        <div class="clear"> </div>
    </div>
    <div class="box_submit"> 
        <input type="button" value="关闭添加模块" onclick="C.hideBox();" />
        <input type="button" value="现在发布商品" id="btn_open_now" />
        <!--<input type="button" value="新的页面添加" id="btn_open_new" />-->
    </div>
</div>

<div class="header">
    <form  method="get" action="__URL__">
        <img  class="action_title" src="__PUBLIC__/Images/icon_search.gif" height="22" border="0" alt="SEARCH">
        <span class="action_title">

            <!--<select id="wh_type" name="category" class="select">
                <option value="0"> 全部类型 </option>
                <option value="1" <?php if(($type) == "1"): ?>selected<?php endif; ?>> 普通用户 </option>
                <option value="2" <?php if(($type) == "2"): ?>selected<?php endif; ?>> 批发商 </option>
                <option value="3" <?php if(($type) == "2"): ?>selected<?php endif; ?>> 工厂 </option>
            </select>-->
           <!-- <select id="wh_pay_status" name="pay_status" class="select">
                <option value=""> 全部 </option>
                <option value="0" <?php if(($pay_status) == "0"): ?>selected<?php endif; ?>> 未付款 </option>
                <option value="1" <?php if(($pay_status) == "1"): ?>selected<?php endif; ?>> 已付款 </option>
            </select>

            <select id="wh_order_status" name="order_status" class="select">
                <option value=""> 全部 </option>
                <option value="created" <?php if(($order_status) == "created"): ?>selected<?php endif; ?> > 待付款 </option>
                <option value="canceled" <?php if(($order_status) == "canceled"): ?>selected<?php endif; ?> > 已取消 </option>
                <option value="payed" <?php if(($order_status) == "payed"): ?>selected<?php endif; ?> > 已付款 </option>
                <option value="refund" <?php if(($order_status) == "refund"): ?>selected<?php endif; ?> > 已退款 </option>
                <option value="shipped_part" <?php if(($order_status) == "shipped_part"): ?>selected<?php endif; ?> > 已部分发货 </option>
                <option value="shipped" <?php if(($order_status) == "shipped"): ?>selected<?php endif; ?> > 已全部发货 </option>
                <option value="received" <?php if(($order_status) == "received"): ?>selected<?php endif; ?> > 已收货 </option>
            </select>-->

        </span>

        <span class="action_title"> 
            <input id="text_keywords" class="input_keywords" type="text" name="keywords" value="输入订单号" />
        </span>

        <span class="action_title"> 
            <input id="btn_search" class="input_keywords button" type="button" value="点击搜索" />
        </span>

        <!--<span class="action_span" > 
            <a href="javascript:C.showBox();">添加商品</a>
        </span>-->
        <div class="clear"> </div>
    </form>
</div>

<div id="middle">
    <form method="post" action="__URL__/batchRecover" onsubmit="return batch(this);" >
        <!--列表-->
        <div class="list_div">
            <table class="list_table" cellspacing="1" cellpadding="3">
                <tr>
                    <!--<th width="65"> 选择选项 </th>-->
                    <th width="65"> 订单编号 </th>
                    <th> 订单号 </th>
                    <th width="160"> 收货人 </th>
                    <th width="250"> 收货地址 </th>
                    <th width="150"> 联系电话</th>
                    <th width="150">下单时间</th>
                    <th width="80"> 支付状态 </th>
                    <th width="80"> 订单状态 </th>
                    <th width="120"> 操作选项 </th>
                </tr>    
                
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="list_tr">
                        <td class="td_items"> <input class="list_checkbox" type="checkbox" name="items[]" value="<?php echo ($vo["id"]); ?>"/> <?php echo ($vo["id"]); ?> </td>
                        <td> <?php echo ($vo["order_no"]); ?> </td>
                        <td> <?php echo ($vo["consignee"]); ?> </td>
                        <td class="td_center_show"><?php echo (getareaname($vo["province_id"])); ?>-<?php echo (getareaname($vo["city_id"])); ?>-<?php echo (getareaname($vo["area_id"])); ?></td>
                        <td class="td_center_show"><?php echo ($vo["mobile"]); ?></td>
                        <td class="td_center_show"><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
                        <td class="td_center_show">
                        	<?php if(($vo['pay_status']) == "0"): ?>未付款<?php endif; ?>
                  			<?php if(($vo['pay_status']) == "1"): ?><font color="#FF0000">已付款</font><?php endif; ?> 
                            <?php if(($vo['pay_status']) == "2"): ?>已退款<?php endif; ?>
                            <?php if(($vo['pay_status']) == "3"): ?><font color="#FF0000">部分付款</font><?php endif; ?>    	    
                        </td>
                        <td class="td_center_show">
                        	<?php if(($vo['order_status']) == "created"): ?>待付款<?php endif; ?>
                            <?php if(($vo['order_status']) == "canceled"): ?>已取消<?php endif; ?>
                            <?php if(($vo['order_status']) == "pay_deposit"): ?>已付定金<?php endif; ?>
                            <?php if(($vo['order_status']) == "payed"): ?>已付款<?php endif; ?>
                            <?php if(($vo['order_status']) == "refund_appiled "): ?>申请退款<?php endif; ?>
                            <?php if(($vo['order_status']) == "refund"): ?>已退款<?php endif; ?>
                            <?php if(($vo['order_status']) == "shipped_part"): ?>已部分发货<?php endif; ?>
                            <?php if(($vo['order_status']) == "shipped"): ?>已全部发货<?php endif; ?>
                            <?php if(($vo['order_status']) == "received"): ?>已收货<?php endif; ?>
                        </td>
                        <td class="td_center_show"> 
                            <span class="control_span">
                                <!--<a href="" title="查看订单"><img border="0" src="__ROOT__/Public/Images/icon_view.gif"/></a>-->
                                <a href="__URL__/recover/id/<?php echo ($vo["id"]); ?>" title="还原">还原</a> |
                                <a href="__URL__/delete/id/<?php echo ($vo["id"]); ?>);" title="永久删除">永久删除</a>
                                <!--<a href="javascript:deletes(<?php echo ($vo["id"]); ?>);" title="删除该商品"><img border="0" src="__ROOT__/Public/Images/icon_drop.gif"/></a>-->
                            </span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            
            <table class="control_table">
            <tr>
                <td align="right" style="padding-right: 10px; text-align: right;" > <?php echo ($page); ?> </td>
            </tr> 
        </table>
        
        <table class="control_table">
            <tr>
                <td width="60" class="td_title"> 批量操作 </td>
                <td id="box_batch_action">
                    <span> <a href="javascript:chooseAll();">全选</a>/<a href="javascript:quitAll();">全不选</a> </span>
                    <span> <input class="batch_dothing"  type="radio" name="opaction" value="status"> 批量更改</span>
                </td>
            </tr> 
        </table>
       
        <table class="control_table batch_table" id="action_status" style="display: none;">
            <tr>
                <td width="60" class="td_title"> 选择操作 </td>
                <td>
                    <span> <input type="radio" name="statusvalue" checked="checked" value="1" /> 永久删除</span>
                    <span> <input type="radio" name="statusvalue" value="2"> 还原</span>
                </td>
            </tr>                    
        </table> 
        
        <table class="submit_table">
            <tr>
                <td> 
                    <span><input type="submit" value="提交保存" /></span>
                   <!-- <span><input type="reset" value="全部重置" /></span>-->
                </td>
            </tr>                    
        </table>     
           
        </div>
    </form>
</div>

<script type="text/javascript">
    
    function batch(btn) {
       var action = $("#box_batch_action input:checked").val();
       if(action!=="sort") {
           var len = $(".td_items input:checked").length;
           if(len===0) {
               alert("请选择要操作的项目!"); return false;
           }
       }
    }
    
    function chooseAll() {
        $(".td_items input").attr("checked", "checked");
    }
    function quitAll() {
        $(".td_items input").removeAttr("checked");
    }
    function recycle(id) {
        if(window.confirm("您确定把商品添加到回收站吗？")) {
            window.location.href="__URL__/recycle/id/"+id;
        }
    }
    
    $(function(){
        //点击
        $("#btn_search").bind("click", function(){
			var str = ''; 
            var key = $("#text_keywords").val();
            var words = "";
            if(key==="输入订单号") {
                words = "";
            }else {
                words = key;
            }
			str = "keywords/" + words;
            window.location.href="__URL__/recycleList/"+str;
        });
        
        //搜索
        $("#text_keywords").bind("click", function(){
            var val = $(this).val();
            if(val==="输入订单号") {
                $(this).val("");
            }
        });
        
        //表格变色
        $(".list_tr").hover(function(){
            $(this).find("td").css("background", "#F4FAFB");
        },function(){
            if(!$(this).find("td").find("input").attr("checked")) {
                $(this).find("td").css("background", "#FFFFFF");
            }
        });
        //checkbox的选择
        $(".list_checkbox").click(function(){
            if($(this).attr("checked")) {
                $(this).parent().parent().find("td").css("background", "#F4FAFB");
            } else {
                $(this).parent().parent().find("td").css("background", "#FFFFFF");
            }
        });
        //批量操作
        $(".batch_dothing").click(function(){
            if($(this).val()!=="sort") {
                $(".batch_table").hide();
                $("#action_"+$(this).val()).show();
            }else {
                $(".batch_table").hide();
            }
        });
    });
    
    //类目变色和选择
    var C = (function($, my){
        my.category=eval('(<?php echo ($category); ?>)');
        my.initLi = function() {
            $(".box_tree ol li").hover(function(){
                $(this).addClass("hover");
            },function(){
                $(this).removeClass("hover");
            });
        },
        my.change = function(li) {
            $(li).addClass("current").siblings().removeClass("current");
            var pid = $(li).attr("pid");
            var iid = $(li).parent().parent().parent().index()+1;
            var html = "";
            $.each(C.category,function(k,v){
                if(pid===v.pid) {
                    html += "<li pid="+ v.id +" onclick='C.change(this);'> "+ v.name +" </li>";
                }
            });
            $("#box_category_list .box_first").eq(iid).find("div").html("<ol>"+ html +"</ol>");
            if(iid===1) {
                $("#box_choose_category").html("<li>"+$(li).html()+"</li>");
            }else {
                $("#box_choose_category").find("li:gt("+ (iid-2) +")").remove();
                $("#box_choose_category").append("<li sign='"+ pid +"'>&nbsp;>&nbsp;"+$(li).html()+"</li>");
            }
            my.initLi();
        },
        my.showBox = function() {
            $("#box_category_list").show();
        };
        my.hideBox = function() {
            $("#box_category_list").hide();
        };
        return my;
    })(jQuery, C || {});
    C.initLi();
</script>


<!--加载页面脚部文件-->
<!--        <div id="footer">
            共执行 29 个查询，用时 0.158485 秒，Gzip 已禁用，内存占用 3.546 MB
            <br/>
            版权所有 © 2012-2012 xxxx科技有限公司，并保留所有权利。
        </div>-->

    </body>
</html>