<?php
/*
*@author:bruce
*@desc:商品模型文件
*/
class GoodsModel extends Model{

	//获取我发起的伙拼
	public function getMyGroupApply($uid,$status=0){
		$condition = array();
		$condition['member_id'] = $uid;
		switch($status){
			case 1:$condition['status'] = 1;break;
			case 2:$condition['status'] = 2;break;
			case 3:$condition['status'] = 3;break;
		}
		$count = M("Group_apply")->where($condition)->count();
		$groupApply = array();
		if($count>0){
			//import("ORG.Util.Page");
			//$p = new Page($count,$size);
			$groupApply = M("Group_apply")->where($condition)->order("create_time desc")->select();
			foreach($groupApply as $key=>$value){
				if(!empty($value['group_id'])){
					$groupApply[$key]['group'] = M("Group")->where("id={$value['group_id']}")->find();
				}
			}
		}
		return $groupApply;
	}

	//获取我发起伙拼的次数
	public function countMyGroupApply($uid){
		$count = array(
			"all"=>0,
			"wait"=>0,
			"fail"=>0,
			"success"=>0
		);
		$sql = "select status,count(id) as num from lj_group_apply where member_id={$uid} group by status";
		$model = new Model();
		$group_apply = $model->query($sql);
		foreach($group_apply as $value){
			switch($value['status']){
				case 1: $count['wait'] = $value['num'];
				case 2: $count['fail'] = $value['num'];
				case 3: $count['success'] = $value['num'];
			}
		}
		$count['all'] = $count['wait']+$count['fail']+$count['success'];
		return $count;
	}

}
?>