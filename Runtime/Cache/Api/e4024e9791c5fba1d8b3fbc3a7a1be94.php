<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quality Intelligence System</title>
<script type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery-1.7.1.min.js"></script>
<style type="text/css">
body{background:#e4e4e4;}
#main{width:950px;margin:10px auto;background: #fff;border-radius: 8px;box-shadow: 0 0 7px rgba(0,0,0,0.1);overflow: hidden;padding: 1px;position: relative;min-height:700px;}
#left_menu{float: left;width: 220px;color:#5454A2;background-color:#F7F7F7;border-radius:8px 0 0;min-height:700px;}
.list{margin:10px 0px;}
.fold{float:right;margin-right:20px;display:inline-block;}
.list div{margin-left:20px;margin-top:10px;line-height:18px;font-size:14px;color:#000;font-family: 'Microsoft Yahei';cursor:pointer;}
.list div.son{display:none;margin-left:30px;}
.list div.son a{display:block;width:100%;text-align:left;line-height:30px;height:30px;color:#0054F0;font-size:12px;}
.list div.son a.active{color:red;}
.wiki_title{font-size: 14px;font-weight: bold;color: #333;line-height:37px;height:37px;background: #EDF3F9;border: none;margin: 16px 0 8px 0;padding: 0 0 0 15px;width:95%;}
#right_content{float:left;margin-left:20px;width:710px;}
.wiki_content{margin-left:15px;font-size:12px;word-break:break-all;overflow-wrap: break-word;}
.wiki_content table{border:1px solid #ccc;border-right:none;border-bottom:none;width:90%;}
.wiki_content table td,.wiki_content table th{border:1px solid #ccc;border-top:none;border-left:none;text-align:left;padding:4px 5px;}
</style>
</head>
<body>
	<a href="__URL__">API测试工具</a>
<div id="main">
	<div id="left_menu">
		<?php if(is_array($api)): foreach($api as $key=>$item): ?><div class="list">
				<div class="title"><?php echo ($item["category"]); ?><a class="fold">+</a></div>
				<div class="son" <?php if($current_key==$key): ?>style="display:block;"<?php endif; ?>>
					<?php if(is_array($item['api'])): foreach($item['api'] as $k=>$it): ?><a href="__URL__/doc?field=<?php echo (urlencode($k)); ?>" <?php if($k==$_GET['field']): ?>class="active"<?php endif; ?>><?php echo ($it["desc"]); ?></a><?php endforeach; endif; ?>
				</div>
			</div><?php endforeach; endif; ?>
	</div>
	<div id="right_content">
		<?php if($current_api): ?><h2 class="wiki_title"><?php echo ($current_api["name"]); ?></h2>
			<div class="wiki_content"><?php echo ($current_api["desc"]); ?></div>
			<h2 class="wiki_title">URL</h2>
			<div class="wiki_content">
				<a href="__APP__/Api/<?php echo ($current_api["name"]); ?>" style="text-decoration:none;" target="_blank">http://<?php echo ($_SERVER["HTTP_HOST"]); ?>__APP__/Api/<?php echo ($current_api["name"]); ?></a>
			</div>
			<h2 class="wiki_title">HTTP请求方式</h2>
			<div class="wiki_content">
				<?php if($current_api['type']): echo ($current_api['type']); else: ?>GET<?php endif; ?>
			</div>
			<h2 class="wiki_title">请求参数</h2>
			<div class="wiki_content">
				<?php if($current_api['field']): ?><table cellpadding="0" border="0" cellspacing="0">
						<tr>
							<th>名称</th>
							<th>说明</th>
						</tr>
						<?php if(is_array($current_api['field'])): foreach($current_api['field'] as $key=>$item): ?><tr>
								<td><?php echo ($key); ?></td>
								<td><?php echo ($item["desc"]); ?></td>
							</tr><?php endforeach; endif; ?>
					</table>
				<?php else: ?>
				无<?php endif; ?>
			</div>
			<h2 class="wiki_title">返回结果示例</h2>
			<div class="wiki_content">
				<?php echo ($current_api['response']); ?>
			</div>
			<h2 class="wiki_title">返回字段说明</h2>
			<div class="wiki_content">
				<?php if($current_api['res_field']): ?><table cellpadding="0" border="0" cellspacing="0">
						<tr>
							<th>返回值字段</th>
							<th>说明</th>
						</tr>
						<?php if(is_array($current_api['res_field'])): foreach($current_api['res_field'] as $key=>$item): ?><tr>
								<td><?php echo ($item["field"]); ?></td>
								<td><?php echo ($item["desc"]); ?></td>
							</tr><?php endforeach; endif; ?>
					</table>
				<?php else: ?>
				无<?php endif; ?>
			</div><?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('.title').click(function(){
			$(this).next().toggle();
		});
	})
</script>
</body>
</html>