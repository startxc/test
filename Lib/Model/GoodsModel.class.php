<?php
/*
*@author:bruce
*@desc:商品模型文件
*/
class GoodsModel extends Model{

	/*@desc:获取商品列表
	**$param = array(
	*	"is_recommend"=>"是否推荐"
	*	"is_group"=>"是否可以伙拼"
	*	"size"=>"获取商品个数"
	*	"order"=>"商品排序规则"
	*)
	*/
	public function getGoodsList($param=array()){
		$condition = array(
			"is_show"=>1,
			"is_deleted"=>0
		);
		if($param['is_recommend'] == 1){
			$condition['is_recommend'] = 1;
		}
		if($param['is_group'] == 1){
			$condition['is_group'] = 1;
		}
		$size = empty($param['size'])?20:intval($param['size']);
		switch($param['order']){
			case 1:$order = "order_index desc";break;
			default:$order = "create_time desc";
		}
		$count = M("Goods")->where($condition)->count();
		$goodsList = array();
		if($count>0){		
            if($size>0){ 
            	import("@.ORG.Util.Page");  
            	$p = new Page($count, $size);
            	$goodsList = M("Goods")->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order($order)->select();
            }else{
            	$goodsList = M("Goods")->where($condition)->order($order)->select();
            }
		}
		return $goodsList;
	}

	//获取伙拼列表
	public function getGroupList($date,$is_recommend=0,$size=20,$order="create_time desc"){
		$start_time = strtotime("$date 00:00:00");
		$end_time = strtotime("$date 23:59:59");
		$condition = array(
			"start_time"=>array("elt",$start_time),
			"end_time"=>array("egt",$end_time),
			"is_show"=>1,
		);
		if($is_recommend == 1){
			$condition['is_recommend'] = 1;
		}
		$count = M("Group")->where($condition)->count();
		$groupList = array();
		if($count>0){
			import("ORG.Util.Page");
			$p = new Page($count,$size);
			$groupList = M("Group")->where($condition)->order($order)->limit($p->firstRow.",".$p->listRows)->select();
		}
		return $groupList;
	}

	//获取商品产地列表
	public function getProductionList($goods_id=0){
		$productionList = S("production".$goods_id);
		if(empty($productionList)){
			$goods_production = M("Goods_production")->where("goods_id={$goods_id}")->select();
			$production_id = array();
			foreach($goods_production as $value){
				$production_id[] = $value['production_id'];
			}
			$productionList = M("Production")->where(array("id"=>array("in",$production_id)))->select();
			SK("production".$goods_id,$productionList);
		}
		return $productionList;
	}	

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