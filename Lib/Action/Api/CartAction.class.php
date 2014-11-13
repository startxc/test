<?php
/**
 * @author mike
 */
class CartAction extends MobileCommonAction {
	
	/**
     * 商品加入购物车
     */
    
    public function addToCart() {
    	$cartModel = D('Cart');
    	$goodsId = max(intval($_POST['goods_id']), 0);
		$goodsQty = max(intval($_POST['goods_qty']), 0);
		$deliveryTime = $_POST['delivery_time'];
		$isGroup = $_POST['is_group'];
    	if (!empty($deliveryTime)) {
    		$deliveryTime = strtotime($deliveryTime);
    	} else {
    		$deliveryTime =  strtotime(date('Ymd', strtotime('+1 day')));
    	}
		$back = $cartModel->addToCart($goodsId, $goodsQty, $deliveryTime, $isGroup);
		if ($back->status == 0) {
			$this->error("商品不存在");
		} elseif ($back->status == 1) {
			$this->success("操作成功");
		} elseif ($back->status == 2) {
			$this->error("商品数量有误");
		} elseif ($back->status == 3) {
			$this->error("更新商品数量失败");
		} elseif ($back->status == 4) {
			$this->error("加入购物车失败");
		}
    }
    
    /**
     * 商品批量加入购物车
     */
    
    public function batchAddToCart() {
    	$model = M();
    	$cartModel = D('Cart');
    	$back = new stdClass();
    	$goodsIdArr = explode(',', trim($_POST['goods_id'], ','));
    	$goodsQtyArr = explode(',', trim($_POST['goods_qty'], ','));
    	$deliveryTimeArr = explode(',', trim($_POST['delivery_time'], ','));
    	$isGroupArr = explode(',', trim($_POST['is_group'], ','));
    	
    	if (empty($goodsIdArr) || empty($goodsQtyArr) || (count($goodsIdArr) != count($goodsQtyArr))) {
    		$this->error("参数错误");
    		$back->status = 0;
    		$back->prompt = '参数错误';
    		return $back;
    	}
    	
    	$model->startTrans();
    	foreach ($goodsIdArr as $key => $goodsId) {
    		if (!empty($deliveryTimeArr[$key])) {
    			$deliveryTime = strtotime($deliveryTimeArr[$key]);
    		} else {
	    		$deliveryTime =  strtotime(date('Ymd', strtotime('+1 day')));
	    	}
    		$back = $cartModel->addToCart($goodsId, $goodsQtyArr[$key], $deliveryTime, $isGroup);
    		if ($back->status != 1) {
    			$model->rollback();
    			if ($back->status == 0) {
					$this->error("商品不存在");
				}  elseif ($back->status == 2) {
					$this->error("商品数量有误");
				} elseif ($back->status == 3) {
					$this->error("更新商品数量失败");
				} elseif ($back->status == 4) {
					$this->error("加入购物车失败");
				}
				return $back;
    		}
    	}
    	
    	$model->commit();
    	$this->success("操作成功");
    }
    
	/**
     * 获取购物车商品
     */
    
    public function getCartList() {
    	$cartModel = D('Cart');
		$cartArr = $cartModel->getCartList();
		$this->ajaxRespon($cartArr);
    }
    
	/**
     * 更新购物车商品数量
     */
    
    public function updateCart() {
		$cartModel = D('Cart');
		$cartId = max(intval($_POST['cart_id']), 0);
    	$goodsQty = max(intval($_POST['goods_qty']), 0);
		$flag = $cartModel->updateCart($cartId, $goodsQty);
    	if ($flag) {
    		$this->success("操作成功");
    	} else {
    		$this->error("更新购物车失败");
    	}
    }
    
	/**
     * 删除购物车商品
     */
    
    public function deleteCart() {
	    $cartModel = D('Cart');
	    $cartId = max(intval($_POST['cart_id']), 0);
	    $flag = $cartModel->deleteCart($cartId);
	    if ($flag) {
    		$this->success("操作成功");
    	} else {
    		$this->error("删除购物车商品失败");
    	}
    }
    
	/**
     * 清空购物车商品
     */
    
    public function emptyCart() {
	    $cartModel = D('Cart');
	    $flag = $cartModel->emptyCart();
	    if ($flag) {
    		$this->success("操作成功");
    	} else {
    		$this->error("删除购物车商品失败");
    	}
    }
}