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
    .list_div input,select,textarea {  outline: none; }
    .list_div .add_input_text { outline: none; padding-left: 3px; }
    .input { float: left; height: 22px; margin: 0 5px; padding-left: 3px; border: 1px solid #CCC; }
    .select { float: left; padding: 3px;  margin-right: 5px; height: 24px; line-height: 24px; }
    .special { margin: 0 5px; background: #DDEEF2; height: 19px; line-height: 19px; display: inline-block; float: left; padding: 1px 5px; border: 1px solid #91C4D0; border-right: 2px solid #91C4D0; border-bottom: 2px solid #91C4D0; }
    .list_div a.special:hover { border: 1px solid #91C4D0; }
    .bright { color:#f60; }
    .box_cat_list { display: inline-block; height: 24px; line-height: 24px; padding: 0 3px; float: left; }
    .img { display: inline-block; margin-right: 3px; vertical-align: middle; width: 14px; height: 14px; border: 1px solid #E4E4E4; }
    .input_prompt { margin-left: 10px; color: #f60; }
    
    .box_basic_property,.box_sku_property { width: 80%; background: #F8F8F8; border: 1px solid #ECECEC; padding: 0 20px 20px 20px; }
    .box_basic_property ul li { margin-top: 20px; }
    .box_basic_property ul li span { float: left; margin-right: 10px; height: 18px; line-height: 18px; padding: 3px; display: inline-block; }
    
    .box_sku_property input{ vertical-align: middle; }
    .box_sku_property .box_sku_group { margin-top: 20px; }
    .box_sku_property ul li { width: 140px; height: 25px; line-height: 25px; float: left; }
    .box_sku_property ul li label.label_name { white-space: nowrap; display: inline-block; vertical-align: middle; width: 85px; height: 22px; line-height: 22px; overflow: hidden; }
    .box_upload_icon { padding: 0; margin: 10px 0 0 0;  }
    
    .box_upload_icon table { border-collapse: collapse; }
    .box_upload_icon table tr th { background: #EDEDED; font-weight:400; }
    .box_upload_icon table tr td,th { padding: 3px 6px 3px 6px; border: 1px solid #D7D7D7; }
    .btn_img { padding-left: 0; border: 1px solid #D7D7D7; background: #F8F8F8; padding: 3px 10px 3px 10px; text-align: center; }
    .left_img { margin-right: 7px; }
    .input_store { width: 80px; padding-left: 3px; border: 1px solid #C2D1D8; height: 20px; line-height: 20px; }

    .box_img_list { width: 100%;  border: 1px solid #ECECEC; }
    .box_header { height: 32px; padding: 10px 20px 0; background: #F8F8F8; }
    .box_header span { background: #ECECEC; height: 32px; line-height: 32px; display: inline-block; padding: 0 12px; margin-right: 12px; cursor: pointer; }
    .box_header span.hover { background: #FFF; }
    .box_middle {  }
    .box_button_upload { height: 100px; margin: 30px; }
    .box_button_upload .upload_field { height: 25px; line-height: 25px; }
    .box_button_upload .upload_field span { position: relative; display: inline-block; width: 90px; height: 25px; overflow: hidden; }
    .box_button_upload .upload_field .file_upload_field { opacity: 0; position: absolute; width: 200px; right: 0; height: 25px;  }
    .box_button_upload .upload_prompt { color: #AAA; margin-top: 15px; }
    .box_button_upload .upload_prompt ol li { margin-left: 14px; margin-top: 8px; }
    .box_footer { padding: 15px; margin: 2px; background: #F8F8F8; }
    .box_gallery ul li { float: left; position: relative; margin-right: 10px; margin-top: 15px; width: 90px; height: 90px; text-align: center; line-height: 90px; border:1px solid #CDCDCD; }
    .box_gallery ul li span { display: inline-block; padding: 2px; width: 86px; height: 86px; background: url(__ROOT__/Public/Images/loading3.gif) no-repeat center; }
    .box_gallery ul li.main { border: 1px solid #FFC097; }
    .box_gallery ul li.first { border: 1px solid #FFC097; }
    .box_gallery ul li .preview { margin: 2px; width: 86px; height: 86px; line-height: 86px; }
    .box_gallery ul li .control { display: none; background: #666; position: absolute; height: 20px; width: 86px; left: 2px; bottom: 2px; }
    .box_gallery ul li .control a {  font: "宋体"; font-size: 10px; color: #FFF; display: inline-block; text-align: center; width: 43px; height: 20px; line-height: 20px; }
    .box_gallery ul li .control a:hover { color: #f60;  } 
    .box_gallery ul .hover .control { display: inline-block; opacity: 0.8;  }
    .box_goods_gallery ul li .control a { width: 28px; }
    .box_goods_gallery ul li .left_arrow { background: url(__ROOT__/Public/Images/icon_control.png) no-repeat; background-position: 0 0; }
    .box_goods_gallery ul li .delete_arrow { background: url(__ROOT__/Public/Images/icon_control.png) no-repeat; background-position: -28px 0; }
    .box_goods_gallery ul li .right_arrow { background: url(__ROOT__/Public/Images/icon_control.png) no-repeat; background-position: -56px 0; }
    
    .box_img_upload {  margin-top: 10px; border: solid 1px #AED3FF; }
    .box_img_upload .box_header { background: #E7F4FD; }
    
    .box_prompt_msg { margin-top: 10px; display: inline-block; background: #E5F4FE; border: 1px solid #3FB2FE; padding: 3px 5px 3px 3px;   }
    .box_prompt_msg p { float: left; margin-left: 5px; }
    .icon_attention { width: 16px; height: 16px; float: left; line-height: 16px;  display:inline-block; background: url(__ROOT__/Public/Images/prompt.png) no-repeat; background-position:0 -60px; }
    
    .box_other_item ul li { float: left; height: 24px; line-height: 24px; width: 150px; overflow: hidden; }
    .box_other_item ul li input { vertical-align: middle; }
    .box_other_item ul li label { display: inline-block; width: 130px; height: 22px; line-height: 22px; vertical-align: middle; overflow: hidden; }
    
    /*编辑器自定义按钮*/
    .ke-icon-selfimg { background-image: url(__ROOT__/Public/Js/kindeditor-4.1.7/themes/default/default.gif); background-position: 0px -495px; width: 16px; height: 16px; }
    
    .box_sku_img { display: inline-block; position: relative; width: 70px; height: 23px; }
    .box_sku_img .choose_sku_file { position: absolute; right: 0; top: 0; opacity: 0; }
    .box_sku_ctrl { display: none; }
    .box_sku_ctrl a { float:right; height:23px; line-height:23px; margin-left: 5px;  }
    
    /*图片模板*/
    .html_template { height: 500px; overflow: auto; }
    .box_template_list ul li { margin-top: 20px; width: 738px; }
    .box_template_list ul li input { padding-left: 3px; width: 733px; height: 24px; border: 1px solid #CDCDCD; border-bottom: none; }
    .box_template_list ul li textarea { padding-left: 3px; width: 733px; height: 48px; border: 1px solid #CDCDCD;  }
    .box_template_list .box_image_out { width: 738px; height: 300px; overflow: hidden; }

    .selected_production{position:relative;display:inline-block;}
    .production_name{
        background: linear-gradient(#fdfefd, #ebebec) repeat scroll 0 0 rgba(0, 0, 0, 0); 
        font-size: 12px;    
        border: 1px solid #cccccc;
        border-radius: 3px;
        color: #666;
        display: inline-block;
        height: 22px;
        line-height: 22px;
        margin-bottom: 6px;
        margin-right: 8px;
        padding: 0 8px;
        outline: medium none;
    }
    .production_delete{
        background: none repeat scroll 0 0 red;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        display: block;
        font-size: 14px;
        font-weight: bold;
        height: 14px;
        line-height: 12px;
        position: absolute;
        right: 2px;
        text-align: center;
        top: -5px;
        width: 14px;       
    }
    a.production_name:hover{
        color:#666;
    }
    a.production_delete:hover{
        color:#fff;
    }
    
</style>

<div class="header">
    <span class="action_title"> <a href="#">商品管理</a></span> <span class="action_module"> - 添加商品  </span>
    <span class="action_span" > <a href="__URL__">商品列表</a> </span>
    <div class="clear"> </div>
</div>

<div id="middle">
        <!--列表-->
        <div class="list_div">
            <table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0;">
                <tr>
                    <td width="85" align="center"> 
                        商品分类
                    </td>
                    <td> 
                        <select id="goods_category">
                            <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        产地列表
                    </td>
                    <td> 
                        <select id="goods_production">
                            <?php if(is_array($production)): $i = 0; $__LIST__ = $production;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <input type="button" value="添加" id="add_production" />&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        已选产地
                    </td>
                    <td id="selected_production"> 
                        

                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品名称
                    </td>
                    <td> <input id="goods_name" type="text" class="add_input_text" name="name"> <label class="input_prompt"> 一般不要超过30个字符 </label> </td>
                </tr>

                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品价格 
                    </td>
                    <td> <input id="goods_price" type="text" class="add_input_text" name="price"></td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品规格 
                    </td>
                    <td> <input id="goods_spec" type="text" class="add_input_text" name="spec"></td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品图片
                    </td>
                    <td> 
                        <div class="box_img_list">
                            <div class="box_header">
                                <span class="hover"> 本地上传 </span>
                                <span> 网上图片 </span>
                            </div>
                            <div class="box_middle"> 
                                <div class="box_button_upload">
                                        <div class="upload_field">
                                                <div style="position:relative;">                                                
                                                    <input type="button" class="btn_img" id="btn_upload_goods" value="图片上传++" />
                                                    <div style="position:absolute;top:0;left:0;">
                                                        <input type="file" style="display:none" id="file_upload_goods_image" />
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="upload_prompt">
                                            <span> 网站提示: </span>
                                            <ol>
                                                <li>本地上传图片大小不能超过<strong class="bright">1M</strong>。</li>
                                                <li>本类目下您最多可以上传<strong class="bright"> 9 </strong>张图片。</li> 
                                            </ol>              
                                        </div>
                                </div>
                            </div>
                            <div class="box_footer">
                                <div class="box_gallery box_goods_gallery" id="box_goods_img_list">
                                    <ul>
                                    </ul>
                                    <div class="clear"> </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品描述
                    </td>
                    <td> 
                        <div class="box_textarea"><textarea id="goods_desc" name="desc" style=" width: 700px; margin-right: 2px; height: 200px;" class="textarea_desc_field" ></textarea> </div>

                    </td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>商品排序 
                    </td>
                    <td> <input id="goods_order" type="text" class="add_input_text" name="order" value="0"></td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>是否显示 
                    </td>
                    <td> <input type="radio" name="is_show" value="1" checked />显示&nbsp;&nbsp;<input type="radio" name="is_show" value="0" />不显示</td>
                </tr>
                <tr>
                    <td width="85" align="center"> 
                        <i class='bright'>*</i>是否推荐 
                    </td>
                    <td> <input type="radio" name="is_recommend" value="1"  />推荐&nbsp;&nbsp;<input type="radio" name="is_recommend" value="0"  checked/>不推荐</td>
                </tr>
            </table>

            <table class="submit_table">
                <tr>
                    <td> 
                        <span>
                            <input id="btn_submit" type="submit" value="提交保存" />
                        </span>
                    </td>
                </tr>                    
            </table> 
        </div>
</div>

<link rel="stylesheet" href="__PUBLIC__/Js/kindeditor-4.1.7/themes/default/default.css" />
<script type="text/javascript" src="__PUBLIC__/Js/kindeditor-4.1.7/kindeditor-min.js"></script>

<script src="__PUBLIC__/Js/image.js" type="text/javascript"></script>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.dragsort-0.5.1.min.js"></script>
<script tyep="text/javascript" src="__PUBLIC__/Js/verify.js"></script>

<script type="text/javascript">
    var isSubmitButton = false;
    var ED;

    //删除商品图片
    function deleteOne(obj){
        $(obj).closest("li").remove();
    }
    $(function() {

        //商品描述编辑器
        ED = KindEditor.create(
                'textarea[name="desc"]', 
                {items:['source', '|', 'undo', 'redo', '|','plainpaste', 'wordpaste', '|', 
                        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                        'italic', 'underline', '|', 'emoticons','link', 'unlink','preview','fullscreen'],
                filterMode:false
                }
        );


        //上传商品图片
        myImage.uploadImage(
            $("#file_upload_goods_image"),
            {width:'90px',height:'22px'},
            {"dir":"product"},
            function(data){
                var res = eval(data);
                if(res.status == 0){
                    alert(res.info);
                }else{
                    $("#box_goods_img_list ul").find(".first").remove();
                    var html = '<li onmouseout="$(this).removeClass(\'hover\');" onmouseover="$(this).addClass(\'hover\');">'; 
                        html += '<div class="preview"><img width="86" height="86" wid="'+res.name+'" src="'+res.src+'" /></div>';
                        html += '<div class="control"><a href="javascript:;" class="delete_arrow" onclick="deleteOne(this);">  </a></div></li>';
                    $("#box_goods_img_list ul").append(html);                   
                }
            }

        );

        $("#add_production").click(function(){
            var production_name = $("#goods_production").find("option:selected").html();
            var production_id = $("#goods_production").val();
            if($("#selected_production").find(".selected_production[dataid="+production_id+"]").length<1){
                var html =  "<span class='selected_production' dataid='"+production_id+"'>";
                    html += "<a class='production_name'>"+production_name+"</a>";
                    html += "<a class='production_delete'>-</a>";
                    html += "</span>";
                $("#selected_production").append(html);
            }
        });
        $(".production_delete").live("click",function(){
            $(this).parent().remove(); 
        });

        //商品图片排序
        $('#box_goods_img_list ul').dragsort({dragSelector: "li"});
                          
        //提交保存
        $("#btn_submit").bind("click", function(){
            //商品分类
            var cid = $("#goods_category").val();
            //商品产地
            var $selected_production = $(".selected_production");
            if($selected_production.length<1){
                alert("商品产地不能为空哦");
                return false;
            }
            var selected_production_id = "";
            $selected_production.each(function(){
                selected_production_id += $(this).attr("dataid")+",";
            })
            //商品名称
            var name = $("#goods_name").val();
            if(name == ""){
                alert("商品名称不能为空");
                $("#goods_name").focus();
                return false;
            }
            //商品价格
            var price = $("#goods_price").val();
            if(!isMoney(price)){
                alert("商品价格格式不对");
                $("#goods_price").focus();
                return false;
            }
            //商品规格
            var spec = $("#goods_spec").val();
            if(!isPositiveInteger(spec)){
                alert("商品规格格式不对");
                $("#goods_spec").focus();
                return false;
            }                     
           //商品图片
            var images = "";
            $("#box_goods_img_list ul").find("li").each(function(){
                var tmp = $(this).find("img").attr("wid");
                if(tmp != undefined) { 
                    images += tmp + ",";
                }
            });
            if(images==="") {
                alert("商品图片不能为空哦!"); 
                return false;
            }      
            //商品描述
            var desc = ED.html();
            desc = desc.replace(/<style .*?<\/style>/ig,"");
            if(desc==="") {
                alert("商品描述不能为空!"); 
                return false;
            }
            //商品排序
            var order = $("#goods_order").val();
            if(!isNumber(order)){
                alert("商品排序格式不对");
                $("#goods_order").focus();
                return false;
            }
            //是否显示
            var is_show = $("input[name=is_show]:checked").val();
            //是否推荐
            var is_recommend = $("input[name=is_recommend]:checked").val();
            $(this).val("提交中...");
            if(isSubmitButton === false){
                isSubmitButton = true;
                $.ajax({
                    type:"post",
                    url:"__URL__/insert",
                    data:{cid:cid,selected_production_id:selected_production_id,name:name,price:price,spec:spec,images:images,desc:desc,order:order,is_show:is_show,is_recommend:is_recommend},
                    async:false,
                    success:function(data){
                        var res = eval(data);
                        if(res.status === 2){
                            alert("添加商品成功");
                            window.location.href = "__URL__/index";
                        }else if(res.status === 1){
                            alert("商品名称已经存在");
                            $("#goods_name").focus();
                        }else{
                            alert("添加商品失败");
                        }
                    }
                });
                isSubmitButton = false;
                $("#btn_submit").val("提交保存");
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