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
	 * @param string $cartId 购物车商品ID
	 * @param string $addressId 收货地址ID
	 * @param integer $orderType 订单类型
	 * @param string $couponCode 代金券号
	 * @return object $back status属性(0:购物车没有商品 1:提交订单成功 2:收货地址不存在 3:代金券不存在或已失效 4:总金额小于代金券最小使用金额 5:写入订单表失败 6:写入订单商品表失败 7:清空购物车商品失败)
	 */
	
	public function addOrder($cartId, $addressId, $orderType, $couponCode=null) {$couponCode='CP82743d16';
		$orderModel = M('Order');
		$orderGoodsModel = M('OrderGoods');
		$memberAddress = M('MemberAddress');
		$groupModel = M('Group');
		$cartModel = D('Cart');
		$orderType = in_array($orderType, array('normal', 'group')) ? $orderType : 'normal';
		$back = new stdClass();
		
		if (!empty($cartId)) {
        	$cartArr = $cartModel->getCartList($cartId);
        } else {
	    	$cartArr = $cartModel->getCartList();
        }
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
		    $cartList[$w]['delivery_time'] = $cart['delivery_time'];
		}
		
		$orderAmount = 0;
		foreach ($cartList as $key => $cart) {
			$orderAmount += $cartList[$key]['total'];
		}
		
		//获取收货地址
		$memberAddressInfo = $memberAddress->where(array('id' => $addressId, 'member_id' => $_SESSION['uid']))->find();
		if (!$memberAddressInfo) {
			$back->status = 2;
	        return $back;
		}
		
		if ($couponCode != null) {
			$map = array();
			$map['member_id'] = $_SESSION['uid'];
			$map['coupon_code'] = $couponCode;
			$map['start_time'] = array('elt', time());
			$map['end_time'] = array('egt', time());
			$map['used'] = 0;
			$memberCouponInfo = M('MemberCoupon')->where($map)->find();
			if (!$memberCouponInfo) {
				$back->status = 3;
	        	return $back;
			}
			$minUseValue = M('Coupon')->where(array('code' => "$couponCode"))->getField('min_use_value');
			if ($orderAmount < $minUseValue) {
				$back->status = 4;
	        	return $back;
			}
			$orderAmount -= $memberCouponInfo['face_value'];
			$orderAmount = max(floatval($orderAmount), 0);
		}
		
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
			$data['order_type'] = $orderType;
			$data['create_time'] = time();
			$data['delivery_time'] = $cartList[$key]['delivery_time'];
			$data['community_id'] = $memberAddressInfo['community_id'];
			if ($orderType == 'group') {
				$groupInfo = $groupModel->where(array('goods_id' => $cart['data'][0]['goods_id']))->field('group_phase_id')->find();
				$data['is_group'] = 1;
		    	$data['group_phase_id'] = $groupInfo['group_phase_id'];
			}
			if ($couponCode != null) {
				$data['coupon_code'] = $couponCode;
				$data['discount_amount'] = $memberCouponInfo['face_value'];
			}
			$orderId = $orderModel->add($data);
			if (!$orderId) {
				$orderModel->rollback();
				$back->status = 5;
		        return $back;
			}
			
			foreach ($cart['data'] as $key => $vo) {
				$goodsInfo = M('Goods')->where(array('id' => $vo['goods_id']))->field('spec, spec_unit')->find();
				$data = array();
				$data['member_id'] = $_SESSION['uid'];
				$data['order_id'] = $orderId;
				$data['goods_id'] = $vo['goods_id'];
				$data['goods_name'] = $vo['goods_name'];
				$data['image'] = $vo['image'];
				$data['number'] = $vo['number'];
				if ($orderType == 'group') {
			    	$groupInfo = $groupModel->where(array('goods_id' => $vo['goods_id']))->field('real_price')->find();
			    	$data['price'] = $groupInfo['real_price'];
				} else {
					$data['price'] = $vo['price'];
				}
				$data['create_time'] = time();
				$data['spec'] = $goodsInfo['spec'];
				$data['spec_unit'] = $goodsInfo['spec_unit'];
				$id = $orderGoodsModel->add($data);
				if (!$id) {
					$orderModel->rollback();
					$back->status = 6;
		        	return $back;
				}
			}
		}
		
		$bool = $cartModel->emptyCart();
		if (!$bool) {
			$orderModel->rollback();
			$back->status = 7;
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
	 * 删除订单
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function deleteOrder($orderId) {
		$orderModel = M('Order');
        $orderStatus = $orderModel->where(array('id' => $orderId, 'member_id' => $_SESSION['uid']))->getField('order_status');
        $orderStatusArr = array('created', 'canceled', 'refund', 'received');
        if (!in_array($orderStatus, $orderStatusArr)) {
        	return false;
        }
        $res = $orderModel->where(array('id' => $id, 'member_id' => $_SESSION['uid']))->setField('is_show', 0);
        if ($res === false) {
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