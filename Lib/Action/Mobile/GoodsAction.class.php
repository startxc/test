<?php
class GoodsAction extends CommonAction{

	//商品列表
	public function index(){
		$this->display();
	}

	//商品详情
	public function detail(){
		$this->display();
	}


	//伙拼商品首页
	public function group(){
		$date = date("Y-m-d",time());
		$groupList = D("Goods")->getGroupList($date,1);
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
		$date = date("Y-m-d",time());
		$groupList = D("Goods")->getGroupList($date);
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