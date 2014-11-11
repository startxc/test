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
	width:100px;
}
.textarea_border {
	border-color:#1px solid #C2D1D8;
	width:256px
}
.new_input {
	width: 250px;
	height: 22px;
	line-height: 22px;
	border: 1px solid #C2D1D8;
}
.upload_input {
	background: none;
	color:#666;
	border:1px solid #D7D7D7;
	background-color:#F8F8F8;
	width: 100px;
	height: 30px;
	position: absolute
}
.upload_input_file {
	background:none;
	width:100px;
	height:30px;
	color:#FFF;
	opacity:0
}
.place_img_upload span {
	display:inline-block;
	width:105px;
}
.auth_box {
	width: 100%;
	background: #EEF8F9;
	border: 1px solid #BBDDE5;
	border-top:none;	
}
.idimg_upload{position:relative;}
.place_img_upload span{position:relative;}
.idimg_upload span .authImg { position:absolute;left: 158px;top: 0px;background: url(__PUBLIC__/Images/Home/bgplus.png) no-repeat;display: inline-block;width: 22px;height: 22px;}
.place_img_upload span .authImg { position:absolute;left: 80px;top: -2px;background: url(__PUBLIC__/Images/Home/bgplus.png) no-repeat;display: inline-block;width: 22px;height: 22px;}
</style>

<div class="header"> <span class="action_title"> <a href="#">添加会员</a></span> <span class="action_module"> - 编辑基本信息 </span> <span class="action_span" > <a href="__URL__">会员列表</a> </span>
  <div class="clear"> </div>
</div>
<div id="middle"> 
  <!--列表-->
  <form method="post" id="editform">
	  <div class="list_div">
	    <table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0;">
	      <tr>
	        <td width="120" align="center"> 会员类型 </td>
	        <td><div class="box_other_item">
	            <ul>
	              <li style="width:60px;"> <input class="checkbox" type="radio" value="normal" <?php if(($info['member_type']) == "normal"): ?>checked="checked"<?php endif; ?> name="info[member_type]" 
	                <label class="label_name" style="width:30px;">普通用户</label>
	              </li>
	              <li style="width:70px;"> <input class="checkbox" type="radio" value="saleman" <?php if(($info['member_type']) == "saleman"): ?>checked="checked"<?php endif; ?> name="info[member_type]" 
	                <label class="label_name" style="width:30px;">业务员</label>
	              </li>
	            </ul>
	            <div class="clear"> </div>
	          </div></td>
	        
	      </tr>
	      <tr>
	        <td width="160" align="center"> 会员昵称 </td>
	        <td ><input id="nickname" type="text" class="add_input_text" name="info[nickname]" value="<?php echo ($info["nickname"]); ?>"></td>	        
	      </tr>
	      <tr>
	        <td width="100" align="center">绑定手机</td>
	        <td><input id="mobile" type="text" name="info[mobile]"  class="add_input_text" value="<?php echo ($info["mobile"]); ?>" /></td>
	        <td width="100" align="center">绑定会员</td>
	        <td><input type="text" name="info[bind_member]" class="add_input_text" value="<?php echo ($info["bind_mobile"]); ?>" /></td>
	      </tr>
	      <tr>
			<tr>
	        <td width="100" align="center">详细地址</td>
	        <td colspan="3"><select name="info[province_id]" id="province" class="select">
	            <option value="" >-请选择-</option>
	          </select>
	          <select name="info[city_id]" id="city" class="select">
	            <option value="" >-请选择-</option>
	          </select>
	          <select name="info[area_id]" id="area" class="select">
	            <option value="" >-请选择-</option>
	          </select>
	          <select name="info[community_id]" id="community" class="select">
	            <option value="" >-请选择-</option>
	          </select>
	          <input id="address" type="text" name="info[address]"   class="add_input_text" value="<?php echo ($info["address"]); ?>" /></td>
	      </tr>
	    </table>
	  </div>
	  <input type="hidden" name="info[id]" value="<?php echo ($info["id"]); ?>"/>
	  <input type="hidden" name="info[old_mobile]" value="<?php echo ($info["mobile"]); ?>"/>
      <input type="hidden" name="sub" value="edit"/>
      </form>
	  <div class="list_div" style="border:none;">
	      <table class="submit_table">
	        <tr>
	          <td><span>
	            <input id="js_edit_member" type="submit" value="提交保存" />
	            </span></td>
	        </tr>
	      </table>
		</div>
	 
</div>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script> 
<script type="text/javascript" src="__PUBLIC__/Js/region.js"></script> 
<script type="text/javascript" src="__PUBLIC__/Js/verify.js"></script> 

<script type="text/javascript">
$(function(){

    myRegion.init('<?php echo ($info["province_id"]); ?>','<?php echo ($info["city_id"]); ?>','<?php echo ($info["area_id"]); ?>','<?php echo ($info["community_id"]); ?>');

	$("#js_edit_member").click(function(){
		if($("#nickname").val() == ""){
			alert("会员昵称不能为空!");
			$("#nickname").focus();
			return false;
		}
		if($("#mobile").val() == ""){
			alert("绑定手机号码不能为空!");
			$("#mobile").focus();
			return false;
		}
		if(!isMobile($("#mobile").val())){
			alert("绑定手机号码格式不对!");
			$("#mobile").select();
			return false;
		}
		if($("#province").val() == ""){
			alert("省份不能为空!");
			return false;
		}
		if($("#city").val() == ""){
			alert("城市不能为空!");
			return false;
		}
		if($("#area").val() == ""){
			alert("区域不能为空!");
			return false;
		}
		if($("#address").val() == ""){
			alert("详细地址不能为空!");
			$("#address").focus();
			return false;
		}
		$("#editform").submit();
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