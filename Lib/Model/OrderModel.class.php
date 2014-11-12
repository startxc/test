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
	 * @return object $back status属性(0:购物车没有商品 1:提交订单成功 2:收货地址不存在 3:写入订单表失败 4:写入订单商品表失败 5:清空购物车商品失败)
	 */
	
	public function addOrder($addressId, $communityId, $orderType, $buyerNote) {
		$orderModel = M('Order');
		$orderGoodsModel = M('OrderGoods');
		$memberAddress = M('MemberAddress');
		$cartModel = D('Cart');
		$orderType = in_array($orderType, array('normal', 'group')) ? $orderType : 'normal';
		$back = new stdClass();
		
		$cartArr = $cartModel->getCartList();
		if (empty($cartArr)) {
			$back->status = 0;
	        return $back;
		}
		
		$cartList = array();
		foreach ($cartArr['data'] as $key => $cart) {
			$w = date('w', $cart['delivery_time']);
			$w == 0 ? 7 : $w;
			$cartList[$w]['data'][] = $cart;
			$cartList[$w]['subtotal'] = $cart['number'] * $cart['price'];
		    $cartList[$w]['total'] += $cartList[$w]['subtotal'];
		}
		
		//获取收货地址
		$memberAddressInfo = $memberAddress->where(array('id' => $addressId, 'member_id' => $_SESSION['uid']))->find();
		if (!$memberAddressInfo) {
			$back->status = 2;
	        return $back;
		}
		
		$orderAmount = 0;
		$orderModel->startTrans();
		foreach ($cartList as $key => $cart) {
			if (count($cartList) > 1) {
				$combinePayNo = 'u'.$this->createOrderNo();
			}
			$data = array();
			if (!empty($combinePayNo)) {
				$data['combine_pay_no'] = $combinePayNo;
			}
			$data['order_no'] = $this->createOrderNo();
			$data['member_id'] = $_SESSION['uid'];
			$data['order_status'] = 'created';
			$data['consignee'] = $memberAddressInfo['consignee'];
			$data['province_id'] = $memberAddressInfo['province_id'];
			$data['city_id'] = $memberAddressInfo['city_id'];
			$data['area_id'] = $memberAddressInfo['area_id'];
			$data['address'] = $memberAddressInfo['address'];
			$data['mobile'] = $memberAddressInfo['mobile'];
			$data['goods_amount'] = $cartList[$key]['total'];
			$data['order_amount'] = $cartList[$key]['total'];
			if (!empty($buyerNote)) {
				$data['buyer_note'] = $buyerNote;
			}
			$data['order_type'] = $orderType;
			$data['create_time'] = time();
			$data['community_id'] = $communityId;
			$orderId = $orderModel->add($data);
			if (!$orderId) {
				$orderModel->rollback();
				$back->status = 3;
		        return $back;
			}
			
			foreach ($cart['data'] as $key => $vo) {
				$data = array();
				$data['member_id'] = $_SESSION['uid'];
				$data['order_id'] = $orderId;
				$data['goods_id'] = $vo['goods_id'];
				$data['goods_name'] = $vo['goods_name'];
				$data['image'] = $vo['image'];
				$data['number'] = $vo['number'];
				$data['price'] = $vo['price'];
				$data['create_time'] = time();
				$id = $orderGoodsModel->add($data);
				if (!$id) {
					$orderModel->rollback();
					$back->status = 4;
		        	return $back;
				}
			}
			
			$orderAmount += $data['order_amount'];
		}
		
		$bool = $cartModel->emptyCart();
		if (!$bool) {
			$orderModel->rollback();
			$back->status = 5;
	        return $back;
		}
		
		if (!empty($combinePayNo)) {
			S('orderId_'.$_SESSION['uid'], $combinePayNo, C('DATA_CACHE_TIME'));
		} else {
			S('orderId_'.$_SESSION['uid'], $orderId, C('DATA_CACHE_TIME'));
		}
		S('orderAmount_'.$_SESSION['uid'], $orderAmount, C('DATA_CACHE_TIME'));
		$orderModel->commit();
		$back->status = 1;
	    return $back;
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
		if ($orderInfo['order_status'] != 'shipped') {
    		return false;
    	}
		$id = $orderModel->where(array('id' => $orderId))->save(array('order_status' => 'received', 'confirm_time' => time()));
    	if ($id === false) {
    		return false;
    	}
    	$orderGoodsList = $orderGoodsModel->where(array('order_id' => $orderId))->field('goods_id, number')->select();
    	foreach ($orderGoodsList as $key => $orderGoods) {
    		//更新商品销量
    		$goodsModel->where(array('id' => $orderGoods['goods_id']))->setInc('sales_count', $orderGoods['number']);
    	}
    	return true;
	}
	
	/**
	 * 订单取消
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function cancelOrder($orderId) {
		$orderModel = M('Order');
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
		$date = date('ymd');
        list($s1, $s2) = explode(' ', microtime());
        $secs = str_pad(intval($s2)%86400, 5, "0", STR_PAD_LEFT);       
        $msec = str_pad(intval($s1*1000), 3, "0", STR_PAD_LEFT); 
        $number = $date.$secs.$msec;
        return $number; 
	}
}
?>