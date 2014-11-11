/*
*功能：省市县联动函数 。
*时间：2012年11月7日 星期三（创建）
*作者：Indar and gxf
用法：（数据库中省的id）province_id,（数据库中市的id）city_id,（数据库中区的id）area_id,(数据库中社区的id)community_id)（后面的数据库中的各项id可以留空）;
注意，表单返回值是相应的省市区的id号
*/
var myRegion = (function(my,$){
	var province =$("#province");
	var city = $("#city");
	var area = $("#area");
	var community = $("#community")
	var province_id;
	var city_id;
	var area_id; 
	var community_id;
	my.init = function(iProvince_id,iCity_id,iArea_id,iCommunity_id){

			province_id = iProvince_id == undefined ? 0 :iProvince_id ;
			city_id = iCity_id == undefined ? 0 : iCity_id;
			area_id = iArea_id == undefined ? 0 : iArea_id;
			community_id = iCommunity_id == undefined ? 0 : iCommunity_id;

			var province_data = province.data("province");
			if(province_data == null){
				$.ajax({
					type:"post",
					url:"/Api/Region/getProvince",
					async:false,
					success:function(data){
						var res  = $.parseJSON(data);
						province_data = res.data;
						province.data("province",province_data);
					}
				});
			}
			for(var i in province_data){
					if(province_data[i].id == province_id){
						province.append("<option value='"+province_data[i].id+"' selected>"+province_data[i].name+"</option>");
						my.changeProvince(province_id);
					}else{
						province.append("<option value='"+province_data[i].id+"'>"+province_data[i].name+"</option>");
					}
			}
			province.change(function(){
				my.changeProvince($(this).val());
			});
			city.change(function(){
				my.changeCity($(this).val());
			});
			area.change(function(){
				my.changeArea($(this).val());
			});
	};

	my.changeProvince = function(province_id){
			city.empty();
			area.empty();
			community.empty();
			city.append("<option value=''>请选择</option>");
			area.append("<option value=''>请选择</option>");
			community.append("<option value=''>请选择</option>");
			if(province_id!=""){
				var city_data = city.data("city"+province_id);
				if(city_data == null){
					$.ajax({
						type:"post",
						url:"/Api/Region/getCity",
						data:{province_id:province_id},
						async:false,
						success:function(data){
							var res = $.parseJSON(data);
							city_data = res.data
							city.data("city"+province_id,city_data);
						}
					});
				}
				for(var i in city_data){
					if(city_data[i].id == city_id){
						city.append("<option value='"+city_data[i].id+"' selected>"+city_data[i].name+"</option>");
						my.changeCity(city_id);
					}else{
						city.append("<option value='"+city_data[i].id+"'>"+city_data[i].name+"</option>");
					}
				}
			}	
	};

	my.changeCity = function(city_id){
			area.empty();
			community.empty();
			area.append("<option value=''>请选择</option>");
			community.append("<option value=''>请选择</option>");
			if(city_id!=""){
				var area_data = area.data("area"+city_id);
				if(area_data == null){
					$.ajax({
						type:"post",
						url:"/Api/Region/getArea",
						data:{city_id:city_id},
						async:false,
						success:function(data){
							var res = $.parseJSON(data);
							area_data = res.data;
							area.data("area"+city_id,area_data);
						}
					});
				}
				for(var i in area_data){
					if(area_data[i].id == area_id){
						area.append("<option value='"+area_data[i].id+"' selected>"+area_data[i].name+"</option>");
						my.changeArea(area_id);
					}else{
						area.append("<option value='"+area_data[i].id+"'>"+area_data[i].name+"</option>");
					}
				}
			}	
	};

	my.changeArea = function(area_id){
			community.empty();
			community.append("<option value=''>请选择</option>");
			if(area_id!=""){
				var community_data = community.data("community"+area_id);
				if(community_data == null){
					$.ajax({
						type:"post",
						url:"/Api/Region/getCommunity",
						data:{area_id:area_id},
						async:false,
						success:function(data){
							var res = $.parseJSON(data);
							community_data = res.data;
							community.data("community"+area_id,community_data);
						}
					});
				}
				for(var i in community_data){
					if(community_data[i].id == community_id){
						community.append("<option value='"+community_data[i].id+"' selected>"+community_data[i].name+"</option>");
					}else{
						community.append("<option value='"+community_data[i].id+"'>"+community_data[i].name+"</option>");
					}
				}
			}
	}
	return my;
})({},jQuery);


