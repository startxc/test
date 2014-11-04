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
			"status"=>1,
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

	//申请伙拼
	public function applyGroup(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$goods_id = intval($_POST['goods_id']);
		$this->isEmpty($goods_id,"商品不能为空哦");
		$production_id = intval($_POST['production_id']);
		$this->isEmpty($production_id,"产地不能为空哦");
		$remark = trim($_POST['remark']);
		$this->isEmpty($remark,"个人说明不能为空哦");
		$member_type = intval($_POST['member_type']);
		$this->isEmpty($member_type,"广而告知对象不能为空");
		$goods = M("Goods")->where("id={$goods_id} && is_show=1 && is_deleted=0")->find();
		if(empty($goods)){
			$this->error("发起伙拼的商品不存在哦");
		}
		if(M("Group")->where("goods_id={$goods_id}")->find()){
			$this->error("该商品已经被人申请过伙拼啦");
		}
		$data = array(
			"member_id"=>$uid,
			"member_name"=>$_SESSION['nickname'],
			"goods_id"=>$goods_id,
			"production_id"=>$production_id,
			"category_id"=>$goods['category_id'],
			"price"=>$goods['price'],
			"name"=>$goods['name'],
			"image"=>$goods['image'],
			"remark"=>$remark,
			"member_type"=>$member_type,
			"create_time"=>time()
		);
		if(M("Group_apply")->add($data)){
			$this->success("你已提交伙拼申请信息啦，请耐心等待审核");
		}else{
			$this->error("提交伙拼申请失败");
		}
	}
}
?>