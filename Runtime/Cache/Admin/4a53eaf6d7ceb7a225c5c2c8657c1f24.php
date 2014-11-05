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

<script type="text/javascript">
    var isSubmitButton = false;

    //删除产地
    function deletes(id) {
        if(window.confirm("您确定删除此产地吗？")) {
            window.location.href="__URL__/delete/id/"+id;
        }
    }

    $(function(){

        //表格变色
        $(".list_tr").hover(function(){
            $(this).find("td").css("background", "#F4FAFB");
        },function(){
            $(this).find("td").css("background", "#FFFFFF");
        });

        //更新排序
        $(".list_sort").change(function(){
            var order_index = $(this).val();
            var id = $(this).attr("dataid");
            if(isSubmitButton === false){
                isSubmitButton = true;
                $.ajax({
                    type:"post",
                    url:"__URL__/updateSort",
                    data:{id:id,order_index:order_index},
                    async:false,
                    success:function(data){
                        var res = eval(data);
                        if(res.status === 1){
                            $("#prompt").show();
                            setTimeout(function(){
                                $("#prompt").hide();
                            },1000);
                        }
                    }
                });
                isSubmitButton = false;
            }
        });
    });
</script>

<div class="header">
    <span class="action_title"> <a href="__URL__">产地管理</a></span> <span class="action_module"> - 产地列表 </span>

    <span class="action_span" > 
        <a href="__URL__/add">添加产地</a>
    </span>
    <div class="clear"> </div>
</div>
<div id="prompt" style="width:300px;height:30px;background:black;color:white;font-size:14px;text-align:center;line-height:30px;margin:10px 0;display:none;">
更新排序成功
</div>
<div id="middle">
    <form method="post" action="__URL__/batch">
        <!--列表-->
        <div class="list_div">
            <table class="list_table" cellspacing="1" cellpadding="3">
                <tr>
                    <!--<th width="65"> 选择选项 </th>-->
                    <th width="65"> 项目编号 </th>
                    <th width="100"> 产地名称 </th>
                    <th width="60"> 产地排序 </th>
                    <th width="60"> 是否显示 </th>
                    <th width="150"> 操作选项 </th>
                </tr>    
                
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="list_tr">
                        <!--<td class="td_items"> <input class="list_checkbox" type="checkbox" name="items[]" value="<?php echo ($vo["id"]); ?>"/> </td>-->
                        <td align="center" style="padding: 0; text-align: center;"> <?php echo ($vo["id"]); ?> </td>
                        <td> <?php echo ($vo["name"]); ?> </td>
                        <td class="td_center_show"> <input class="list_sort" type="text" dataid=<?php echo ($vo["id"]); ?> name="sort[<?php echo ($vo["id"]); ?>]" value="<?php echo ($vo["order_index"]); ?>">  </td>
                        <td class="td_center_show"> <a href="javascript:void();"> <?php if($vo['is_show'] == 1): ?><img border_index="0" src="__ROOT__/Public/Images/yes.gif"> <?php else: ?> <img border_index="0"  src="__ROOT__/Public/Images/no.gif"><?php endif; ?> </a> </td>
                        <td class="td_center_show"> 
                            <span class="control_span">   
                                <a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" title="编辑产地"><img border_index="0" src="__ROOT__/Public/Images/icon_edit.gif"/></a>
                                <a href="javascript:deletes(<?php echo ($vo["id"]); ?>);" title="删除该产地"><img border_index="0" src="__ROOT__/Public/Images/icon_drop.gif"/></a>
                            </span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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