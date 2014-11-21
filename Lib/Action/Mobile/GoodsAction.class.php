<?php
class GoodsAction extends CommonAction{

	//商品列表
	public function index(){
		$categoryList = D("Goods")->getCategoryList();
		$size = 20;
		$param = array(
			"size"=>$size,
		);
		if(!empty($_GET['cid'])){
			$param['category_id'] = intval($_GET['cid']);
			$category = M("Category")->where("id={$param['category_id']}")->getField("name");
			$this->assign("category",$category);
		}
		$goodsList = D("Goods")->getGoodsList($param);
		$totalPage = ceil($goodsList['count']/$size);
		$this->assign("totalPage",$totalPage);
		$this->assign("size",$size);
		$this->assign("goodsList",$goodsList['data']);
		$this->assign("categoryList",$categoryList);
		$this->display();
	}

	//商品详情
	public function detail(){
		$id = intval($_GET['id']);
		$goods = M("Goods")->where("id={$id} && is_show=1")->find();
		if(empty($goods)){
			$this->error("商品不存在");
			exit();
		}
		$category = M("Category")->where("id={$goods['category_id']}")->getField("name");
		$this->assign("goods",$goods);
		$this->assign("category",$category);	
		$this->display();
	}


	//伙拼商品首页
	public function group(){
		$size = 20;
		$time = time();
		$this->assign("time",$time);
		$param = array(
			"date"=>date("Y-m-d",$time),
			"is_recommend"=>1,
			"size"=>$size
		);
		$groupList = D("Goods")->getGroupList($param);
		$totalPage = ceil($groupList['count']/$size);
		$this->assign("totalPage",$totalPage);
		$this->assign("size",$size);
		$this->assign("groupList",$groupList['data']);
		$this->display();
	}

	//发起伙拼页面
	public function groupApply(){
		$param = array(
			"is_group"=>1,
			"size"=>0
		);
		$goodsList = D("Goods")->getGoodsList($param);
		$this->assign("goodsList",$goodsList['data']);
		
		//发起伙拼顶部广告
		$groupApply_top = D("Adv")->getAdvList("groupApply_top");
		$this->assign("groupApply_top",$groupApply_top);
		$this->display();
	}

	//伙拼商品列表
	public function groupList(){
		$size = 20;
		$param = array(
			"size"=>$size
		);
		if($_GET['keyword']){
			$param['keyword'] = trim($_GET['keyword']);
		}
		$this->assign("keyword",$param['keyword']);
		$groupList = D("Goods")->getGroupList($param);
		$totalPage = ceil($groupList['count']/$size);
		$this->assign("totalPage",$totalPage);
		$this->assign("size",$size);
		$this->assign("groupList",$groupList['data']);
		$this->display();
	}

	//伙拼商品详情
	public function groupDetail(){
		$id = intval($_GET['id']);
		$group = M("Group")->where("id={$id} && is_show=1")->find();
		if(empty($group)){
			$this->error("伙拼商品不存在");
			exit();
		}
		$group_buy_status = 1;
		$time = time();
		if($group['start_time']>$time){
			$group_buy_status = 0; //伙拼还未开始
		}
		if($group['end_time']<$time){
			$group_buy_status = -1; //伙拼已经结束
		}
		$this->assign("group_buy_status",$group_buy_status);


		$spec = M("Goods")->where("id={$group['goods_id']}")->getField("spec");
		$category = M("Category")->where("id={$group['category_id']}")->getField("name");
		$production = M("Production")->where("id={$group['production_id']}")->getField("name");

		$percent = floor(($group['price']-$group['real_price'])/($group['price']-$group['min_price']))*100;
		$this->assign("percent",$percent);
		$this->assign("group",$group);
		$this->assign("spec",$spec);
		$this->assign("category",$category);
		$this->assign("production",$production);
		$this->display();
	}

}
?>