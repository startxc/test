<?php
/**
 * 会员中心
 * @author mike
 */
class MemberAction extends CommonAction {
    
	public function _initialize() {
        parent::_initialize();
        //登录判断   
        $actionName = ACTION_NAME;
        $moduleName = MODULE_NAME;
        //不需要登录可以访问的模块方法
        $module_action = array(); 
        if (empty($_SESSION['uid']) && !in_array($moduleName."-".$actionName,$module_action)) {
            $this->redirect('Index/index');
        } else {
            //用户信息
			$memberInfo = M('Member')->where("id={$_SESSION['uid']}")->find();
			$this->assign('score', intval($memberInfo['score']));
    		$this->assign('money', number_format($memberInfo['money'], 2, '.', ''));
        }
    }
	
	public function index(){
		$this->myInfo();
    }
    
	/**
     * 我的
     */
    
    public function myInfo() {
    	$memberCouponModel = M('MemberCoupon');
    	$groupApplyModel = M('GroupApply');
    	$couponNum = intval($memberCouponModel->where(array('member_id' => $_SESSION['uid']))->count('id'));
    	$groupNum = intval($groupApplyModel->where(array('member_id' => $_SESSION['uid']))->count('id'));
    	$this->assign('couponNum', $couponNum);
    	$this->assign('groupNum', $groupNum);
    	$this->assign('title', '我的');
    	$this->display('myInfo');
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
    	if ($memberCouponInfo['used'] == 1) {
    		$memberModel = M('Member');
    		$orderModel = M('Order');
    		$mobile = $memberModel->where(array('id' => $_SESSION['uid']))->getField('mobile');
    		$orderNo = $orderModel->where(array('coupon_code' => $memberCouponInfo['coupon_code']))->getField('order_no');
    		$this->assign('mobile', $mobile);
    		$this->assign('order_no', $orderNo);
    	}
    	$this->assign('memberCouponInfo', $memberCouponInfo);
    	$this->assign('title', '代金券详情');
		$this->display();
    }
}
?>