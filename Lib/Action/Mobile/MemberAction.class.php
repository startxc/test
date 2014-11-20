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
        	$this->assign('title', '个人主页');
        	$this->display('myInfo');
        }else{ //业务员
        	$memberModel = M('Member');
			$orderModel = M('Order');
			$communityId = $memberModel->where(array('id' => $_SESSION['uid']))->getField('community_id');
			$orderList = $orderModel->where(array('community_id' => $communityId))->select();
			foreach ($orderList as $key => $order) {
				$orderList[$key]['province_name'] = M('Region')->where(array('id' => $order['province_id']))->getField('name');
				$orderList[$key]['city_name'] = M('Region')->where(array('id' => $order['city_id']))->getField('name');
				$orderList[$key]['area_name'] = M('Region')->where(array('id' => $order['area_id']))->getField('name');
				$names = '';
				$goodsNames = M('OrderGoods')->where(array('order_id' => $order['id']))->field('goods_name')->select();
				foreach ($goodsNames as $k => $name) {
					$names .= $name['goods_name'] . ' | ';
				}
				$orderList[$key]['names'] = trim($names, ' |');
				if (in_array($order['order_status'], array('payed'))) {
	    			$notSend[] = $order;
	    		} elseif (in_array($order['order_status'], array('shipped', 'received'))) {
	    			$send[] = $order;
	    		}
			}
			$customer = $memberModel->where(array('community_id' => $communityId, 'member_type' => 'normal'))->count();
			$this->assign('customer', $customer);
			$this->assign('notSend', count($notSend));
			$this->assign('send', count($send));
			$this->assign('orderList', $orderList);
			$this->assign('title', '个人主页');
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
     * 代金券详情
     */
    
    public function couponInfo() {
    	$memberCouponModel = M('MemberCoupon');
		$id = max(intval($_GET['id']), 0);
		$memberCouponInfo = $memberCouponModel->where(array('id' => $id, 'member_id' => $_SESSION['uid']))->find();
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
        $step = intval($_GET['step']);
        if($step == 2){
            $type = intval($_GET['type']);
            switch($type){
                case 2:$charge_amount=200;$extra_amount=20;break;
                case 3:$charge_amount=500;$extra_amount=50;break;
                case 4:$charge_amount=1000;$extra_amount=100;break;
                default:$charge_amount=100;$extra_amount=10;break;
            }
            $this->assign("type",$type);
            $this->assign("charge_amount",$charge_amount);
            $this->assign("extra_amount",$extra_amount);
            $this->display("recharge2");
            exit();  
        }else if($step == 3){
            $this->display("recharge3");
            exit(); 
        }
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

    //添加收货地址
    public function addAddress(){
    	$this->assign('jumpurl', base64_decode($_GET['jumpurl']));
        $this->display();
    }

    //修改收货地址页面
    public function setAddress(){
        $uid = $_SESSION['uid'];
        $address = D("Member")->getAddress($uid);
        $this->assign("address",$address);
        $this->display();
    }

    //编辑收货地址
    public function editAddress(){
        $uid = $_SESSION['uid'];
        $id = intval($_GET['id']);
        $address = M("Member_address")->where("member_id={$uid} && id={$id}")->find();
        $this->assign("address",$address);
        $this->display();
    }


    //我的伙拼
    public function groupApply(){
        $uid = $_SESSION['uid'];
        $status = empty($_GET['status'])?0:intval($_GET['status']);
        if($status<1 || $status>3){
            $status = 0;
        } 
        $this->assign("status",$status);
        $size = 20;
        $param = array(
            'status'=>$status,
            'size'=>$size
        );
        $groupApply = D("Goods")->getMyGroupApply($param);
        $totalPage = ceil($groupApply['count']/$size);
        $this->assign("totalPage",$totalPage);
        $this->assign("size",$size);
        $this->assign("groupApply",$groupApply['data']);

        $groupApplyNum = D("Goods")->countMyGroupApply($uid);
        $this->assign("groupApplyNum",$groupApplyNum);

        $this->display();
    }
}
?>