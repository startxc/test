<?php
class GoodsAction extends CommonAction{

	//商品列表
	public function index(){
		$categoryList = D("Goods")->getCategoryList();
		$param = array(
			"size"=>20,
		);
		if(!empty($_GET['cid'])){
			$param['category_id'] = intval($_GET['cid']);
			$category = M("Category")->where("id={$param['category_id']}")->getField("name");
			$this->assign("category",$category);
		}
		$goodsList = D("Goods")->getGoodsList($param);
		$this->assign("categoryList",$categoryList);
		$this->assign("goodsList",$goodsList);
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
		$param = array(
			"date"=>date("Y-m-d",time()),
			"is_recommend"=>1,
			"size"=>20
		);
		$groupList = D("Goods")->getGroupList($param);
		$this->assign("groupList",$groupList);
		$this->display();
	}

	//发起伙拼页面
	public function groupApply(){
		$param = array(
			"is_group"=>1,
			"size"=>0
		);
		$goodsList = D("Goods")->getGoodsList($param);
		$this->assign("goodsList",$goodsList);
		$this->display();
	}

	//伙拼商品列表
	public function groupList(){
		$param = array(
			"size"=>20
		);
		if($_GET['keyword']){
			$param['keyword'] = trim($_GET['keyword']);
		}
		$groupList = D("Goods")->getGroupList($param);
		$this->assign("groupList",$groupList);
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