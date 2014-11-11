<?php
/*
*@author bruce;
*@desc 地区管理模型文件
*/
class RegionModel extends Model{
	
	//获取省份列表
	public function getProvince(){
		$province = S("region_province");
		if(empty($province)){
			$province = M("Region")->where("type=1")->select();
			SK("region_province",$province);
		}
		return $province;
	}

	//获取城市列表
	public function getCity($province_id=0){
		$city = S("region_city".$province_id);
		if(empty($city)){
			$city = M("Region")->where("parent_id={$province_id}")->select();
			SK("region_city".$province_id,$city);
		}
		return $city;
	}

	//获取地区(县)列表
	public function getArea($city_id=0){
		$area = S("region_area".$city_id);
		if(empty($area)){
			$area = M("Region")->where("parent_id={$city_id}")->select();
			SK("region_area".$city_id,$area);
		}
		return $area;
	}

	//获取社区列表
	public function getCommunity($area_id=0){
		$community = S("region_community".$area_id);
		if(empty($community)){
			$community = M("Community")->where("region_id={$area_id}")->select();
			SK("region_community".$area_id,$community);
		}
		return $community;
	}
}
?>