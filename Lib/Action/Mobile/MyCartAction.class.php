<?php
/**
 * 购物车
 * @author mike
 */
class MyCartAction extends CommonAction {
    
	public function index(){
		$this->cart();
    }
    
	/**
     * 购物车
     */
    
	public function cart() {
		$cart = A('Api/Cart');
		$cartArr = $cart->getCartList();
		$this->assign('cartArr', $cartArr);
		$this->assign('title', '购物车');
		$this->display('cart');
	}
    
	/**
     * 商品加入购物车
     */
    
    public function addToCart() {
		$cart = A('Api/Cart');
		$back = $cart->addToCart($goodsId, $goodsQty);
		ajax_return($back);
    }
    
	/**
     * 更新购物车商品数量
     */
    
    public function updateCart() {
    	$back = new stdClass();
		$cart = A('Api/Cart');
    	$flag = $cart->updateCart($cartId, $goodsQty);
    	if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	ajax_return($back);
    }
    
	/**
     * 删除购物车商品
     */
    
    public function deleteCart() {
    	$back = new stdClass();
		$cart = A('Api/Cart');
    	$flag = $cart->deleteCart($cartId);
    	if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	ajax_return($back);
    }
    
	/**
     * 确认订单信息
     */
    
	public function confirmOrder(){
		$this->display();
    }
    
    /**
     * 提交订单
     */
    
    public function addOrder() {
    	$back = new stdClass();
		$order = A('Api/Order');
    	$addressId = max(intval($_GET['address_id']), 0);
    	$orderType = max(intval($_GET['order_type']), 0);
    	$buyerNote = $_GET['buyer_note'];
    	$back = $order->addOrder($addressId, $orderType, $buyerNote);
    	ajax_return($back);
    }
}
?>