<?php
/**
 * 购物车模型文件
 */
class OrderModel extends CommonModel {
	
    public function __construct() {
        parent::__construct();
    }
    
	/**
	 * 提交订单
	 * @param string $addressId 收货地址ID
	 * @param integer $orderType 订单类型
	 * @param string $buyerNote 买家备注
	 * @return object $back status属性(0:购物车没有商品 1:提交订单成功 2:写入订单表失败 3:写入订单商品表失败 4:清空购物车商品失败)
	 */
	
	public function addOrder($addressId, $orderType, $buyerNote) {
		$orderModel = M('Order');
		$orderGoodsModel = M('OrderGoods');
		$memberAddress = M('MemberAddress');
		$cartModel = D('Cart');
		$back = new \stdClass();
		
		$cartArr = $cartModel->getCartList();
		if (empty($cartArr)) {
			$back->status = 0;
	        return $back;
		}
		$goodsAmount = $cartArr['total'];
		$orderAmount = $goodsAmount;
		
		//获取收货地址
		$memberAddressInfo = $memberAddress->where(array('uid' => $_SESSION['uid'], 'is_default' => 1))->find();
		
		$orderModel->startTrans();
		$data = array();
		$data['order_no'] = $this->createOrderNo();
		$data['member_id'] = $_SESSION['uid'];
		$data['order_status'] = 'created';
		$data['consignee'] = $memberAddressInfo['consignee'];
		$data['province_id'] = $memberAddressInfo['province_id'];
		$data['city_id'] = $memberAddressInfo['city_id'];
		$data['area_id'] = $memberAddressInfo['area_id'];
		$data['address'] = $memberAddressInfo['address'];
		$data['tel'] = $memberAddressInfo['tel'];
		$data['mobile'] = $memberAddressInfo['mobile'];
		$data['goods_amount'] = $goodsAmount;
		$data['order_amount'] = $orderAmount;
		$data['buyer_note'] = $buyerNote;
		$data['order_type'] = $orderType;
		$data['create_time'] = time();
		$orderId = $orderModel->add($data);
		if (!$orderId) {
			$orderModel->rollback();
			$back->status = 2;
	        return $back;
		}
		
		foreach ($cartArr['data'] as $key => $cart) {
			$data = array();
			$data['member_id'] = $_SESSION['uid'];
			$data['order_id'] = $orderId;
			$data['goods_id'] = $cart['goods_id'];
			$data['goods_name'] = $cart['goods_name'];
			$data['image'] = $cart['image'];
			$data['number'] = $cart['number'];
			$data['price'] = $cart['price'];
			$data['create_time'] = $cart['create_time'];
			$id = $orderGoodsModel->add($data);
			if (!$id) {
				$orderModel->rollback();
				$back->status = 3;
	        	return $back;
			}
		}
		
		$bool = $cartModel->emptyCart();
		if (!$bool) {
			$orderModel->rollback();
			$back->status = 4;
	        return $back;
		}
		
		$orderModel->commit();
		S('orderId_'.$_SESSION['uid'], $orderId, C('DATA_CACHE_TIME'));
		S('orderAmount_'.$_SESSION['uid'], $orderAmount, C('DATA_CACHE_TIME'));
		$back->status = 1;
	    return $back;
	}
	
	/**
	 * 订单发货
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function deliverOrder($orderId) {
		$orderModel = M('Order');
		
    	$orderInfo = $orderModel->where(array('id' => $orderId))->find();
    	if (!$orderInfo) {
    		return false;
    	}
    	if ($orderInfo['order_status'] != 'created') {
    		return false;
    	}
		
		$id = $orderModel->where(array('id' => $orderId))->save(array('order_status' => 'shipped', 'shippint_time' => time()));
    	if ($id === false) {
    		return false;
    	}
    	return true;
	}

	/**
	 * 确认收货
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function confirmOrder($orderId) {
		$goodsModel = M('Goods');
		$orderModel = M('Order');
		$orderGoodsModel = M('OrderGoods');
		
    	$orderInfo = $orderModel->where(array('id' => $orderId))->find();
    	if (!$orderInfo) {
    		return false;
    	}
		if ($orderInfo['order_status'] != 'created') {
    		return false;
    	}
		
		$id = $orderModel->where(array('id' => $orderId))->save(array('order_status' => 'received', 'confirm_time' => time()));
    	if ($id === false) {
    		return false;
    	}
    	
    	$orderGoodsList = $orderGoodsModel->where(array('order_id' => $orderId))->field('goods_id, number')->select();
    	foreach ($orderGoodsList as $key => $orderGoods) {
    		//更新商品销量
    		$id = $goodsModel->where(array('id' => $orderGoods['goods_id']))->setInc('sales_count', $orderGoods['number']);
	    	if ($id === false) {
	    		return false;
	    	}
    	}
    	return true;
	}
	
	/**
	 * 订单取消
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function cancelOrder($orderId) {
		$orderModel = M('order');
		
    	$orderInfo = $orderModel->where(array('id' => $orderId))->find();
    	if (!$orderInfo) {
    		return false;
    	}
    	if ($orderInfo['order_status'] != 'created') {
    		return false;
    	}
		
		$id = $orderModel->where(array('id' => $orderId))->save(array('order_status' => 'canceled'));
    	if ($id === false) {
    		return false;
    	}
    	return true;
	}
	
	/**
	 * 创建订单号
	 * @return integer
	 */
	
	private function createOrderNo() {
		//订单号规则：店铺ID+年的后2位+月+日+订单数
		$count = M('order')->count();
		$orderNo = I('session.shopId') . substr(date('Ymd', time()), 2) . $count;
		return $orderNo;
	}
}
?>