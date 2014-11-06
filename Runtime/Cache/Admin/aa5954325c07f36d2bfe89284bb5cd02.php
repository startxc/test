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

<div class="header">
    <span class="action_title"> <a href="__URL__/groupApply">伙拼申请管理</a></span> <span class="action_module"> - 审核伙拼申请  </span>
    <span class="action_span" > <a href="__URL__/groupApply">伙拼申请列表</a> </span>
    <div class="clear"> </div>
</div>

<div id="middle">
    <form method="post" action="__URL__/insert" enctype="multipart/form-data" id="myForm">
        <!--列表-->
        <div class="list_div">
            <table class="add_table" cellspacing="1" cellpadding="3">
                
                <tr>
                    <td width="100" align="center"> 
                        商品价格：
                    </td>
                    <td> <input type="text" class="add_input_text" id="goods_price" name="price" value="<?php echo ($groupApply["price"]); ?>" style="width:150px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        商品名称：
                    </td>
                    <td> <input type="text" class="add_input_text" id="goods_name" name="name" value="<?php echo ($groupApply["name"]); ?>" style="width:150px;" /> </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        商品分类
                    </td>
                    <td> 
                        <select id="goods_category">
                            <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $groupApply['category_id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        商品产地
                    </td>
                    <td> 
                        <select id="goods_production">
                            <?php if(is_array($production)): $i = 0; $__LIST__ = $production;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $groupApply['production_id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td width="100" align="center"> 
                        商品封面：
                    </td>
                    <td> <?php if(!empty($groupApply["image"])): ?><img id="group_image"  wid="<?php echo ($groupApply["image"]); ?>" src="<?php echo (picture($groupApply["image"],'','product')); ?>" width="100" height="100" />
                        <?php else: ?>
                            <img style="display:none;" id="group_image"  wid="" src="" width="100" height="100" /><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        添加图片：
                    </td>
                    <td> 
                        <div style="position:relative;">
                            <?php if(!empty($groupApply["image"])): ?><input id="group_image_btn" type="button" value="更换图片" />
                            <?php else: ?>
                                <input id="group_image_btn" type="button" value="上传图片" /><?php endif; ?>
                            <div style="position:absolute;top:0px;left:0px;">
                                <input style="display:none;" type="file" id="file_upload_group_image" multiple />
                            </div>
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品描述
                    </td>
                    <td> 
                        <div class="box_textarea"><textarea id="goods_desc" name="desc" style=" width: 700px; margin-right: 2px; height: 200px;" class="textarea_desc_field" ><?php echo ($groupApply["description"]); ?></textarea> </div>
                    </td>
                </tr>               
                <tr>
                    <td width="100" align="center"> 
                        申请人：
                    </td>
                    <td> <?php echo ($groupApply["member_name"]); ?></td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        申请说明：
                    </td>
                    <td> <?php echo ($groupApply["remark"]); ?></td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        操作：
                    </td>
                    <td> 
                        <input type="radio" name="operation" value="success" />审核通过 &nbsp;&nbsp; 
                        <input type="radio" name="operation" value="fail" />审核不通过
                    </td>
                </tr>
                <tr id="extra_info" style="display:none;">
                    <td width="100" align="center"> 
                        
                    </td>
                    <td>
                        起订规格：<input type="text" id="moq_spec" name="moq_spec" class="add_input_text" style="width:50px;" /> &nbsp;&nbsp;
                        伙拼最低价格：<input type="text" id="min_price" name="min_price" class="add_input_text" style="width:50px;" /> &nbsp;&nbsp;
                        伙拼最低价格对应的规格：<input type="text" id="min_price_spec" name="min_price_spec" class="add_input_text" style="width:50px;" /> &nbsp;&nbsp;
                        开始日期：<input id="start_time" type="text" name="start_time" class="add_input_text" style="width:100px;" /> &nbsp;&nbsp;
                        结束日期: <input id="end_time" type="text" name="end_time" class="add_input_text" style="width:100px;" />
                    </td>
                </tr>
            </table>

            <table class="submit_table">
                <tr>
                    <td> 
                        <span>
                            <input type="hidden" name="id" id="group_apply_id" value="<?php echo ($groupApply["id"]); ?>" />
                            
                            <input type="hidden" name="goods_id" id="goods_id" value="<?php echo ($groupApply["goods_id"]); ?>" />
                            <input type="button" id="submit_add" value="提交保存" />
                        </span>
                    </td>
                </tr>                    
            </table> 
        </div>
    </form>
</div>
<link rel="stylesheet" href="__PUBLIC__/Js/kindeditor-4.1.7/themes/default/default.css" />
<script type="text/javascript" src="__PUBLIC__/Js/kindeditor-4.1.7/kindeditor-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/image.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/verify.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/lhgcalendar/lhgcore.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/lhgcalendar/lhgcalendar.min.js"></script>
<script type="text/javascript">
    J(function(){
        J('#start_time').calendar();
        J('#end_time').calendar();
    });
    var isSubmitButton = false;
    $(function(){

            //商品描述编辑器
            ED = KindEditor.create(
                    'textarea[name="desc"]', 
                    {items:['source', '|', 'undo', 'redo', '|','plainpaste', 'wordpaste', '|', 
                            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                            'italic', 'underline', '|', 'emoticons','link', 'unlink','preview','fullscreen'],
                    filterMode:false
                    }
            );

            //上传封面图片
            myImage.uploadImage(
                $('#file_upload_group_image'),
                {width:'59px',height:'21px'},
                {'dir':'product'},
                function(data){
                    var res = eval(data);
                    if (res.status === 0) {
                        alert(res.info);  
                    }else{
                        $("#group_image").attr("src",res.src).attr("wid",res.name).show();
                        $("#group_image_btn").val("更改图片");  
                    }
                }
            );
            $("input[name=operation]").click(function(){
                if($(this).val() == "success"){
                    $("#extra_info").show();
                }else{
                    $("#extra_info").hide();
                }
            });

            $("#submit_add").click(function(){

                var operation = $("input[name=operation]:checked").val();
                if(operation == undefined){
                    alert("请选择操作");
                    return false;
                }
                var id = $("#group_apply_id").val();
                if(operation == "success"){
                    var price = $("#goods_price").val();
                    if(price == ""){
                        alert("商品价格不能为空");
                        $("#goods_price").focus();
                        return false;
                    }
                    if(!isMoney(price)){
                        alert("商品价格格式不对");
                        $("#goods_price").focus();
                        return false;
                    }
                    var name = $("#goods_name").val();
                    if(name == ""){
                        alert("商品名称不能为空");
                        $("#goods_name").focus();
                        return false;
                    }
                    var image = $("#group_image").attr("wid");
                    if(image == ""){
                        alert("封面图片不能为空");
                        return false;
                    }
                    var desc = ED.html();
                    desc = desc.replace(/<style .*?<\/style>/ig,"");
                    if(desc==="") {
                        alert("商品描述不能为空!"); 
                        return false;
                    }
                    var moq_spec = $("#moq_spec").val();
                    if(moq_spec == ""){
                        alert("起订量不能为空");
                        $("#moq_spec").focus();
                        return false;
                    }
                    if(!isNumber(moq_spec)){
                        alert("起订量格式不对");
                        $("#moq_spec").focus();
                        return false;
                    }
                    var min_price = $("#min_price").val();
                    if(min_price == ""){
                        alert("伙拼最低价格不能为空");
                        $("#min_price").focus();
                        return false;
                    }
                    if(!isMoney(min_price)){
                        alert("伙拼最低价格格式不对");
                        $("#min_price").focus();
                        return false;
                    }
                    if(parseFloat(min_price)>parseFloat(price)){
                        alert("伙拼最低价格不能大于商品价格");
                        $("#min_price").focus();
                        return false;
                    }
                    var min_price_spec = $("#min_price_spec").val();
                    if(min_price_spec == ""){
                        alert("伙拼最低价格对应的量不能为空");
                        $("#min_price_spec").focus();
                        return false;
                    }
                    if(!isNumber(min_price_spec)){
                        alert("伙拼最低价格对应的量格式不对");
                        $("#min_price_spec").focus();
                        return false;
                    }
                    if(parseInt(min_price_spec)<parseInt(moq_spec)){
                        alert("伙拼最低价格对应的量不能小于起订量");
                        $("#min_price_spec").focus();
                        return false;
                    }
                    var start_time = $("#start_time").val();
                    if(start_time == ""){
                        alert("开始日期不能为空");
                        return false;
                    } 
                    var end_time = $("#end_time").val();
                    if(end_time == ""){
                        alert("结束日期不能为空");
                        return false;
                    }
                    var start = new Date(start_time.replace("-", "/").replace("-", "/"));
                    var end = new Date(end_time.replace("-", "/").replace("-", "/"));
                    if(start>end){
                        alert("开始日期必须小于结束日期");
                        return false;
                    }
                    
                    var goods_id = $("#goods_id").val();
                    var category_id = $("#goods_category").val();
                    var production_id = $("#goods_production").val();
                    var url = "__URL__/passGroupApply";
                    var data = {id:id,price:price,name:name,desc:desc,goods_id:goods_id,category_id:category_id,production_id:production_id,image:image,moq_spec:moq_spec,start_time:start_time,end_time:end_time,min_price:min_price,min_price_spec:min_price_spec};
                }else{
                    var url = "__URL__/notPassGroupApply";
                    var data = {id:id};
                }
                if(isSubmitButton === false){
                    isSubmitButton = true;
                    $.ajax({
                        type:"post",
                        url:url,
                        data:data,
                        async:false,
                        success:function(data){
                            var res = eval(data);
                            if(res.status === 1){
                                alert("操作成功");
                                window.location.href = "__URL__/groupApply";
                            }else{
                                alert(res.info);
                            }
                        }
                    });
                    isSubmitButton = false;
                }
                return false;
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