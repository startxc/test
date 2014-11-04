<?php
/**
 * @author mike
 */
class CartApiAction extends ApiCommonAction {
	
	/**
     * 加入购物车
     */
    
    public function addToCart($goodsId, $goodsQty) {
    	$cartModel = D('Cart');
		$back = $cartModel->addToCart($goodsId, $goodsQty);
		return $back;
    }
    
    /**
     * 获取购物车商品
     */
    
    public function getCartList() {
    	$cartModel = D('Cart');
		$cartArr = $cartModel->getCartList();
		return $cartArr;
    }
    
    /**
     * 更新购物车商品数量
     */
    
    public function updateCart($cartId, $goodsQty) {
    	$cartModel = D('Cart');
		$flag = $cartModel->updateCart($cartId, $goodsQty);
		return $flag;
    }
    
    /**
     * 删除购物车商品
     */
    
    public function deleteCart($cartId) {
	    $cartModel = D('Cart');
	    $flag = $cartModel->deleteCart($cartId);
	    return $flag;
    }
}