<?php
/**
 * 支付
 * @author mike
 */
class PayAction extends CommonAction {
    
	public function _initialize() {
        parent::_initialize();
        //登录判断   
        $actionName = ACTION_NAME;
        $moduleName = MODULE_NAME;
        //不需要登录可以访问的模块方法
        $module_action = array(); 
        if (empty($_SESSION['uid']) && !in_array($moduleName."-".$actionName,$module_action)) {
            $this->redirect('Public/login');
        }
    }
	
	public function index(){
		$this->orderPay();
    }
    
    /**
     * 订单支付
     */
    
    public function orderPay() {
    	$orderNo = $_GET['id'];
        if (empty($orderNo)) {
            $this->redirect('Member/myOrder');
        }
        $infoArr = $this->getPayInfo($orderNo);
        if ($infoArr == false) {
            $this->redirect("Index/index");
        }
        print_r($infoArr);exit('待续...');
        $this->assign("number", $infoArr['number']);
        $this->assign("total", $infoArr['total']);
        $this->display('orderPay');
    }
    
    /**
     * 支付操作判断
     */
    
    public function payment() {
    }
    
	/**
     * 获取订单支付信息
     * @param $orderNo 商户订单号         
     * @return array $infoArr
     */
    
    private function getPayInfo($orderNo) {
        $infoArr = array();
        if (substr($orderNo, 0, 1) == 'u') {
            $orderList = M('Order')->where("member_id = '{$_SESSION['uid']}' AND combine_pay_no = '$orderNo'")->select();
            if (!$orderList) {
                return false;
            }
            foreach ($orderList as $key => $order) {
            	if (!$order || $order['pay_status'] != 0 || $order['order_status'] != 'created') {
                    return false;                       
                }
                $infoArr['total'] += $order['order_amount'];
            }
            $infoArr['number'] = $orderNo;
            $infoArr['title'] = 'lvjie商品合并付款';
        } else {
            $orderInfo = M('order')->where("member_id = '{$_SESSION['uid']}' AND order_no = '$orderNo'")->find();  
            if (!$orderInfo || $orderInfo['pay_status'] != 0 || $orderInfo['order_status'] != 'created') {
                return false;
            }
            $infoArr['total'] = $orderInfo['order_amount'];
            $infoArr['number'] = $orderInfo['order_no'];
            $orderGoodsList = M('OrderGoods')->where("order_id = '{$orderInfo['id']}'")->field('goods_name')->select();
            foreach ($orderGoodsList as $key => $vo) {
            	$goodsNames .= $vo['goods_name'] . ',';
            }
            $infoArr['title'] = trim($goodsNames, ','); 
        }
        return $infoArr;
    }
}
?>