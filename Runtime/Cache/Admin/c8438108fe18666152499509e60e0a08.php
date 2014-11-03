<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkcms.css"/>
        <link type="text/css" rel="stylesheet" href="__ROOT__/Public/Css/thinkright.css"/>
        <script type="text/javascript" src="__ROOT__/Public/Js/jquery.min.js"></script>
        <script type="text/javascript">
            function init() {
                $("#loader").hide();
            }
            function chooseAll() {
                $(".td_items input").attr("checked", "checked");
            }
            function quitAll() {
                $(".td_items input").removeAttr("checked");
            }
            $(function(){
                //var h = $(document).height();
                //$("#prompt").css("margin-top", (h-120)/2-20);
            });
        </script>
        <style type="text/css">
            #middle { margin: 0; }
            
            #prompt { width: 100%; color: red; font-weight: bolder; }
            #prompt table { border-collapse: collapse; width: 100%; }
            #prompt table tr th { border: 1px solid #EEE; padding: 5px; }
            #prompt table tr td { padding-left: 10px; text-align: left; border: 1px solid #EEE; padding: 5px; }
            #prompt table tr td.td_left { width: 150px; }
        </style>
    </head>
    <body onload="init();" >
        <div id="loader"> 页面加载中... </div>
        <div id="middle">
            <div id="prompt">
                
                <table>
                    <tr> <th colspan="2" > 槃思平台管理中心 </th> </tr>
                    <?php foreach ($system as $key => $value) { ?>
                        <tr>
                            <td class="td_left"> <?php echo ($key); ?> </td> <td> <?php echo ($value); ?> </td>
                        </tr>
                    <?php } ?>
                </table>
                
            </div>
        </div>
    </body>
</html>