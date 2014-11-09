<?php
/**
 * 会员中心
 * @author mike
 */
class MemberAction extends CommonAction {
    
    private $memberInfo;

	public function _initialize() {
        parent::_initialize();
        //登录判断   
        $actionName = ACTION_NAME;
        $moduleName = MODULE_NAME;
        //不需要登录可以访问的模块方法
        $module_action = array(); 
        if (empty($_SESSION['uid']) && !in_array($moduleName."-".$actionName,$module_action)) {
            $this->redirect('Public/login');
        } else {
            //用户信息
			$this->memberInfo = $memberInfo = M('Member')->where("id={$_SESSION['uid']}")->find();
            $this->assign("memberinfo",$memberInfo);
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
        //普通用户
        if($this->memberInfo['member_type'] == "normal"){
        	$memberCouponModel = M('MemberCoupon');
        	$groupApplyModel = M('GroupApply');
        	$couponNum = intval($memberCouponModel->where(array('member_id' => $_SESSION['uid']))->count('id'));
        	$groupNum = intval($groupApplyModel->where(array('member_id' => $_SESSION['uid']))->count('id'));
        	$this->assign('couponNum', $couponNum);
        	$this->assign('groupNum', $groupNum);
        	$this->assign('title', '我的');
        	$this->display('myInfo');
        }else{ //业务员

            $this->display("salemanInfo");
        }
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

    //编辑信息页面
    public function editInfo(){
        $this->display();
    }

    //充值页面
    public function recharge(){
        $this->display();
    }

    //设置登录密码页面
    public function setPassword(){
        $this->display();
    }

    //设置支付密码页面
    public function setPayPassword(){
        $this->display();
    }

    //修改昵称页面
    public function setNickname(){
        $this->display();
    }

    //修改手机号码页面
    public function setMobile(){
        $this->display();
    }

    //修改绑定会员
    public function setBindMember(){
        $this->display();
    }

    //修改收货地址页面
    public function setAddress(){
        $uid = $_SESSION['uid'];
        $address = D("Member")->getAddress($uid);
        $this->assign("address",$address);
        $this->display();
    }
}
?>