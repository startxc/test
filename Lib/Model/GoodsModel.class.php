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
		if(!empty($param['category_id'])){
			$condition['category_id'] = intval($param['category_id']);
		}
		$size = empty($param['size'])?20:intval($param['size']);
		switch($param['order']){
			case 1:$order = "order_index desc";break;
			default:$order = "create_time desc";
		}
		$count = M("Goods")->where($condition)->count();
		$goodsList = array(
			'count'=>$count,
			'data'=>array()
		);
		if($count>0){		
            if($size>0){ 
            	import("@.ORG.Util.Page");  
            	$p = new Page($count, $size);
            	$goodsList['data'] = M("Goods")->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order($order)->select();
            }else{
            	$goodsList['data'] = M("Goods")->where($condition)->order($order)->select();
            }

		}
		return $goodsList;
	}

	//获取伙拼列表
	public function getGroupList($param=array()){
		$condition = array(
			"is_show"=>1,
		);
		if(!empty($param['date'])){
			$start_time = strtotime("{$param['date']} 00:00:00");
			$end_time = strtotime("{$param['date']} 23:59:59");
			$condition['start_time'] = array("elt",$start_time);
			$condition['end_time'] = array("egt",$end_time);
		}
		if($param['is_recommend'] == 1){
			$condition['is_recommend'] = 1;
		}
		if(!empty($param['keyword'])){
			$condition['name'] = array("like","%{$param['keyword']}%");
		}
		$size = empty($param['size'])?0:intval($param['size']);
		switch($param['order']){
			default:$order="create_time desc";
		}
		$count = M("Group")->where($condition)->count();
		$groupList = array(
			'count'=>$count,
			'data'=>array()
		);
		if($count>0){
			if($size>0){
				import("ORG.Util.Page");
				$p = new Page($count,$size);
				$groupList['data'] = M("Group")->where($condition)->order($order)->limit($p->firstRow.",".$p->listRows)->select();
			}else{
				$groupList['data'] = M("Group")->where($condition)->order($order)->select();
			}
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

	//获取商品分类列表
	public function getCategoryList(){
        $categoryList = M("Category")->where("is_show=1")->order("order_index desc")->select();
        return $categoryList;	
	}	

	//获取我发起的伙拼
	public function getMyGroupApply($param){
		$condition = array(
			"member_id"=>$_SESSION['uid']
		);
		switch($param['status']){
			case 1:$condition['status'] = 1;break;
			case 2:$condition['status'] = 2;break;
			case 3:$condition['status'] = 3;break;
		}
		$size = empty($param['size'])?0:intval($param['size']);
		$count = M("Group_apply")->where($condition)->count();
		$groupApply = array(
			'count'=>$count,
			'data'=>array()
		);
		if($count>0){
			if($size>0){
				import("ORG.Util.Page");
				$p = new Page($count,$size);
				$groupApply['data'] = M("Group_apply")->where($condition)->order("create_time desc")->limit($p->firstRow.",".$p->listRows)->select();
			}else{
				$groupApply['data'] = M("Group_apply")->where($condition)->order("create_time desc")->select();
			}
			foreach($groupApply['data'] as $key=>$value){
				if(!empty($value['group_id'])){
					$groupApply['data'][$key]['group'] = M("Group")->where("id={$value['group_id']}")->find();
					$groupApply['data'][$key]['group']['diffTime'] = diffTime($groupApply['data'][$key]['group']['start_time'],$groupApply['data'][$key]['group']['end_time']);
					$groupApply['data'][$key]['group']['imgsrc'] = picture($groupApply['data'][$key]['group']['image'],'', 'product');
				}else{ 
					$groupApply['data'][$key]['imgsrc'] = picture($value['image'],'', 'product');
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
				case 1: $count['wait'] = $value['num'];break;
				case 2: $count['fail'] = $value['num'];break;
				case 3: $count['success'] = $value['num'];break;
			}
		}
		$count['all'] = $count['wait']+$count['fail']+$count['success'];
		return $count;
	}

	//更新伙拼的数量
	public function updateGroupNum($group_phase_id,$num){
		$group_phase = M("Group_phase")->where("id={$group_phase_id}")->find();
		$group = M("Group")->where("id={$group_phase['group_id']}")->find();
		$spec = M("Goods")->where("id={$group['goods_id']}")->getField("spec");
		$add_spec = $spec*$num;
		$percent =	min(max(0,$group_phase['sale_spec']+$add_spec-$group_phase['moq_spec']), $group['min_price_spec']-$group['moq_spec'])/($group['min_price_spec']-$group['moq_spec']);
		$real_price = $group_phase['price']-($group_phase['price']-$group_phase['min_price'])*$percent;
		$sale_count = $group_phase['sale_count']+$num;
		$sale_spec = $group_phase['sale_spec']+$add_spec;
		$time = time();
		$group_phase_data = array(
			"real_price"=>$real_price,
			"sale_count"=>$sale_count,
			"sale_spec"=>$sale_spec,
			"update_time"=>$time
		);
		M("Group_phase")->where("id={$group_phase['id']}")->save($group_phase_data);
		if($group['group_phase_id'] == $group_phase['id']){
			$sale_total_count = $group['sale_total_count']+$num;
			$sale_total_spec = $group['sale_total_spec']+$add_spec;
			$group_data = array(
				"real_price"=>$real_price,
				"sale_count"=>$sale_count,
				"sale_spec"=>$sale_spec,
				"sale_total_spec"=>$sale_total_spec,
				"sale_total_count"=>$sale_total_count,
				"update_time"=>$time
			);
			M("Group")->where("id={$group['id']}")->save($group_data);
		}
	}

}
?>