<?php
/**
 * @author mike
 */
class OrderApiAction extends ApiCommonAction {
	
	/**
	 * 提交订单
	 */
	
	public function addOrder($addressId, $orderType, $buyerNote) {
		$orderModel = D('Order');
		$back = $orderModel->addOrder($addressId, $orderType, $buyerNote);
		return $back;
	}
	
	/**
	 * 订单发货
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function deliverOrder($orderId) {
		$orderModel = D('Order');
		$flag = $orderModel->deliverOrder($orderId);
		return $flag;
	}

	/**
	 * 确认收货
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function confirmOrder($orderId) {
		$orderModel = D('Order');
		$flag = $orderModel->confirmOrder($orderId);
		return $flag;
	}
	
	/**
	 * 订单取消
	 * @param integer $orderId 订单ID
	 * @return boolean
	 */
	
	public function cancelOrder($orderId) {
		$orderModel = D('Order');
		$flag = $orderModel->cancelOrder($orderId);
		return $flag;
	}
}