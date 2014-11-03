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
select,input{outline: none;}.select{float: left;padding: 3px;margin: 0 6px;height: 24px;line-height: 24px;}.input_keywords{padding-left: 5px;margin-left: 5px;outline: none;height: 22px;border: 1px solid #CCC;}.button{height: 23px;padding-right: 5px;background: #EEE;}.box_category{padding: 15px;display: none;}.box_category .box_first{width: 230px;height: 290px;float: left;background: #FFF;border: 1px solid #BBDDE5;}.box_tree{padding: 5px;width: 220px;height: 280px;overflow-x: hidden;overflow-y: auto;}.box_tree ol li{color: #555;cursor: pointer;float: none;border: none;background: none;margin: 0;text-align: left;padding-left: 15px;width: 200px;height: 24px;line-height: 24px;}.box_tree ol li.current{background: #DFF1FB;}.box_tree ol li.hover{background: #EEEEEE;}.box_control{color: #444;border: 1px solid #FEDBAB;background: #FFFAF2;padding: 10px;margin-top: 10px;}.box_control dt{float: left;font-weight: bolder;width: 115px;}.box_control dd{float: left;}.box_control dd ol li{float: left;list-style: none;}.box_submit{padding: 0;margin-top: 10px;}.box_submit input{padding: 5px 10px;margin-right: 10px;border: 1px solid #278296;background: #DDEEF2;}
</style>
<div class="header">
	<span class="action_title"><a href="__URL__">订单管理</a></span><span class="action_module"> - 订单列表</span>
	<div class="clear"></div>
</div>
<div class="header">
	<form  method="get" action="__URL__">
		<img  class="action_title" src="__PUBLIC__/Images/icon_search.gif" height="22" border="0" alt="SEARCH">
        <span class="action_title">
            <select id="wh_pay_status" name="pay_status" class="select">
                <option value=""> 全部 </option>
                <option value="0" <?php if(($pay_status) == "0"): ?>selected<?php endif; ?>> 未付款 </option>
                <option value="1" <?php if(($pay_status) == "1"): ?>selected<?php endif; ?>> 已付款 </option>
            </select>
            <select id="wh_order_status" name="order_status" class="select">
                <option value=""> 全部 </option>
                <option value="created" <?php if(($order_status) == "created"): ?>selected<?php endif; ?> > 待付款 </option>
                <option value="canceled" <?php if(($order_status) == "canceled"): ?>selected<?php endif; ?> > 已取消 </option>
                <option value="payed" <?php if(($order_status) == "payed"): ?>selected<?php endif; ?> > 待发货 </option>
                <option value="refund" <?php if(($order_status) == "refund"): ?>selected<?php endif; ?> > 已退款 </option>
                <option value="shipped" <?php if(($order_status) == "shipped"): ?>selected<?php endif; ?> > 已发货 </option>
                <option value="received" <?php if(($order_status) == "received"): ?>selected<?php endif; ?> > 已收货 </option>
            </select>
        </span>
        <span class="action_title"> 
            <input id="text_keywords" class="input_keywords" type="text" name="keywords" <?php if(!empty($keywords)): ?>value="<?php echo ($keywords); ?>" /><?php else: ?> value="输入订单号" /><?php endif; ?>
        </span>
        <span class="action_title"> 
            <input id="btn_search" class="input_keywords button" type="button" value="点击搜索" />
        </span>
        <div class="clear"> </div>
    </form>
</div>
<div id="middle">
    <form method="post" action="__URL__/batch" onsubmit="return batch(this);" >
		<div class="list_div">
            <table class="list_table" cellspacing="1" cellpadding="3">
                <tr>
                    <th width="65">订单编号</th>
                    <th>订单号</th>
                    <th width="150">收货人</th>
                    <th width="250">收货地址</th>
                    <th width="150">联系电话</th>
                    <th width="150">下单时间</th>
                    <th width="80">支付状态</th>
                    <th width="80">订单状态</th>
                    <th width="80">操作选项</th>
                </tr>
                <?php if(is_array($orderList)): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="list_tr">
                        <td class="td_items"><?php echo ($vo["id"]); ?></td>
                        <td><?php echo ($vo["order_no"]); ?></td>
                        <td><?php echo ($vo["consignee"]); ?></td>
                        <td class="td_center_show"><?php echo (getareaname($vo["province_id"])); ?>-<?php echo (getareaname($vo["city_id"])); ?>-<?php echo (getareaname($vo["area_id"])); ?></td>
                        <td class="td_center_show"><?php echo ($vo["mobile"]); ?></td>
                        <td class="td_center_show"><?php echo (date('Y-m-d H:i:s',$vo["create_time"])); ?></td>
                        <td class="td_center_show">
                        	<?php if(($vo['pay_status']) == "0"): ?>未付款<?php endif; ?>
                  			<?php if(($vo['pay_status']) == "1"): ?><font color="#FF0000">已付款</font><?php endif; ?> 
                        </td>
                        <td class="td_center_show">
                       		<?php if(($vo['order_status']) == "created"): ?>待付款<?php endif; ?>
                           	<?php if(($vo['order_status']) == "canceled"): ?>已取消<?php endif; ?>
                           	<?php if(($vo['order_status']) == "payed"): ?>待发货<?php endif; ?>
                           	<?php if(($vo['order_status']) == "refund_appiled "): ?>申请退款<?php endif; ?>
                           	<?php if(($vo['order_status']) == "refund"): ?>已退款<?php endif; ?>
                           	<?php if(($vo['order_status']) == "shipped"): ?>已发货<?php endif; ?>
                           	<?php if(($vo['order_status']) == "received"): ?>已收货<?php endif; ?>
                        </td>
                        <td class="td_center_show" style="text-align:center;"> 
                        	<span class="control_span">
                                <a href="__URL__/editOrder/id/<?php echo ($vo["id"]); ?>" title="编辑订单"><img border="0" src="__ROOT__/Public/Images/icon_edit.gif"/></a>   
                            </span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <table class="control_table">
	            <tr>
	                <td align="right" style="padding-right:10px; text-align:right;"><?php echo ($page); ?></td>
	            </tr> 
        	</table>         
        </div>
    </form>
</div>
<script type="text/javascript">  
$(function(){
	//点击
	$('#btn_search').bind('click', function() {
		var str = '';
    	var pay_status = $('#wh_pay_status').val();
		if (pay_status != '') {
			str = 'pay_status/' + pay_status + '/';	
		}
        var order_status = $('#wh_order_status').val();
		if (order_status != '') {
			str = str + 'order_status/' + order_status + '/';	
		} 
		var key = $('#text_keywords').val();
        var words = '';
        if (key === '输入订单号') {
        	words = '';
        } else {
        	words = key;
        }
		str = str + 'keywords/' + words;
        window.location.href = '__URL__/index/' + str;
     });
        
	//搜索
	$('#text_keywords').bind('click', function() {
		var val = $(this).val();
        if(val === '输入订单号') {
        	$(this).val('');
        }
	});
        
	//表格变色
	$('.list_tr').hover(function(){
		$(this).find('td').css('background', '#F4FAFB');
	}, function(){
		if (!$(this).find('td').find('input').attr('checked')) {
		    $(this).find('td').css('background', '#FFFFFF');
		}
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