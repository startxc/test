<?php
/**
 * 会员中心
 * @author mike
 */
class MemberAction extends CommonAction {
    
	public function index(){
		$this->myInfo();
    }
    
	/**
     * 所有订单/伙拼/普通
     */
    
    public function myOrder() {
    	$order = A('Api/Order');
    	$orderList = $order->getOrderList();
    	$this->assign('orderList', $orderList);
    	$this->assign('title', '所有订单');
		$this->display();
    }
    
	/**
	 * 删除订单
	 */
	
	public function deleteOrder() {
		$back = new stdClass();
		$order = A('Api/Order');
		$back = $order->deleteOrder();
    	ajax_return($back);
	}
	
	/**
	 * 确认收货
	 */
	
	public function confirmOrder() {
		$back = new stdClass();
		$order = A('Api/Order');
		$back = $order->confirmOrder();
    	ajax_return($back);
	}
	
	/**
     * 获取订单统计
     */
    
    public function getOrderStatusCount() {
    	$order = A('Api/Order');
    	$order->getOrderStatusCount();
    }
	
	/**
     * 我的代金券
     */
    
    public function myCoupon() {
    	$coupon = A('Api/Coupon');
    	$memberCouponList = $coupon->getCouponList();
    	$this->assign('memberCouponList', $memberCouponList);
    	$this->assign('title', '我的代金券');
		$this->display();
    }
    
	/**
     * 添加代金券
     */
    
    public function addCoupon() {
		$coupon = A('Api/Coupon');
		$back = $coupon->addCoupon();
    	ajax_return($back);
    }
    
	/**
     * 代金券详情
     */
    
    public function couponInfo() {
    	$coupon = A('Api/Coupon');
    	$memberCouponInfo = $coupon->getCouponById();
    	$this->assign('memberCouponInfo', $memberCouponInfo);
    	$this->assign('title', '代金券详情');
		$this->display();
    }
	
	/**
     * 获取代金券统计
     */
    
    public function getCouponStatusCount() {
    	$coupon = A('Api/Coupon');
    	$coupon->getCouponStatusCount();
    }
    
    /**
     * 我的
     */
    
    public function myInfo() {
    	$memberModel = M('Member');
    	$memberCouponModel = M('MemberCoupon');
    	$groupApplyModel = M('GroupApply');
    	
    	$memberInfo = $memberModel->where(array('id' => $_SESSION['uid']))->field('score, money')->find();
    	$couponNum = $memberCouponModel->where(array('id' => $_SESSION['uid']))->count('id');
    	$groupNum = $groupApplyModel->where(array('id' => $_SESSION['uid']))->count('id');
    	
    	$this->assign('score', intval($memberInfo['score']));
    	$this->assign('money', number_format($memberInfo['money'], 2, '.', ''));
    	$this->assign('couponNum', $couponNum);
    	$this->assign('groupNum', $groupNum);
    	$this->assign('title', '我的');
    	$this->display('myInfo');
    }
}
?>