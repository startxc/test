<?php
/**
 * 订单
 * @author mike
 */
class OrderAction extends CommonAction {
    
	public function index(){
    }
    
	/**
	 * 订单发货
	 */
	
	public function deliverOrder($orderId) {
		$back = new \stdClass();
		$orderApi = A('Api/OrderApi');
		$orderId = $_POST['order_id'];
		$back = $orderApi->deliverOrder($orderId);
		if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
	}
	
	/**
	 * 确认收货
	 */
	
	public function confirmOrder($orderId) {
		$back = new \stdClass();
		$orderApi = A('Api/OrderApi');
		$orderId = $_POST['order_id'];
		$back = $orderApi->confirmOrder($orderId);
		if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
	}
	
	/**
	 * 订单取消
	 */
	
	public function cancelOrder($orderId) {
		$back = new \stdClass();
		$orderApi = A('Api/OrderApi');
		$orderId = $_POST['order_id'];
		$back = $orderApi->cancelOrder($orderId);
		if ($flag) {
    		$back->status = 1;
    	} else {
    		$back->status = 0;
    	}
    	echo $_GET['callback'] . '(' . json_encode($back) .')';
	}
}
?>