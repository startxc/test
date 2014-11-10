<?php
/*
*@author:bruce
*@desc:广告模型文件
*/
class AdvModel extends Model{

	//获取某个位置的广告
	public function getAdvList($code="",$size=1){
		$advList = array();
		$position_id = M("Adv_position")->where("code='{$code}'")->getField("id");
		if(empty($position_id)){
			return $advList;
		}
		if($size == 1){
			$advList = M("Adv")->where("position_id={$position_id} && enabled=1")->find();
		}else{
			$advList = M("Adv")->where("position_id={$position_id} && enabled=1")->order("sort desc")->limit($size)->select();
		}
		return $advList;
	}
}
?>