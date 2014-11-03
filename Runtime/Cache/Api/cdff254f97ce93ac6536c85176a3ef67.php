<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quality Intelligence System</title>
<script type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery-1.7.1.min.js"></script>
</head>
<body>
<div>
Current User：
  <?php echo $_SESSION['user']['screen_name']; $api_with_key = array(); foreach($api as $cate_id=> $value){ if($value['api'] && is_array($value['api'])){ array_walk($value['api'],function(&$item,$k,$cate){ $item['cate'] = $cate; },$cate_id); $api_with_key = array_merge($api_with_key,$value['api']); } } ?>
  <div>
  	   <!-- <a id="logout" href="__APP__/Public/logout" style="float:right;">Logout</a> -->
  </div>
  <a href="__URL__/doc">查看API文档</a>
</div>
<table>
<tr>
 <td style="width:300px;background-color:#F7F7F7;">
     <table id="post_table">
        <tr>
	        <td class="right">API 类别：</td>
	        <td>
		        <select id="api_category" name="api">
		          <option value="-1">All</option>
			      <?php if(is_array($api)): foreach($api as $key=>$item): ?><option value="<?php echo ($key); ?>"><?php echo ($item["category"]); ?></option><?php endforeach; endif; ?>
			    </select>
		    </td>
        </tr>
        <tr>
	        <td class="right">API Name：</td>
	        <td>
		        <select id="api" name="api">
		          <option value=""></option>
			      <?php if(is_array($api_with_key)): foreach($api_with_key as $key=>$item): ?><option value="<?php echo ($key); ?>" cate="<?php echo ($item["cate"]); ?>"><?php echo ($key); ?></option><?php endforeach; endif; ?>
			    </select>
		    </td>
        </tr>
        <tr>
        	<td colspan="2" id="desc"></td>
        </tr>
        <tr>
           <td class="right">
        		   Request Method：
           </td>
           <td>
           	 <label><input  type="radio" name="method" checked value="GET"/>GET</label>
           	 <label><input  type="radio" name="method" value="POST"/>POST</label>
           </td>
        </tr>
        <tr>
           <td colspan="2" nowrap='true'>
             <div id="params_box">
        	   API Param：
        	    <form method="get" id="api_form" target="api_ajax_frame" enctype="multipart/form-data">
	        	   <div id="params">
	        	       
	        	   </div>
        	    </form>
             </div>
          </td>
        </tr>
        <tr>
        	<td style="text-align:center;">
        		<input type="button" value="Call API" id="request_api"/>
        	</td>
        </tr>
     </table>
	    
 </td>
  <td>
      <div class="title">Request:</div>
      <div id="request">
      </div>
      <div class="title">Response Content：</div>
      <div id="response" contentEditable="true">
      </div>
      <!-- <h6>返回的内容：</h6> -->
 </td>
</tr>
</table>
<style>
*{font-family:Arial, Helvetica, sans-serif;font-size:13px;}
table td{text-align:left;vertical-align:top;padding:10px;}
 .right{text-align:right;width:80px;}
