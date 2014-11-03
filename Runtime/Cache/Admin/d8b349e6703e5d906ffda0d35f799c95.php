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
    <span class="action_title"> <a href="#">菜单管理</a></span> <span class="action_module"> - 菜单编辑  </span>
    <span class="action_span" > <a href="__URL__/index">菜单列表</a> </span>
    <div class="clear"> </div>
</div>

<div id="middle">
    <form method="post" action="__URL__/update">
    <!--列表-->
    <div class="list_div">
        <table class="add_table" cellspacing="1" cellpadding="3">
            <tr>
                <td width="100" align="center"> 
                    菜单名称
                </td>
                <td> <input type="text" class="add_input_text" name="name" value="<?php echo ($info["name"]); ?>" > </td>
            </tr>
             <tr>
                <td width="100" align="center"> 
                    菜单链接
                </td>
                <td> <input type="text" class="add_input_text" name="url" value="<?php echo ($info["url"]); ?>"> </td>
            </tr>
            <tr>
                <td width="100" align="center"> 启用状态 </td>
                <td> <input type="radio" name="status" value="1" <?php if($info['status'] == 1): ?>checked="true"<?php endif; ?> > 启用 <input type="radio" name="status" value="0" <?php if($info['status'] == 0): ?>checked="true"<?php endif; ?>> 禁用 </td>
            </tr>
            <tr>
                <td width="100" align="center"> 旗下栏目 </td>
                <td> 
                    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><span><input type="checkbox" value="<?php echo ($vo["id"]); ?>" <?php if(in_array($vo['id'],$child)) { echo 'checked="true"'; } ?> name="menu[]"> <?php echo ($vo["title"]); ?> </span> &nbsp; &nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
            </tr>
        </table>
       
        <table class="submit_table">
            <tr>
                <td> 
                    <input type="hidden" value="<?php echo ($info["id"]); ?>" name="id">
                    <span><input type="submit" value="提交保存" /></span>
                    <span><input type="reset" value="全部重置" /></span>
                </td>
            </tr>                    
        </table> 
    </div>
    </form>
</div>

<!--加载页面脚部文件-->
<!--        <div id="footer">
            共执行 29 个查询，用时 0.158485 秒，Gzip 已禁用，内存占用 3.546 MB
            <br/>
            版权所有 © 2012-2012 xxxx科技有限公司，并保留所有权利。
        </div>-->

    </body>
</html>