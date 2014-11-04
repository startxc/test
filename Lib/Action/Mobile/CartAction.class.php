<?php
/**
 * 购物车
 * @author mike
 */
class CartAction extends CommonAction {
    
public function index(){
		$this->cart();
    }
    
    /**
     * 购物车
     */
    
	public function cart() {
		$back = new \stdClass();
		$cartApi = A('Api/CartApi');
		$cartArr = $cartApi->getCartList();
		$back->list = $cartArr;
		echo $_GET['callback'] . '(' . json_encode($back) .')';
	}
    
    /**
     * 加入购物车
     */
    
    public function addToCart() {
    	$back = new \stdClass();
		$cartApi = A('Api/CartApi');
		$goodsId = $_POST['goods_id'];
		$goodsQty = $_POST['goods_qty'];
		$back = $cartApi->addToCart($goodsId, $goodsQty);
		echo $_GET['callback'] . '(' . json_encode($back) .')';
    }
    
    /**
     * 更新购物车商品数量
     */
    
    public function updateCart() {
    	$back = new \stdClass();
		$cartApi = A('Api/CartApi');
    	$cartId = max(intval($_GET['cart_id']), 0);
    	$goodsQty = max(intval($_GET['goods_qty']), 0);
    	$flag = $cartApi->updateCart($cartId, $goodsQty);
    	if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
    }
    
    /**
     * 删除购物车商品
     */
    
    public function deleteCart() {
    	$back = new \stdClass();
		$cartApi = A('Api/CartApi');
    	$cartId = max(intval($_GET['cart_id']), 0);
    	$flag = $cartApi->deleteCart($cartId);
    	if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
    }
    
	/**
     * 确认订单信息
     */
    
	public function confirmOrder(){
		
    }
    
    /**
     * 提交订单
     */
    
    public function addOrder() {
    	$back = new \stdClass();
		$orderApi = A('Api/OrderApi');
    	$addressId = max(intval($_GET['address_id']), 0);
    	$orderType = max(intval($_GET['order_type']), 0);
    	$buyerNote = $_GET['buyer_note'];
    	$back = $orderApi->addOrder($addressId, $orderType, $buyerNote);
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
    }
}
?>