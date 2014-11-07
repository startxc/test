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
		$back = $cartModel->addToCart($goodsId, $goodsQty);
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
		return $back;
    }
    
	/**
     * 获取购物车商品
     */
    
    public function getCartList() {
    	$cartModel = D('Cart');
		$cartArr = $cartModel->getCartList();
		$this->ajaxRespon($cartArr);
		return $cartArr;
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
    		$this->success("更新购物车失败");
    	}
		return $flag;
    }
    
	/**
     * 删除购物车商品
     */
    
    public function deleteCart($cartId) {
	    $cartModel = D('Cart');
	    $cartId = max(intval($_POST['cart_id']), 0);
	    $flag = $cartModel->deleteCart($cartId);
	    if ($flag) {
    		$this->success("操作成功");
    	} else {
    		$this->success("删除购物车商品失败");
    	}
		return $flag;
    }
}