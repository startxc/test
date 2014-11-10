<?php
/*
*@author:bruce
*@desc:首页
*/
class IndexAction extends Action{

	public function index(){
		//伙拼水果

		//特价菜色

		//热门菜谱

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