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
		foreach ($cartArr['data'] as $key => $cart) {
			$date = date("Ymd", strtotime("+1 day"));
			$deliveryTime = date("Ymd", $cart['delivery_time']);
			if ($deliveryTime != $date) {
				$cartArr['total'] -= $cartArr['data'][$key]['price'] * $cartArr['data'][$key]['number'];
				$cartArr['total_goods_qty'] -= $cartArr['data'][$key]['number'];
				unset($cartArr['data'][$key]);
			}
		}
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
     * 选择收货地址
     */
    
    public function chooseAddress() {
    	$uid = $_SESSION['uid'];
        $address = D("Member")->getAddress($uid);
        if (!$address) {
        	$this->redirect('Member/addAddress', array('jumpurl' => base64_encode(U('Mobile/MyCart/chooseAddress'))));
        }
        $this->assign("address",$address);
    	$this->assign('title', '选择收货地址');
    	$this->display();
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
        	$cartArr = $cartModel->getCartList($cartId);
        } else {
	    	$cartArr = $cartModel->getCartList();
        }
        if (empty($cartArr)) {
        	$this->redirect('Index/index');
        }
        $addressModel = M('MemberAddress');
		$addressInfo = $addressModel->where(array('member_id' => $_SESSION['uid'], 'id' => $_GET['address_id']))->find();
		if (!$addressInfo) {
			$this->redirect('MyCart/chooseAddress');
		}
		$cartList = array();
		foreach ($cartArr['data'] as $key => $cart) {
			$w = date('w', $cart['delivery_time']);
			$w == 0 ? 7 : $w;
			$cartList[$w]['delivery_date'] = date('Y-m-d', $cart['delivery_time']);
			$cartList[$w]['data'][] = $cart;
		}
		$this->assign('cartId', $cartId);
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
    	if (substr($orderId, 0, 1) == 'u') {
            $this->assign('orderNo', $orderId);
        } else {
            $orderModel = M('Order');
            $orderNo = $orderModel->where("member_id = '{$_SESSION['uid']}' AND id = '$orderId'")->getField('order_no');
            $this->assign('orderNo', $orderNo);           
        }
        $this->assign('orderAmount', $orderAmount);
        $this->display();
    }
}
?>