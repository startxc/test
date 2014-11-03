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
.list_div input, select, textarea {
	outline: none;
}
.list_div .add_input_text {
	outline: none;
	padding-left: 3px;
}
.input {
	float: left;
	height: 22px;
	margin: 0 5px;
	padding-left: 3px;
	border: 1px solid #CCC;
}
.select {
	float: left;
	padding: 3px;
	margin-right: 5px;
	height: 24px;
	line-height: 24px;
}
.special {
	margin: 0 5px;
	background: #DDEEF2;
	height: 19px;
	line-height: 19px;
	display: inline-block;
	float: left;
	padding: 1px 5px;
	border: 1px solid #91C4D0;
	border-right: 2px solid #91C4D0;
	border-bottom: 2px solid #91C4D0;
}
.list_div a.special:hover {
	border: 1px solid #91C4D0;
}
.bright {
	color:#f60;
}
.box_cat_list {
	display: inline-block;
	height: 24px;
	line-height: 24px;
	padding: 0 3px;
	float: left;
}
.img {
	display: inline-block;
	margin-right: 3px;
	vertical-align: middle;
	width: 14px;
	height: 14px;
	border: 1px solid #E4E4E4;
}
.input_prompt {
	margin-left: 10px;
	color: #f60;
}
.box_basic_property, .box_sku_property {
	width: 80%;
	background: #F8F8F8;
	border: 1px solid #ECECEC;
	padding: 0 20px 20px 20px;
}
.box_basic_property ul li {
	margin-top: 20px;
}
.box_basic_property ul li span {
	float: left;
	margin-right: 10px;
	height: 18px;
	line-height: 18px;
	padding: 3px;
	display: inline-block;
}
.box_sku_property input {
	vertical-align: middle;
}
.box_sku_property .box_sku_group {
	margin-top: 20px;
}
.box_sku_property ul li {
	width: 140px;
	height: 25px;
	line-height: 25px;
	float: left;
}
.box_sku_property ul li label.label_name {
	white-space: nowrap;
	display: inline-block;
	vertical-align: middle;
	width: 85px;
	height: 22px;
	line-height: 22px;
	overflow: hidden;
}
.box_upload_icon {
	padding: 0;
	margin: 10px 0 0 0;
}
.box_upload_icon table {
	border-collapse: collapse;
}
.box_upload_icon table tr th {
	background: #EDEDED;
	font-weight:400;
}
.box_upload_icon table tr td, th {
	padding: 5px 10px 5px 10px;
	border: 1px solid #D7D7D7;
}
.btn_img {
	padding-left: 0;
	border: 1px solid #D7D7D7;
	background: #F8F8F8;
	padding: 3px 10px 3px 10px;
	text-align: center;
}
.left_img {
	margin-right: 7px;
}
.input_store {
	width: 80px;
	padding-left: 3px;
	border: 1px solid #C2D1D8;
	height: 20px;
	line-height: 20px;
}
.box_img_list {
	width: 100%;
	border: 1px solid #ECECEC;
}
.box_header {
	height: 32px;
	padding: 10px 20px 0;
	background: #F8F8F8;
}
.box_header span {
	background: #ECECEC;
	height: 32px;
	line-height: 32px;
	display: inline-block;
	padding: 0 12px;
	margin-right: 12px;
	cursor: pointer;
}
.box_header span.hover {
	background: #FFF;
}
.box_middle {
}
.box_button_upload {
	height: 100px;
	margin: 30px;
}
.box_button_upload .upload_field {
	height: 25px;
	line-height: 25px;
}
.box_button_upload .upload_field span {
	position: relative;
	display: inline-block;
	width: 90px;
	height: 25px;
	overflow: hidden;
}
.box_button_upload .upload_field .file_upload_field {
	opacity: 0;
	position: absolute;
	width: 200px;
	right: 0;
	height: 25px;
}
.box_button_upload .upload_prompt {
	color: #AAA;
	margin-top: 15px;
}
.box_button_upload .upload_prompt ol li {
	margin-left: 14px;
	margin-top: 8px;
}
.box_footer {
	padding: 15px;
	margin: 2px;
	background: #F8F8F8;
}
.box_gallery ul li {
	float: left;
	position: relative;
	margin-right: 10px;
	margin-top: 15px;
	width: 90px;
	height: 90px;
	text-align: center;
	line-height: 90px;
	border:1px solid #CDCDCD;
}
.box_gallery ul li span {
	display: inline-block;
	padding: 2px;
	width: 86px;
	height: 86px;
	background: url(__ROOT__/Public/Images/loading3.gif) no-repeat center;
}
.box_gallery ul li.main {
	border: 1px solid #FFC097;
}
.box_gallery ul li.first {
	border: 1px solid #FFC097;
}
.box_gallery ul li .preview {
	margin: 2px;
	width: 86px;
	height: 86px;
	line-height: 86px;
}
.box_gallery ul li .control {
	display: none;
	background: #666;
	position: absolute;
	height: 20px;
	width: 86px;
	left: 2px;
	bottom: 2px;
}
.box_gallery ul li .control a {
	float: left;
	font: "宋体";
	font-size: 10px;
	color: #FFF;
	display: inline-block;
	text-align: center;
	width: 43px;
	height: 20px;
	line-height: 20px;
}
.box_gallery ul li .control a:hover {
	color: #f60;
}
.box_gallery ul .hover .control {
	display: inline-block;
	opacity: 0.8;
}
.box_goods_gallery ul li .control a {
	width: 28px;
}
.box_goods_gallery ul li .left_arrow {
	background: url(__ROOT__/Public/Images/icon_control.png) no-repeat;
	background-position: 0 0;
}
.box_goods_gallery ul li .delete_arrow {
	background: url(__ROOT__/Public/Images/icon_control.png) no-repeat;
	background-position: -28px 0;
}
.box_goods_gallery ul li .right_arrow {
	background: url(__ROOT__/Public/Images/icon_control.png) no-repeat;
	background-position: -56px 0;
}
.box_img_upload {
	display: none;
	margin-top: 10px;
	border: solid 1px #AED3FF;
}
.box_img_upload .box_header {
	background: #E7F4FD;
}
.box_prompt_msg {
	margin-top: 10px;
	display: inline-block;
	background: #E5F4FE;
	border: 1px solid #3FB2FE;
	padding: 3px 5px 3px 3px;
}
.box_prompt_msg p {
	float: left;
	margin-left: 5px;
}
.icon_attention {
	width: 16px;
	height: 16px;
	float: left;
	line-height: 16px;
	display:inline-block;
	background: url(__ROOT__/Public/Images/prompt.png) no-repeat;
	background-position:0 -60px;
}
.box_other_item ul li {
	float: left;
	height: 24px;
	line-height: 24px;
	width: 150px;
	overflow: hidden;
}
.box_other_item ul li input {
	vertical-align: middle;
}
.box_other_item ul li label {
	display: inline-block;
	width: 130px;
	height: 22px;
	line-height: 22px;
	vertical-align: middle;
	overflow: hidden;
}
/*编辑器自定义按钮*/
    .ke-icon-selfimg {
	background-image: url(__ROOT__/Public/Js/kindeditor-4.1.7/themes/default/default.gif);
	background-position: 0px -495px;
	width: 16px;
	height: 16px;
}
.box_sku_img {
	display: inline-block;
	position: relative;
	width: 70px;
	height: 23px;
}
.box_sku_img .choose_sku_file {
	position: absolute;
	right: 0;
	top: 0;
	opacity: 0;
}
.box_sku_ctrl {
	display: none;
}
.box_sku_ctrl a {
	float:right;
	height:23px;
	line-height:23px;
	margin-left: 5px;
}
.textarea_border {
	border-color:#1px solid #C2D1D8;
	width:256px
}
.open {
	display:none
}
</style>

