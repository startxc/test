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
    <span class="action_title"> <a href="#">分类管理</a></span> <span class="action_module"> - 编辑分类  </span>
    <span class="action_span" > <a href="__URL__">分类列表</a> </span>
    <div class="clear"> </div>
</div>

<div id="middle">
    <form method="post" action="__URL__/insert" enctype="multipart/form-data" id="myForm">
        <!--列表-->
        <div class="list_div">
            <table class="add_table" cellspacing="1" cellpadding="3">
                
                
                <tr>
                    <td width="100" align="center"> 
                        分类名称
                    </td>
                    <td> <input  type="text" class="add_input_text" name="name" value="<?php echo ($info["name"]); ?>"> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        同级排序
                    </td>
                    <td> <input type="text" class="add_input_text" name="order_index" value="<?php echo ($info["order_index"]); ?>"> </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 启用状态 </td>
                    <td> <input type="radio" name="status" value="1" <?php if(($info["status"]) == "1"): ?>checked<?php endif; ?> /> 启用 <input type="radio" name="status" value="0" <?php if(($info["status"]) == "0"): ?>checked<?php endif; ?> /> 禁用 </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        分类图片
                    </td>
                    <td> <?php if(!empty($info["image"])): ?><img id="category_image"  wid="<?php echo ($info["image"]); ?>" src="<?php echo (picture($info["image"],'','product')); ?>" width="100" height="100" />
                        <?php else: ?>
                            <img style="display:none;" id="category_image"  wid="" src="" width="100" height="100" /><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="center"> 
                        添加图片
                    </td>
                    <td> 
                        <div style="position:relative;">
                            <?php if(!empty($info["image"])): ?><input id="category_image_btn" type="button" value="更换图片" />
                            <?php else: ?>
                                <input id="category_image_btn" type="button" value="上传图片" /><?php endif; ?>
                            <div style="position:absolute;top:0px;left:0px;">
                                <input style="display:none;" type="file" id="file_upload_category" multiple />
                            </div>
                        </div>
                        
                    </td>
                </tr>
            </table>

            <table class="submit_table">
                <tr>
                    <td> 
                        <span>
                            <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" />
                            <input type="submit" id="submit_add" value="提交保存" />
                        </span>
                        <span><input type="reset" value="全部重置" /></span>
                    </td>
                </tr>                    
            </table> 
        </div>
    </form>
</div>
<script type="text/javascript" src="__PUBLIC__/Js/image.js"></script>
<script type="text/javascript">
    $(function(){
            var isSubmitButton = false;
            myImage.uploadImage(
                $('#file_upload_category'),
                {width:'59px',height:'21px'},
                {'dir':'product'},
                function(data){
                    var res = eval(data);
                    if (res.status === 0) {
                        alert(res.info);  
                    }else{
                        $("#category_image").attr("src",res.src).attr("wid",res.name).show();
                        $("#category_image_btn").val("更改图片");  
                    }
                }
            );

            $("#submit_add").click(function(){
                var oForm = $("#myForm").get(0);
                var id = oForm.id.value;
                var name = oForm.name.value;
                if(name == ""){
                    alert("分类名称不能为空");
                    oForm.name.focus();
                    return false;
                }
                var order_index = oForm.order_index.value;
                var status = $("input[name=status]:checked").val();
                var image = $("#category_image").attr("wid");
                if(isSubmitButton === false){
                    isSubmitButton = true;
                    $.ajax({
                        type:"post",
                        url:"__URL__/update",
                        data:{id:id,name:name,order_index:order_index,status:status,image:image},
                        async:false,
                        success:function(data){
                            var res = eval(data);
                            if(res.status === 1){
                                alert("更新成功");
                                window.location.href = "__URL__/edit/id/"+id;
                            }else{
                                alert("更新失败");
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