<?php
/**
 * @author mike
 */
class OrderAction extends MobileCommonAction {
	
	
	/**
	 * 提交订单
	 */
	
	public function addOrder($addressId, $orderType, $buyerNote) {
		$orderModel = D('Order');
		$addressId = max(intval($_POST['address_id']), 0);
    	$orderType = $_POST['order_type'];
    	$buyerNote = $_POST['buyer_note'];
		$back = $orderModel->addOrder($addressId, $orderType, $buyerNote);
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
		$orderModel = D('Order');
		$orderId = max(intval($_POST['id']), 0);
		$flag = $orderModel->deleteOrder($orderId);
		if (!$flag) {
			$this->error("亲,对不起,系统出现错误啦");
		}
		$this->success("操作成功");
    }
    
	/**
	 * 确认收货
	 */
	
	public function confirmOrder() {
		$orderModel = D('Order');
		$orderId = max(intval($_POST['id']), 0);
		$flag = $orderModel->confirmOrder($orderId);
		if (!$flag) {
			$this->error("亲,对不起,系统出现错误啦");
		}
		$this->success("操作成功");
	}
	
	/**
	 * 订单取消
	 */
	
	public function cancelOrder() {
		$orderModel = D('Order');
		$orderId = max(intval($_POST['id']), 0);
		$flag = $orderModel->cancelOrder($orderId);
		if (!$flag) {
			$this->error("亲,对不起,系统出现错误啦");
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
	}
	
	/**
	 * 根据订单编号获取订单
	 */
	
	public function getOrderByOrderNo() {
		$orderModel = D('Order');
		$orderNo = $_GET['order_no'];
		$orderInfo = $orderModel->where(array('order_no' => $orderNo))->find();
		$this->ajaxRespon($orderInfo);
	}
	
	/**
	 * 获取用户所有订单
	 */
	
	public function getOrderList() {
		$orderModel = M('Order');
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
	    		$orderList[$key]['province_name'] = M('Region')->where(array('id' => $order['province_id']))->getField('name');
				$orderList[$key]['city_name'] = M('Region')->where(array('id' => $order['city_id']))->getField('name');
				$orderList[$key]['area_name'] = M('Region')->where(array('id' => $order['area_id']))->getField('name');
	    		$orderList[$key]['goodsList'] = M('OrderGoods')->where(array('order_id' => $order['id']))->select();
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
    }
}