<div class="header"> <span class="action_title"> <a href="#">订单管理</a></span> <span class="action_module"> - 编辑订单 </span> <span class="action_span" > <a href="__URL__">订单列表</a> </span>
  <div class="clear"> </div>
</div>
<div id="middle">
<!--<form method="post" action="__URL__/insert" enctype="multipart/form-data" >--> 
<!--列表-->
<div class="list_div">
<input id="text_gid" type="hidden" value="<?php echo ($info['id']); ?>" name="info[id]" />
<table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0;">
  <tr>
    <td width="80" align="center"> 订单编号 </td>
    <td width="150"><?php echo ($info["order_no"]); ?></td>
    <td width="80" align="center">下单时间</td>
    <td width="150"><?php echo (date('Y-m-d H:i',$info["create_time"])); ?></td>
    <td width="80" align="center">订单状态</td>
    <td><?php if(($info["order_status"]) == "created"): ?>待付款<?php endif; ?>
      <?php if(($info["order_status"]) == "canceled"): ?>已取消<?php endif; ?>
      <?php if(($info["order_status"]) == "payed"): ?>待发货&nbsp;&nbsp;
      	<input id="shipping-<?php echo ($info["id"]); ?>" type="reset" value="点击发货" style="padding:3px 5px;"/>
		<input id="no-<?php echo ($info["id"]); ?>" value="" type="text" placeholder="请输入快递单号" style="display:none;width:120px;height:22px;"/>
        <input id="sure-<?php echo ($info["id"]); ?>" type="reset" value="确认" style="display:none;padding:3px 5px;"/><?php endif; ?>
      <?php if(($info["order_status"]) == "refund_appiled"): ?>已申请退款<?php endif; ?>
      <?php if(($info["order_status"]) == "refund"): ?>已退款<?php endif; ?>
      <?php if(($info["order_status"]) == "shipped"): ?>已发货&nbsp;&nbsp;(快递单号：<?php echo ($info["shipping_no"]); ?>)<?php endif; ?>
      <?php if(($info["order_status"]) == "received"): ?>已收货<?php endif; ?>
  </tr>
  <tr>
    <td width="100" align="center">支付状态</td>
    <td id="pay_status">
    	<?php if(($info["pay_status"]) == "0"): ?>未付款<?php endif; ?>
      	<?php if(($info["pay_status"]) == "1"): ?>已付款<?php endif; ?>
    </td>
    <td width="100" align="center"> 付款方式名称 </td>
    <td><?php if($info["pay_status"] == 1): if(($info["pay_method"]) == "alipay"): ?>支付宝<?php endif; if(($info["pay_method"]) == "wechat"): ?>微信支付<?php endif; endif; ?></td>
    <td width="100" align="center"> 付款时间 </td>
    <td><?php if(!empty($info["pay_time"])): echo (date('Y-m-d H:i',$info["pay_time"])); endif; ?></td>
  </tr>
  <tr>
    
    <td width="100" align="center">订单类型</td>
    <td width="100" ><?php if(($info["order_type"]) == "normal"): ?>普通订单
        <?php else: ?>
        伙拼订单<?php endif; ?></td>
    <td width="100" align="center">商品总金额</td>
    <td><?php echo ($info["goods_amount"]); ?></td>
    <td align="center">订单总金额</td>
    <td><?php echo ($info["order_amount"]); ?></td>
  </tr>
