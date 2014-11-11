<?php
/*
*@author:bruce
*@desc:获取地区信息接口
*/
class RegionAction extends MobileCommonAction{
	
	//获取省份列表
	public function getProvince(){
		$province = D("Region")->getProvince();
		$this->ajaxRespon($province);
	}

	//获取城市列表
	public function getCity(){
		$province_id = intval($_POST['province_id']);
		$city = D("Region")->getCity($province_id);
		$this->ajaxRespon($city);
	}

	//获取地区(县)列表
	public function getArea(){
		$city_id = intval($_POST['city_id']);
		$area = D("Region")->getArea($city_id);
		$this->ajaxRespon($area);
	}

	//获取社区列表
	public function getCommunity(){
		$area_id = intval($_POST['area_id']);
		$community = D("Region")->getCommunity($area_id);
		$this->ajaxRespon($community);
	}
}
?>