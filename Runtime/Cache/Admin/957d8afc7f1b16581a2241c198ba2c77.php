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

<link type="text/css" rel="stylesheet" href="__PUBLIC__/Js/lhgcalendar-3.0.0/skins/lhgcalendar.css" />
<div class="header">
    <span class="action_title"> 编辑伙拼信息</span> 
    <span class="action_span" > <a href="__URL__/group">伙拼列表</a> </span>
    <div class="clear"> </div>
</div>

<div id="middle">
        <!--列表-->
        <div class="list_div">
            <table class="add_table" cellspacing="1" cellpadding="3">
                <tr>
                    <td width="100" align="center"> 
                        商品价格
                    </td>
                    <td> <input type="text" class="add_input_text" id="group_price" name="price" value="<?php echo ($info["price"]); ?>" style="width:150px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        商品名称
                    </td>
                    <td> <input type="text" class="add_input_text" id="group_name" name="name" value="<?php echo ($info["name"]); ?>" style="width:150px;" /> </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        商品分类
                    </td>
                    <td> 
                        <select id="goods_category">
                            <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $info['category_id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        商品产地
                    </td>
                    <td> 
                        <select id="goods_production">
                            <?php if(is_array($production)): $i = 0; $__LIST__ = $production;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $info['production_id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>            
                <tr>
                    <td width="100" align="center"> 
                        商品封面：
                    </td>
                    <td> <?php if(!empty($info["image"])): ?><img id="group_image"  wid="<?php echo ($info["image"]); ?>" src="<?php echo (picture($info["image"],'','product')); ?>" width="100" height="100" />
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
                    <td width="100" align="center"> 
                        起订规格
                    </td>
                    <td> <input type="text" class="add_input_text" id="moq_spec" name="moq_spec" value="<?php echo ($info["moq_spec"]); ?>" style="width:80px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        最低价格
                    </td>
                    <td> <input type="text" class="add_input_text" id="min_price" name="min_price" value="<?php echo ($info["min_price"]); ?>" style="width:80px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        最低价格对应的规格
                    </td>
                    <td> <input type="text" class="add_input_text" id="min_price_spec" name="min_price_spec" value="<?php echo ($info["min_price_spec"]); ?>" style="width:80px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        开始时间
                    </td>
                    <td> <input type="text" class="add_input_text" name="start_time" id="start_time" value="<?php echo (date("Y-m-d",$info["start_time"])); ?>" style="width:180px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        结束时间
                    </td>
                    <td> <input type="text" class="add_input_text" name="end_time" id="end_time" value="<?php echo (date("Y-m-d",$info["end_time"])); ?>" style="width:180px;" /> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 显示状态 </td>
                    <td> <input type="radio" name="is_show" value="1" <?php if(($info['is_show']) == "1"): ?>checked="true"<?php endif; ?> > 显示 <input type="radio" name="is_show" value="0" <?php if(($info['is_show']) == "0"): ?>checked="true"<?php endif; ?> /> 隐藏 </td>
                </tr>
            </table>

            <table class="submit_table">
                <tr>
                    <td> 
                        <span>
                            <input type="hidden" id="group_id" value="<?php echo ($info['id']); ?>" name="id" />
                            <input type="button" id="js_edit_group" value="提交保存" />
                        </span>
                    </td>
                </tr>                    
            </table> 
        </div>
</div>

<script type="text/javascript" src="__PUBLIC__/Js/lhgcalendar/lhgcore.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/lhgcalendar/lhgcalendar.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/image.js" ></script>
<script type="text/javascript" src="__PUBLIC__/Js/verify.js"></script>
<script type="text/javascript">
    J(function(){
        J("#start_time").calendar();
        J("#end_time").calendar();
    });
    var isSubmitButton = false;
    $(function(){
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

            $("#js_edit_group").click(function(){
                var price = $("#group_price").val();
                if(price == ""){
                    alert("商品价格不能为空");
                    $("#group_price").focus();
                    return false;
                }
                if(!isMoney(price)){
                    alert("商品价格格式不对");
                    $("#group_price").focus();
                    return false;
                }
                var name = $("#group_name").val();
                if(name == ""){
                    alert("商品名称不能为空");
                    $("#group_name").focus();
                    return false;
                }
                var image = $("#group_image").attr("wid");
                if(image == ""){
                    alert("封面图片不能为空");
                    return false;
                }
                var moq_spec = $("#moq_spec").val();
                if(moq_spec == ""){
                    alert("起订规格不能为空");
                    $("#moq_spec").focus();
                    return false;
                }
                if(!isNumber(moq_spec)){
                    alert("起订规格格式不对");
                    $("#moq_spec").focus();
                    return false;
                }
                var min_price = $("#min_price").val();
                if(min_price == ""){
                    alert("商品最低价格不能为空");
                    $("#min_price").focus();
                    return false;
                }
                if(!isMoney(min_price)){
                    alert("商品最低价格格式不对");
                    $("#min_price").focus();
                    return false;
                }
                if(min_price>price){
                    alert("商品最低价格不能大于商品价格");
                    $("#min_price").focus();
                    return false;
                }
                var min_price_spec = $("#min_price_spec").val();
                if(min_price_spec == ""){
                    alert("商品最低价格对应的规格不能为空");
                    $("#min_price_spec").focus();
                    return false;
                }
                if(!isNumber(min_price_spec)){
                    alert("商品最低价格对应的规格格式不对");
                    $("#min_price_spec").focus();
                    return false;
                }
                if(min_price_spec<moq_spec){
                    alert("商品最低价格对应的规格不能小于起订规格");
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
                var id = $("#group_id").val();  
                var category_id = $("#goods_category").val();
                var production_id = $("#goods_production").val();
                var is_show = $("input[name=is_show]:checked").val();

                if(isSubmitButton === false){
                    isSubmitButton = true;
                    $.ajax({
                        type:"post",
                        url:"__URL__/groupUpdate",
                        data:{id:id,category_id:category_id,production_id:production_id,name:name,price:price,image:image,moq_spec:moq_spec,min_price:min_price,min_price_spec:min_price_spec,start_time:start_time,end_time:end_time,is_show:is_show},
                        async:false,
                        success:function(data){
                            var res = eval(data);
                            if(res.status == 1){
                                alert("修改成功");
                                window.location.href = "__URL__/groupEdit/id/"+id;
                            }else{
                                alert("修改失败");
                            }
                        }
                    });
                    isSubmitButton = false;
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