.key{width:90px;text-align:right;padding-right:5px;}
.val{width:150px;}
#post_table{border:0px;}
#post_table td{padding:2px;}
#request,#response{border: 1px solid #C6C6C6;box-shadow: 1px 1px 1px 0px #F0F0F0 inset;vertical-align: top;color: #666;line-height: 20px;padding:10px;overflow-x:auto;overflow-y: auto;width:800px;min-height:100px;}
.json_block{margin-left:40px;color:#518A00;}
.stronger{font-weight:bold;}
.title{padding:0;line-height:30px;height:30px;}
#desc{color:red;}
iframe{display:none;}
#params_box{padding:10px 8px;background-color:#FFF;border:1px solid #D1D1D1;}
#request_api{background-color:#8ADA00;color:#fff;border-radius:4px;border:1px solid #8ADA00;padding:4px 9px;font-size:13px;font-weight:bold;cursor:pointer;}
.Add_Input{cursor:pointer;color:rgb(60, 124, 179);}
#param div{margin:5px;margin-top:10px;display:block;}
span.delete{color:#C4C4C4;display:inline-block;margin-left:2px;cursor:pointer;}
#request table{border:none;}
#request table td{padding:2px 10px;}
</style>
<script type="text/javascript">
var current_dom;
var global_token = '<?php echo session_id(); ?>';
$(function(){
 	var api_width_key = <?php echo json_encode($api_with_key); ?>;
	var tab_index = 1;

	function getInput(field,type,add,desc){
		return '<div><input  class="key" value="'+field+'" />:<input name="'+field+'" class="val" '+(type && type=='file'?'type="file"':'')+' tabindex="'+(tab_index++)+'" placeHolder="'+(desc?desc:'')+'"/>'+
		(type && type=='image'?'<input type="button" value="Upload Picture" class="up_image"/>':'')+(add?'<span class="delete">╳</span>':'')+'</div>';
	}

	function paraseParam(fields,add){
		var html = '';
		if(add && (!fields || fields.length==0)){
			html = getInput('',false,add);
		}else{
			for(var k in fields){
				var f = fields[k];
				html += getInput(k,f[0],add,f['desc']);
			}
		}
		if(add){
			html += '<div class="Add_Input"><span>╋</span>Add</div>'
		}
		$('#params').html(html);
	}
	$('#api').change(function(){
		var val = $(this).val();
		if(!val || !api_width_key[val]) return;
		var api = api_width_key[val];
		$('#desc').html(api['desc']+'(<a href="__URL__/doc?field='+encodeURIComponent(val)+'" target="_blank">查看</a>)');
		paraseParam(api['field'],api['add']?true:false);
		if(api.type=='POST'){
			$('input[name=method][value=POST]').attr('checked','checked');
		}else{
			$('input[name=method][value=GET]').attr('checked','checked');
		}
	});
	$('#api_category').change(function(){
		var cate = $(this).val();
		var option = '<option value=""> </option>';
		for(var k in api_width_key){
			var ca = api_width_key[k];
			if(cate=='-1'|| ca['cate']==cate){
				option += '<option value="'+k+'">'+k+'</option>';
			}
		}
		$('#api').html(option);
	});
	
	$('#api_category,#api').change();
	$('span.delete').live('click',function(){
		$(this).parent().remove();
	});
	$('div.Add_Input').live('click',function(){
		html = getInput('',false,true);
		$(html).insertBefore(this);
	});
	$('input.key').live('keyup',function(){
		$(this).next().attr('name',$(this).val());
	});


	$('#request_api').click(function(){
		var api_name = $('#api').val();
		if(api_name==''){
			alert("Please Select Api Name!");
			return ;
		}
		var url = '__APP__/Api/'+api_name;
		var method = $('input[name=method]:checked').length?$('input[name=method]:checked').val():'get';
		$('#response').html('');
		var tr = '';
		var request_data = {};
		$('#api_form').find('input').each(function(){
			var name = $(this).attr('name');
			if(name && name!='__hash__'){
				request_data[name] = $(this).val();
				tr += '<tr><td class="right">'+name+':</td><td>'+$(this).val()+'</td></tr>';
			}
		});
		
		if('|Public/checkLogin|AppVersion/getVersion|AppLog/index|'.indexOf('|'+api_name+'|')==-1){
			request_data['token'] = global_token;
			tr += '<tr><td class="right">token:</td><td>'+global_token+'</td></tr>';
		}
		
		if(! $('#api_form input:file').length){
			$.ajax({
				type:method,
				data:request_data,
				url:url,
				success:formateHtml,
				error:function(data){
						$('#response').html('Error:'+data+'');
				}
			});
		}else{
			$('#api_form').attr('action',url).attr('method','post');
			$('#api_form').submit();
		}
		var request_html = '';
		request_html += '<div>Request Method : '+method+'</div>';
		request_html += '<div>URL : http://'+location.host+url+'</div>';
		request_html += '<div>Request Param :</div>';

		request_html += tr==''?'':('<table>'+tr+'</table>');
		$('#request').html(request_html);
	});

	$('#logout').click(function(){
		$.getJSON('__APP__/Public/logout',function(data){
			location.href = location.href;
		});
		return false;
	});
	$('div.json_block').live('mouseover',function(){
		$(this).addClass('stronger');
		return false;
	}).live('mouseout',function(){
		$(this).removeClass('stronger');
	});
	
	//上传图片
	$('input.up_image').live('click',function(){
		current_dom = $(this).prev().get(0);
		window.open ('__URL__/file','newwindow','height=100,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
	});
});
function isArray(data){
	return data && data.length!=undefined && typeof data.push=='function';
}
function getContent(){
	var content = $('#api_ajax_frame').contents();
	if(content){
		var html = content.find('body').html();
		if(html){
			formateHtml(html);
		}
	}
}

function genJsonHtml(data){
	var html = '';
	if(data && typeof data =='object'){
		//如果是数组
		if(isArray(data)){
			for(var k in data){
				html += '<div class="json_block">'+genJsonHtml(data[k])+',</div>';
			}
			return '['+html+']';
		}else{
			for(var k in data){
			    html += '<div class="json_block">"'+k+'":'+genJsonHtml(data[k])+',</div>';
			}
			return '{'+html+'}';
		}
	}else{
		return typeof data=='string'?'"'+data+'"':data+'';
	}
}

function formateHtml(json_data){
	try{
		eval('var data=('+json_data+');');
		if(data.token){
			global_token = data.token;
		}
		$('#response').html(genJsonHtml(data));
	}catch(e){
		$('#response').html(json_data);
	}
}
</script>
<iframe name="api_ajax_frame" id="api_ajax_frame" onload="getContent()"></iframe>
</body>
</html>