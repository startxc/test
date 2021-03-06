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
    .box_upload_icon table tr td,th { padding: 5px 10px 5px 10px; border: 1px solid #D7D7D7; }
    .btn_img { padding-left: 0; border: 1px solid #D7D7D7; background: #F8F8F8; padding: 3px 10px 3px 10px; text-align: center; }
    .left_img { margin-right: 7px; }
    .input_store { width: 80px; padding-left: 3px; border: 1px solid #C2D1D8; height: 20px; line-height: 20px; }

    .box_img_list { width: 100%;  border: 1px solid #ECECEC; }
    .box_header { height: 32px; padding: 10px 20px 0; background: #F8F8F8; }
    .box_header span { background: #ECECEC; height: 32px; line-height: 32px; display: inline-block; padding: 0 12px; margin-right: 12px; cursor: pointer; }
    .box_header span.hover { background: #FFF; }
    
</style>

<div class="header">
    <span class="action_title"> <a href="#">系统设置管理</a></span> <span class="action_module"> - 系统配置列表  </span>
    <!--<span class="action_span" > <a href="__URL__">系统设置列表</a> </span>-->
    <div class="clear"> </div>
</div>

<div id="middle">
        <!--列表-->
        <div class="list_div">
            <form method="post" action="__URL__/batch">
                <table class="add_table" cellspacing="1" cellpadding="3"  style="padding: 10px 0;">
                    <?php if(is_array($configList)): $i = 0; $__LIST__ = $configList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td width="100" align="right"> 
                            <?php echo ($vo["name"]); ?>
                        </td>
                        <td> <input type="text" class="add_input_text" name="config[<?php echo ($vo["code"]); ?>]" value="<?php echo ($vo["value"]); ?>" /> </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>

                <table class="submit_table">
                    <tr>
                        <td> 
                            <span>
                                <input id="btn_submit" type="submit" value="提交保存" />
                            </span>
                            <span><input id="btn_preview" type="reset" value="提交预览" /></span>
                        </td>
                    </tr>                    
                </table> 
            </form>
        </div>
</div>

<script type="text/javascript">
   $("#btn_submit").bind("click", function(){ $(this).val("保存中..."); });
</script>

<!--加载页面脚部文件-->
<!--        <div id="footer">
            共执行 29 个查询，用时 0.158485 秒，Gzip 已禁用，内存占用 3.546 MB
            <br/>
            版权所有 © 2012-2012 xxxx科技有限公司，并保留所有权利。
        </div>-->

    </body>
</html>