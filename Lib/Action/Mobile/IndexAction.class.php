<?php
/*
*@author:bruce
*@desc:首页
*/
class IndexAction extends Action{

	public function index(){
		//伙拼水果
		$index_group = D("Adv")->getAdvList("index_group",4);
		$this->assign("index_group",$index_group);

		//特价菜色
		$index_goods = D("Adv")->getAdvList("index_goods",4);
		$this->assign("index_goods",$index_goods);

		//热门菜谱
		$index_weekmenu = D("Adv")->getAdvList("index_weekmenu",4);
		$this->assign("index_weekmenu",$index_weekmenu);

		//首页中间左边广告
		$index_middle_left = D("Adv")->getAdvList("index_middle_left");
		$this->assign("index_middle_left",$index_middle_left);

		//首页中间右上广告
		$index_middle_rightTop = D("Adv")->getAdvList("index_middle_rightTop");
		$this->assign("index_middle_rightTop",$index_middle_rightTop);

		//首页中间左下广告
		$index_middle_rightBottom = D("Adv")->getAdvList("index_middle_rightBottom");
		$this->assign("index_middle_rightBottom",$index_middle_rightBottom);		

		//每个分类下的商品
		$category = A("Api/Goods")->getCategoryList();
		$goodsList = array();
		foreach($category as $value){
			$goods = A("Api/Goods")->getGoodsByCategoryId($value['id'],19);
			$goodsList[] = array(
				              "cid"=>$value['id'],
				              "cname"=>$value['name'],
				              "goods"=>$goods,
				              "count"=>count($goods)
				           );
		}
		$this->assign("goodsList",$goodsList);
		$this->display();
	}
}
?>