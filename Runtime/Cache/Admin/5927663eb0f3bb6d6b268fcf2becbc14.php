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
    function chooseAll() {
        $(".td_items input").attr("checked", "checked");
    }
    function quitAll() {
        $(".td_items input").removeAttr("checked");
    }
    function deletes(id) {
        if(window.confirm("您确定删除此应用吗？")) {
            window.location.href="__URL__/delete/id/"+id;
        }
    }
    $(function(){
        //表格变色
        $(".list_tr").hover(function(){
            $(this).find("td").css("background", "#F4FAFB");
        },function(){
            if(!$(this).find("td").find("input").attr("checked")) {
                $(this).find("td").css("background", "#FFFFFF");
            }
        });
        //checkbox的选择
        $(".list_checkbox").click(function(){
            if($(this).attr("checked")) {
                $(this).parent().parent().find("td").css("background", "#F4FAFB");
            } else {
                $(this).parent().parent().find("td").css("background", "#FFFFFF");
            }
        });
        //批量操作
        $(".batch_dothing").click(function(){
            if($(this).val()!="sort") {
                $(".batcn_table").hide();
                $("#action_"+$(this).val()).show();
            }else {
                $(".batcn_table").hide();
            }
        });
    });
</script>

<div class="header">
    <span class="action_title"> <a href="__URL__">菜单管理</a></span> <span class="action_module"> - 菜单列表 </span>

    <span class="action_span" > 
        <a href="__URL__/add">添加菜单</a>
    </span>
    <div class="clear"> </div>
</div>

<!--<div class="header">
    <span class="action_title"> <a href="__URL__"> 选择分组： </a></span> <span class="action_module"> &nbsp; [ <?php if(is_array($groupList)): $i = 0; $__LIST__ = $groupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><a href="__URL__/index/group_id/<?php echo ($key); ?>"><?php echo ($tag); ?></a>&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?><a href="__URL__">全部 </a> ] </span>
    <span class="action_search_span "><input type="submit" class="btn_action_search" value="查找" /></span>
    <div class="clear"> </div>
</div>-->

<div id="middle">
    <form method="post" action="__URL__/batch">
        <!--列表-->
        <div class="list_div">
            <table class="list_table" cellspacing="1" cellpadding="3">
                <tr>
                    <th width="65"> 选择选项 </th>
                    <th width="65"> 操作编号 </th>
                    <th> 菜单名称 </th>
                    <th> 菜单链接 </th>
                    <th width="60"> 菜单序号 </th>
                    <th width="60"> 是否显示 </th>
                    <th width="100"> 操作选项 </th>
                </tr>    
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="list_tr">
                        <td class="td_items"> <input class="list_checkbox" type="checkbox" name="items[]" value="<?php echo ($vo["id"]); ?>"/> </td>
                        <td align="center" style="padding: 0; text-align: center;"> <?php echo ($vo["id"]); ?> </td>
                        <td> <?php echo ($vo["name"]); ?> </td>
                        <td> <?php echo ($vo["url"]); ?>  </td>
                        <td class="td_center_show"> <input class="list_sort" type="text" name="sort[<?php echo ($vo["id"]); ?>]" value="<?php echo ($vo["sort"]); ?>">  </td>
                        <td class="td_center_show"> <a href="javascript:void();"> <?php if($vo['status'] == 1): ?><img border="0" src="__ROOT__/Public/Images/yes.gif"> <?php else: ?> <img border="0"  src="__ROOT__/Public/Images/no.gif"><?php endif; ?> </a> </td>
                        <td class="td_center_show"> 
                            <span class="control_span">
                                <a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" title="编辑菜单"><img border="0" src="__ROOT__/Public/Images/icon_edit.gif"/></a>
                                <a href="javascript:deletes(<?php echo ($vo["id"]); ?>);" title="删除该菜单"><img border="0" src="__ROOT__/Public/Images/icon_drop.gif"/></a>
                            </span>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>

            <table class="control_table">
                <tr>
                    <td align="right" style="padding-right: 10px; text-align: right;" > <?php echo ($page); ?> </td>
                </tr> 
            </table>

            <table class="control_table">
                <tr>
                    <td width="60" class="td_title"> 批量操作 </td>
                    <td >
                        <span> <a href="javascript:chooseAll();">全选</a>/<a href="javascript:quitAll();">全不选</a> </span>
                        <span> <input class="batch_dothing" type="radio" name="opaction" value="sort" checked="cheacked"> 排序(默认)</span>
                        <span> <input class="batch_dothing"  type="radio" name="opaction" value="delete">批量删除</span>
                        <span> <input class="batch_dothing"  type="radio" name="opaction" value="visible">是否启用</span>
                        <span> <input class="batch_dothing"  type="radio" name="opaction" value="clear">清除缓存</span>
                    </td>
                </tr> 
            </table>

            <table class="control_table batcn_table" id="action_delete" style="display: none;">
                <tr>
                    <td width="60" class="td_title"> 选择操作 </td>
                    <td>
                        <span> <input type="radio" name="opactionvalue" checked="true" value="delete">直接删除</span>
                    </td>
                </tr>                    
            </table> 

            <table class="control_table batcn_table" id="action_visible" style="display: none;">
                <tr>
                    <td width="60" class="td_title"> 选择操作 </td>
                    <td>
                        <span> <input type="radio" name="opvalue" value="1" checked="true">启用</span>
                        <span> <input type="radio" name="opvalue" value="-1">禁用</span>
                    </td>
                </tr>                    
            </table> 
            
            <table class="control_table batcn_table" id="action_clear" style="display: none;">
                <tr>
                    <td width="60" class="td_title"> 选择操作 </td>
                    <td>
                        <span> <input type="radio" name="opvalue" value="list_cache_menu" checked="true">直接清除</span>
                    </td>
                </tr>                    
            </table> 
            <table class="submit_table">
                <tr>
                    <td> 
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