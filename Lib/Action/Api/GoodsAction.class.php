<?php
/**
*@author:bruce
*@desc:商品与伙拼相关接口
*/

class GoodsAction extends MobileCommonAction{

	//获取伙拼商品列表
	public function getGroupList(){
		$time = time();
		$condition = array(
			"status"=>1
			"start_time"=>array("lt",$time),
			"end_time"=>array("gt",$time)
		);
	}

	//获取普通商品列表
	public function getGoodsList($size=19,$order="create_time desc"){
		$condition = array(
			"status"=>1,
			"deleted"=>0
		);
		$count = M("Goods")->where($condition)->count();
		$goodsList = array();
		if($count>0){
			import("@.ORG.Util.Page");  
            $p = new Page($count, $size);
            $goodsList = M("Goods")->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order($order)->select();
		}
		$this->ajaxRespon($goodsList);
		return $goodsList;
	}

	//获取商品分类列表
	public function getCategoryList(){
		$category = M("Category")->field("id,name,image")->where("status=1")->order("order_index desc")->select();
		$this->ajaxRespon($category);
		return $category;
	}

	//获取某一分类的商品
	public function getGoodsByCategoryId($cid=0,$size=19){
		$cid = empty($_GET['cid'])?$cid:$_GET['cid'];
		$cid = intval($cid);
		$condition = array(
			"category_id"=>$cid,
			"status"=>1,
			"deleted"=>0
		);
		$count = M("Goods")->where($condition)->count();
		$goodsList = array();
		if($count>0){
            import("@.ORG.Util.Page");  
            $p = new Page($count, $size);
            $goodsList = M("Goods")->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order("create_time desc")->select();
		}
		$this->ajaxRespon($goodsList);
		return $goodsList;
	}
}
?>