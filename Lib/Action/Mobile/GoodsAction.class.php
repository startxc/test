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
	}

	//伙拼商品列表
	public function groupList(){
		
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