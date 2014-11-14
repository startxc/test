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
		$cartModel = D('Cart');
		$cartArr = $cartModel->getCartList();
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
    
	public function confirmOrder() {$_GET['address_id']=6;
		if (empty($_SESSION['uid'])) {
            $this->redirect('Public/login');
        }
        $cartModel = D('Cart');
        $cartId = S("h_goto_order_".$_SESSION['uid']);
        if (!empty($cartId)) {
        	$cartArr = $cartModel->getCartList($cartId);
        } else {
	    	$cartArr = $cartModel->getCartList();
        }
        if (empty($cartArr)) {
        	$this->redirect('Index/index');
        }
        $addressModel = M('MemberAddress');
		$addressInfo = $addressModel->where(array('id' => $_GET['address_id']))->find();
		if (!$addressInfo) {
			$this->redirect('Index/index');
		}
		$cartList = array();
		foreach ($cartArr['data'] as $key => $cart) {
			$w = date('w', $cart['delivery_time']);
			$w == 0 ? 7 : $w;
			$cartList[$w]['delivery_date'] = date('Y-m-d', $cart['delivery_time']);
			$cartList[$w]['data'][] = $cart;
		}
		$this->assign('total', $cartArr['total']);
		$this->assign('total_goods_qty', $cartArr['total_goods_qty']);
        $this->assign('cartList', $cartList);
        $this->assign('addressInfo', $addressInfo);
        $this->assign('title', '确认订单');
		$this->display();
    }
    
    /**
     * 订单提交完成
     */
    
    public function addOrderSuccess() {
    	$orderId = S('orderId_'.$_SESSION['uid']);
        $orderAmount =  S('orderAmount_'.$_SESSION['uid']);
        if (empty($orderId)) {
            $this->redirect('Index/index');
        }
        $this->assign('orderAmount', $orderAmount);
        $this->display();
    }
}
?>