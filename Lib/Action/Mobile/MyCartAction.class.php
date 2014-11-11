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
     * 去结算
     */
    
    public function goToOrder() {
    	$back = new stdClass();
        if (empty($_SESSION['uid'])) {
            $back->status = 0;
            ajax_return($back);
        }
        $cartId = $_POST['cart_id'];
        $cartId = implode(',', $cartId);
        S("h_goto_order_".$_SESSION['uid'], $cartId, C('DATA_CACHE_TIME'));
        $back->status = 1;
        $back->cartId = S("h_goto_order_".$_SESSION['uid']);
        ajax_return($back);
    }
    
	/**
     * 确认订单信息
     */
    
	public function confirmOrder() {
		if (empty($_SESSION['uid'])) {
            $this->redirect('Public/login');
        }
        $cartModel = D('Cart');
        $cartId = S("h_goto_order_".$_SESSION['uid']);
        if (!empty($cartId)) {
        	$cartList = $cartModel->getCartList($cartId);
        } else {
	    	$cartList = $cartModel->getCartList();
        }
        print_r($cartList);
        $this->assign('cartList', $cartList);
		$this->display();
    }
    
    /**
     * 提交订单
     */
    
    public function addOrder() {
    	$back = new stdClass();
		$order = A('Api/Order');
    	$back = $order->addOrder();
    	ajax_return($back);
    }
}
?>