</table>
<table class="add_table close" cellspacing="1" cellpadding="3"  style="padding: 10px 0; border-top:1px solid #BBDDE5">
  <tr>
    <td width="100" align="center"> 收货人 </td>
    <td><?php echo ($info["consignee"]); ?></td>
    <td></td>
    <td clospan="2">
      <?php if(($info["order_status"]) == "created"): ?><input id="edit_addr" type="reset" value="编辑收货地址" style="padding:3px 5px; float:right"/><?php endif; ?>
      <?php if(($info["order_status"]) == "payed"): ?><input id="edit_addr" type="reset" value="编辑收货地址" style="padding:3px 5px; float:right"/><?php endif; ?></td>
  </tr>
  <tr>
    <td width="100" align="center">收货地址</td>
    <td colspan="3"><?php echo (getareaname($info["province_id"])); echo (getareaname($info["city_id"])); echo (getareaname($info["area_id"])); ?>-<?php echo ($info["address"]); ?></td>
  </tr>
  <tr>
<!--    <td width="100" align="center"> 邮编 </td>
    <td width="100"><?php echo ($info["zipcode"]); ?></td>-->
    <td width="100" align="center"> 电话 </td>
    <td width="100"><?php echo ($info["tel"]); ?></td>
    <td width="100" align="center"> 手机 </td>
    <td><?php echo ($info["mobile"]); ?></td>
  </tr>
  <tr>
    <td width="100" align="center">买家留言</td>
    <td><?php echo ($info["buyer_note"]); ?></td>
  </tr>
</table>
<table class="add_table open" cellspacing="1" cellpadding="3"  style="padding: 10px 0; border-top:1px solid #BBDDE5">
  <tr>
    <td width="100" align="center"> 收货人 </td>
    <td colspan="3"><input id="consignee" type="text" class="add_input_text" name="info[consignee]" value="<?php echo ($info["consignee"]); ?>">
      <input id="cannel_addr" type="reset" value="取消编辑" style="padding:3px 5px; float:right"/></td>
  </tr>
  <tr>
    <td width="100" align="center">收货地址</td>
    <td colspan="3"><select name="info[province_id]" id="province" class="select">
        <option value="" >-请选择-</option>
      </select>
      <select name="info[city_id]" id="city" class="select">
        <option value="" >-请选择-</option>
      </select>
      <select name="info[area_id]" id="area" class="select">
        <option value="" >-请选择-</option>
      </select>
      <input type="text" name="info[address]" id="address" value="<?php echo ($info["address"]); ?>"  class="add_input_text"/></td>
  </tr>
  <tr> 
    <!--<td width="100" align="center"> 邮编 </td>
    <td width="100"><input id="goods_name" type="text" class="add_input_text" name="info[zipcode]" value="<?php echo ($info["zipcode"]); ?>"></td>-->
    <td width="100" align="center"> 手机 </td>
    <td width="100"><input id="mobile" type="text" class="add_input_text" name="info[mobile]" value="<?php echo ($info["mobile"]); ?>"></td>
    <td width="100" align="center"> 电话 </td>
    <td><input id="tel" type="text" class="add_input_text" name="info[tel]" value="<?php echo ($info["tel"]); ?>">
      <input id="save_addr" type="reset" value="保存" style="padding:3px 15px; float:right"/></td>
  </tr>
  <tr>
    <td width="100" align="center">买家留言</td>
    <td><?php echo ($info["buyer_note"]); ?></td>
  </tr>
