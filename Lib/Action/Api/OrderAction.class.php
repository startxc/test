<?php
/**
 * @author mike
 */
class OrderAction extends MobileCommonAction {
	
	
	/**
	 * 提交订单
	 */
	
	public function addOrder($addressId, $communityId, $orderType, $buyerNote) {
		$orderModel = D('Order');
		$addressId = max(intval($_POST['address_id']), 0);
		$communityId = max(intval($_POST['community_id']), 0);
    	$orderType = $_POST['order_type'];
    	$buyerNote = $_POST['buyer_note'];
		$back = $orderModel->addOrder($addressId, $communityId, $orderType, $buyerNote);
		if ($back->status == 0) {
			$this->error("购物车没有商品");
		} elseif ($back->status == 1) {
			$this->success("提交订单成功");
		} elseif ($back->status == 2) {
			$this->error("收货地址不存在");
		} elseif ($back->status == 3) {
			$this->error("写入订单表失败");
		} elseif ($back->status == 4) {
			$this->error("写入订单商品表失败");
		} elseif ($back->status == 5) {
			$this->error("清空购物车商品失败");
		}
	}
	
	/**
	 * 删除订单
	 */
	
	public function deleteOrder() {
		$orderModel = M('Order');
		$id = max(intval($_POST['id']), 0);
        $orderStatus = $orderModel->where(array('id' => $id, 'member_id' => $_SESSION['uid']))->getField('order_status');
        $orderStatusArr = array('created', 'canceled', 'refund', 'received');
        if (!in_array($orderStatus, $orderStatusArr)) {
        	$this->error("亲,对不起,系统出现错误啦");
        }
        $res = $orderModel->where(array('id' => $id, 'member_id' => $_SESSION['uid']))->setField('is_show', 0);
        if ($res === false) {
            $this->error("亲,对不起,系统出现错误啦");
        }
        $this->success("操作成功");
    }
    
	/**
	 * 确认收货
	 */
	
	public function confirmOrder() {
		$goodsModel = M('Goods');
		$orderModel = M('Order');
		$orderGoodsModel = M('OrderGoods');
		$orderId = max(intval($_POST['id']), 0);
		
    	$orderInfo = $orderModel->where(array('id' => $orderId))->find();
    	if (!$orderInfo) {
    		$this->error("亲,对不起,系统出现错误啦");
    	}
		if ($orderInfo['order_status'] != 'shipped') {
    		$this->error("亲,对不起,系统出现错误啦");
    	}
		$id = $orderModel->where(array('id' => $orderId))->save(array('order_status' => 'received', 'confirm_time' => time()));
    	if ($id === false) {
    		$this->error("亲,对不起,系统出现错误啦");
    	}
    	$orderGoodsList = $orderGoodsModel->where(array('order_id' => $orderId))->field('goods_id, number')->select();
    	foreach ($orderGoodsList as $key => $orderGoods) {
    		//更新商品销量
    		$goodsModel->where(array('id' => $orderGoods['goods_id']))->setInc('sales_count', $orderGoods['number']);
    	}
    	$this->success("操作成功");
	}
	
	/**
	 * 根据订单ID获取订单
	 */
	
	public function getOrderById() {
		$orderModel = D('Order');
		$id = max(intval($_GET['id']), 0);
		$orderInfo = $orderModel->where(array('id' => $id))->find();
		$this->ajaxRespon($orderInfo);
		return $orderInfo;
	}
	
	/**
	 * 根据订单编号获取订单
	 */
	
	public function getOrderByOrderNo() {
		$orderModel = D('Order');
		$orderNo = $_GET['order_no'];
		$orderInfo = $orderModel->where(array('order_no' => $orderNo))->find();
		$this->ajaxRespon($orderInfo);
		return $orderInfo;
	}
	
	/**
	 * 获取用户所有订单
	 */
	
	public function getOrderList() {
		$orderModel = M('Order');
    	$orderGoodsModel = M('OrderGoods');
		$orderType = in_array($_GET['order_type'], array('normal', 'group')) ? $_GET['order_type'] : '';
		$orderStatus = in_array($_GET['order_status'], array('created', 'payed', 'shipped')) ? $_GET['order_status'] : '';
		
		$map = array();
		$map['member_id'] = $_SESSION['uid'];
		$map['is_show'] = 1;
		if (!empty($orderType)) {
			$map['order_type'] = $orderType;
		}
    	if (!empty($orderStatus)) {
			$map['order_status'] = $orderStatus;
		}
		$count = $orderModel->where($map)->count('id');
    	if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, 10);
            $page = $p->show();
            $orderList = $orderModel->where($map)->limit($p->firstRow . ',' . $p->listRows)->order('create_time desc')->select();
	    	foreach ($orderList as $key => $order) {
	    		$orderList[$key]['goodsList'] = $orderGoodsModel->where(array('order_id' => $order['id']))->select();
	    	}
        }
        $this->ajaxRespon($orderList);
        $this->assign('order_type', $orderType);
        $this->assign('order_status', $orderStatus);
        $this->assign('page', $page);
		return $orderList;
	}
	
	/**
     * 获取用户订单各状态统计
     */
    
    public function getOrderStatusCount() {
    	$orderModel = M('Order');
    	$orderList = $orderModel->where(array('member_id' => $_SESSION['uid'], 'is_show' => '1'))->field('id, order_status')->select();
    	foreach ($orderList as $key => $order) {
    		if ($order['order_status'] == 'created') {
    			$createdArr[] = $order;
    		} elseif ($order['order_status'] == 'payed') {
    			$payedArr[] = $order;
    		} elseif ($order['order_status'] == 'shipped') {
    			$shippedArr[] = $order;
    		}
    	}
    	$orderStatusCount = array(
	    	'order_num' => count($orderList),
	    	'created_num' => count($createdArr),
	    	'payed_num' => count($payedArr),
	    	'shipped_num' => count($shippedArr)
    	);
    	$this->ajaxRespon($orderStatusCount);
		ajax_return($orderStatusCount);
    }
}