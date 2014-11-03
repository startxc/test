// JavaScript Document
/*
*功能：省市县联动函数 。
*时间：2012年11月7日 星期三（创建）
*作者：Indar and gxf
用法：initCACL(select标签省的id,select标签市的id,select标签区的id,（数据库中省的id）pv,（数据库中市的id）cv,（数据库中区的id）tv)（后面的数据库中的各项id可以留空）
在使用时要定义以下变量
var region = '{$regionjson}';
region = eval("("+region+")");
注意，表单返回值是相应的省市区的id号
*/
var region;
function initCACL(province,city,county,pv,cv,tv){
	province =$("#"+province);
	city = $("#"+city);
	county = $("#"+county);
	pv = pv =="undefined" || typeof pv!="string" ? 0 :pv ;
	cv = cv =="undefined" || typeof cv!="string"? 0 : cv;
	tv = tv =="undefined" || typeof tv!="string"? 0 : tv;
	if(!region)
	{
		return false;
		}
	for(var i=0;i<region.length;i++){
			//这里是初始化已存在地址
			if(pv!=0&&region[i].id==pv){
				province.append("<option name="+i+" value="+region[i].id+" selected='selected'>"+region[i].name+"</option>"); 
				var t="";
				for(var j=0;j<region[i].child.length;j++){
					if(cv!=0&&region[i].child[j].id==cv){
						t+="<option name='"+j+","+i+"' value='"+region[i].child[j].id+"' selected='selected'>"+region[i].child[j].name+"</option>";
						if(region[i].child[j].child!=null){
							for(var k=0;k<region[i].child[j].child.length;k++){ 
							if(tv!=0 && region[i].child[j].child[k].id==tv){
								county.append("<option name='"+k+"' value='"+region[i].child[j].child[k].id+"' selected='selected'>"+region[i].child[j].child[k].name+"</option>");
							}else{
								county.append("<option name='"+k+"' value='"+region[i].child[j].child[k].id+"'>"+region[i].child[j].child[k].name+"</option>");
							}
						}
							}
						
					}else{
						t+="<option name='"+j+","+i+"' value='"+region[i].child[j].id+"'>"+region[i].child[j].name+"</option>"
					}
				}
				city.append(t); 
			}else{
				province.append("<option name="+i+" value="+region[i].id+">"+region[i].name+"</option>");
			}
	}
	province.change(function(){
			city.empty();
			county.empty();
			city.append("<option value=''>请选择</option>");
			county.append("<option value=''>请选择</option>");
			if($(this).val()!=""){
				var provinceId=($(this).find("option:selected").attr("name"));
				var t="";
				for(var i=0;i<region[provinceId].child.length;i++){
					t+="<option name='"+i+","+provinceId+"' value='"+region[provinceId].child[i].id+"'>"+region[provinceId].child[i].name+"</option>"
				}
				city.append(t); 
			}
	});
	city.change(function(){
			county.empty();
			county.append("<option value=''>请选择</option>");
			if($(this).val()!=""){ 
				var data=($(this).find("option:selected").attr("name")).split(",");
				var cityId=data[0],provinceId=data[1];
				if(region[provinceId].child[cityId].child != null){
					for(var i=0;i<region[provinceId].child[cityId].child.length;i++){ 
				
					county.append("<option name='"+i+"' value='"+region[provinceId].child[cityId].child[i].id+"'>"+region[provinceId].child[cityId].child[i].name+"</option>");
					}
				
				}
			} 
	});
}