</table>
<table class="control_table batch_table" id="action_ishot">
    <tr>
        <td>商品信息</td>
    </tr>            
</table>
<table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0; border-top:1px solid #BBDDE5">
  <tr>
    <td>商品编号</td>
    <td>商品名</td>
    <td>数量</td>
    <td>价格</td>
  </tr>
  <?php if(is_array($list)): foreach($list as $key=>$l): ?><tr height="80">
      <td width="120"><?php echo ($l["id"]); ?></td>
      <td align="left" width="300"><span style="display:block;float:left"><img src="<?php echo (picture($l["image"],64)); ?>" /></span><span style="display:block; margin-left:4px; width:150px; line-height:22px; float:left"><?php echo ($l["goods_name"]); ?></span></td>
      <td width="80"><?php echo ($l["number"]); ?></td>
      <td width="80"><?php echo ($l["price"]); ?></td>
    </tr><?php endforeach; endif; ?>
</table>
<?php if($info["order_status"] == 'created'): ?><table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0; border-top:1px solid #BBDDE5;">
  	<tr>
    	<td align="right" width="300px">当前订单金额</td>
    	<td >￥<?php echo ($info["order_amount"]); ?>&nbsp;</td>
  	</tr>
    <tr>
      <td align="right" width="300px">选择操作</td>
      <td><input type="checkbox" name="setPayed" id="pay" value="" style=" vertical-align:middle"/>
        &nbsp;设为已付款&nbsp;&nbsp;<select id="paytype_input" type="text" style="display:none; padding:2px 3px"/>
        <option value="alipay">支付宝</option>
        <option value="wechat">微信支付</option>
        </select>&nbsp;&nbsp;<span id="paytype_note" style="display:none;">请选择付款方式</span></td>
    </tr>
    <tr>
      <td align="right" width="300px">操作备注</td>
      <td align="left"></span>
        <textarea rows="4" name="action_note" class="textarea_border" id="action_note" style="width:500px"></textarea></td>
    </tr>
    <tr>
      <td align="right" width="300px"></td>
      <td align="center"><span>
      	<input type="hidden" id="save_type" value="money"  />
        <input id="save_money" type="submit" name="submit" value="提交保存" style="padding:3px 5px;"/>
        </span></td>
    </tr>
  </table><?php endif; ?>
<?php if(!empty($action)): ?><table class="control_table batch_table" id="action_ishot">
    <tr>
        <td>订单日志</td>
    </tr>            
</table>
<style>
	.action_tr {border-bottom:1px solid #BBDDE5;}
	.action_td {border-bottom:1px solid #F4F4F4;}
</style>

<table class="add_table" cellspacing="0" cellpadding="0"  style="padding: 10px 0; border-top:1px solid #BBDDE5;">
    <tr height="30px" >
        <td width="120" class="action_tr"> 操作人</th>
        <td width="80" class="action_tr"> 订单状态</td>
        <td width="70" class="action_tr"> 金额 </td>
        <td width="300" class="action_tr"> 操作备注 </td>
        <td width="120" align="center" class="action_tr"> 操作时间 </td>
    </tr> 
    <?php if(is_array($action)): foreach($action as $key=>$a): ?><tr>
      <td class="action_td"><?php echo ($a["action_user"]); ?></td>
      <td class="action_td">
        <?php if(($a["order_status"]) == "created"): ?>待付款<?php endif; ?>
        <?php if(($a["order_status"]) == "canceled"): ?>已取消<?php endif; ?>
        <?php if(($a["order_status"]) == "payed"): ?>待发货<?php endif; ?>
        <?php if(($a["order_status"]) == "refund_appiled"): ?>已申请退款<?php endif; ?>
        <?php if(($a["order_status"]) == "refund"): ?>已退款<?php endif; ?>
        <?php if(($a["order_status"]) == "shipped"): ?>已发货<?php endif; ?>
        <?php if(($a["order_status"]) == "received"): ?>已收货<?php endif; ?>
      </td>
      <td class="action_td"><?php echo ($a["money"]); ?></td>
      <td class="action_td"><?php echo ($a["action_note"]); ?></td>
      <td class="action_td"><?php echo (date('Y-m-d H:i:s',$a["log_time"])); ?></td>
    </tr><?php endforeach; endif; ?>  
  </table><?php endif; ?>
</div>
</div>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script> 
<script type="text/javascript" src="__PUBLIC__/Js/country.js"></script> 
<script type = "text/javascript">
var region = '<?php echo ($regionjson); ?>';
var region = eval("("+region+")");
	jQuery(function(){
		initCACL('province','city','area','<?php echo ($info["province_id"]); ?>','<?php echo ($info["city_id"]); ?>','<?php echo ($info["area_id"]); ?>');
	});
</script> 
<script type="text/javascript">
	$(function(){

		//编辑收货地址		
		$("#edit_addr").click(function(){
			$(".close").hide();
			$(".open").show();		
		});
		//取消编辑收货地址
		$("#cannel_addr").click(function(){
			$(".open").hide();
			$(".close").show();		
		});						
		//保存收货地址
		$("#save_addr").click(function(){
			var province_id = $("#province").val();
			var city_id = $("#city").val();
			var area_id = $("#area").val();
			var address = $("#address").val();
			var consignee = $("#consignee").val();
			var mobile = $("#mobile").val();
			var tel = $("#tel").val();
			var id = $("#text_gid").val();
			var parm = {id:id,province_id:province_id,city_id:city_id,area_id:area_id,address:address,consignee:consignee,mobile:mobile,tel:tel};
			$.post('__URL__/saveAddress',parm,function(data){
				var result = eval(data);
				if (result.status == 0) {
					alert(result.prompt)		
				} else {
					window.location.reload();
				}
			})	
		});

		//将订单设置为已付款，选择付款方式
		$("#pay").click(function(){
			$("#money").attr("checked",false);
			$("#money_input,#money_type").hide();
			if($(this).attr("checked")== true){
				if(confirm("确认设为已付款吗？")) {
					$("#paytype_input,#paytype_note").show();
					$(this).attr("checked",true);
					$("#save_type").val("pay")	
				} else {
					$(this).attr("checked",false);	
				}	
			} else {
				$("#paytype_input,#paytype_note").hide();	
			}
		});
		$("#save_money").click(function(){
			var action_note = $("#action_note").val();
			if (action_note=='') {
					alert('操作备注不能为空');
					return false;	
			}
			var order_id = $("#text_gid").val();
			var payname = $("#paytype_input").val()
			if(payname=='') {
				alert('付款方式不能为空')	
				return false;
			}
			var parm = {order_id:order_id,action_note:action_note,payname:payname};	
			if (confirm("确定将订单设置为已付款吗？")) {				
				$.post('__URL__/setPayed',parm,function(data){
					var result = eval(data);
					if (result.status == 0) {
						alert(result.prompt)		
					} else {
						window.location.reload();
					}
				});
			}			
		})


		//发货
		$("[id^='shipping-']").toggle(function(){
			if(confirm("确认发货吗？")) {
				$(this).val("取消发货")
				var sid = $(this).attr("id").split("-");			
				$("#no-"+sid[1]).css("display","inline-block");
				$("#sure-"+sid[1]).css("display","inline-block");
			}
			},function(){
				$(this).val("点击发货")
				var sid = $(this).attr("id").split("-");			
				$("#no-"+sid[1]).css("display","none");
				$("#sure-"+sid[1]).css("display","none");
			}	
		)
		//确认发货
		$("[id^='sure-']").click(function(){
			if(confirm("确认发货吗？")) {
				var sure = $(this).attr("id").split("-");
				var no = $("#no-"+sure[1]).val();
				if (no == '') {
					alert('请填写快递单号');
					return false;
				}
				var order_id = $("#text_gid").val();
				$.post('__URL__/setShipped',{order_id:sure[1],shipping_no:no},function(data){
					var result = eval(data);
					if (result.status == 0) {
						alert(result.prompt)		
					} else {
						window.location.reload();
					}
				})	
			}	
		});
	})
</script> 
<!--加载页面脚部文件--> 
<!--        <div id="footer">
            共执行 29 个查询，用时 0.158485 秒，Gzip 已禁用，内存占用 3.546 MB
            <br/>
            版权所有 © 2012-2012 xxxx科技有限公司，并保留所有权利。
        </div>-->

    </body>
</html>