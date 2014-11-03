<?php
/**
 * 订单管理
 */
class OrderAction extends CommonAction {
	private $listRows = 20;
	
    public function index() {
        $this->normalOrder();
    }
    
    /**
     * 普通订单列表
     */
    
    public function normalOrder() {
    	$orderModel = M('Order');
        $payStatus = intval($_GET['pay_status']);
        $orderStatus = $_GET['order_status'];
        $keywords = trim($_GET['keywords']);
        
        $condition = array();
        $condition['order_type'] = 'normal';
        if(!empty($payStatus)) {
            $condition['pay_status'] = $payStatus;
        }
        if(!empty($orderStatus)) {
            $condition['order_status'] = $orderStatus;
        }        
        if(!empty($keywords)) {
            $condition['order_no'] = array('like', '%' . $keywords . '%');
        }
        $count = $orderModel->where($condition)->count('id');     
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, $this->listRows);
            $page = $p->show();
            $orderList = $orderModel->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order('create_time desc')->select();                
        }
        
        $this->assign('pay_status', $payStatus);
        $this->assign('order_status', $orderStatus);
        $this->assign('keywords',$keywords);
        $this->assign('page', $page);
        $this->assign('orderList', $orderList);
        $this->display('orderList');
    }
    
    /**
     * 伙拼订单列表
     */
    
	public function groupOrder() {
    	$orderModel = M('Order');
        $payStatus = intval($_GET['pay_status']);
        $orderStatus = $_GET['order_status'];
        $keywords = trim($_GET['keywords']);
        
        $condition = array();
        $condition['order_type'] = 'group';
        if(!empty($payStatus)) {
            $condition['pay_status'] = $payStatus;
        }
        if(!empty($orderStatus)) {
            $condition['order_status'] = $orderStatus;
        }        
        if(!empty($keywords)) {
            $condition['order_no'] = array('like', '%' . $keywords . '%');
        }
        $count = $orderModel->where($condition)->count('id');
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, $this->listRows);
            $page = $p->show();
            $orderList = $orderModel->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order('create_time desc')->select();                
        }
        
        $this->assign('pay_status', $payStatus);
        $this->assign('order_status', $orderStatus);
        $this->assign('keywords',$keywords);
        $this->assign('page', $page);
        $this->assign('orderList', $orderList);
        $this->display('orderList');
    }
    
    /**
     * 编辑订单
     */
    
    public function editOrder() {
    	$orderModel = M('Order');                   
        $memberModel = D('Member');
        $provinceList = $memberModel->getAllRegions();
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->error(L('OPERATION_WRONG'));
        }
        
        $info = $orderModel->where("id = '$id'")->find();
        $list = M('OrderGoods')->where("order_id = '$id'")->select();
        $action = M('OrderAction')->where("order_id = '$id'")->order("log_time DESC")->select();
        
        $this->assign("regionjson", json_encode($provinceList));
        $this->assign('info', $info);
        $this->assign('list', $list);
        $this->assign('action', $action);
        $this->display();  
    }
    
	/**
	 * 保存收货地址
	 */
    
    public function saveAddress() {
        $orderModel = M('Order');
        $orderModel->create();
        $res = $orderModel->save();
        if (!$res) {
            $back->status = 0;
            $back->prompt = '系统出错啦';
            ajax_return($back);
        }
        $back->status = 1;
        ajax_return($back);
    }
    
    /**
     * 将订单设置为已付款
     */
    
    public function setPayed() {
        $orderModel = M('Order');
        $orderActionModel = M('OrderAction');
        $orderId = max(intval($_POST['order_id']), 0);
        $actionNote = $_POST['action_note'];
        $payName = $_POST['payname'];
		$back = new stdClass();
		
        $orderInfo = $orderModel->where("id = '$orderId'")->field('order_status, order_amount')->find();
        if ($orderInfo['order_status'] != 'created' ) {
            $back->status = 0;
            $back->prompt = "参数错误";
            ajax_return($back);
        }
        
        $orderModel->startTrans();
        $data = array(
        	'order_status' => "payed",
        	'pay_status' => 1,
        	"pay_name" => $payName,
        	"pay_time" => time()
        );
        $res = $orderModel->where("id = '$orderId'")->save($data);
        if ($res === false) {
            $orderModel->rollback();
            $back->status = 0;
            $back->prompt = '参数错误';
            ajax_return($back);
        }

        
        $map = array();               
        $map['log_time'] = time();
        $map['order_id'] = $orderId;
        $map['money'] = $orderInfo['order_amount'];
        $map['action_user'] = $_SESSION['loginUserName'];
        $map['action_user_id'] = $_SESSION[C('USER_AUTH_KEY')];
        $map['action_note'] = $actionNote;
        $map['order_status'] = "payed";
        $map['pay_status'] = 1;
        $id = $orderActionModel->add($map);
        if (!$id) {
            $orderModel->rollback();
            $back->status = 0;
            $back->prompt = '参数错误';
            ajax_return($back);
        }
        
        $orderModel->commit();
        $back->status = 1;
        ajax_return($back);
    }

    /**
     * 将订单设置为已发货
     */
    
    public function setShipped() {
        $orderModel = M('Order');
        $orderActionModel = M('OrderAction');
        $id = max(intval($_POST['order_id']), 0);
        
        $info = $orderModel->where("id = '$id'")->field('order_status, shipping_status, order_amount')->find();
        $back = new stdClass();
        if ($info['order_status'] != "payed") {
            $back->status = 0;
            $back->prompt = '参数错误';
            ajax_return($back);
        }
        if ($row['shipping_status'] != 0) {
            $back->status = 0;
            $back->prompt = '参数错误';
            ajax_return($back);
        }
        
        $orderModel->startTrans();
        $condition = array();
        $condition['shipping_no'] = $_POST['shipping_no'];
        $condition['shipping_time'] = time();
        $condition['confirm_time'] = strtotime('+3 day'); //自动收货时间
        $condition['shipping_status'] = 1;
        $condition['order_status'] = "shipped";
        $res = $orderModel->where("id={$id}")->save($condition);
        if ($res === false) {
        	$orderModel->rollback();
            $back->status = 0;
            $back->prompt = "亲,系统出错啦";
            ajax_return($back);
        }
        
        $map['log_time'] = time();
        $map['order_id'] = $id;
        $map['action_user'] = $_SESSION['loginUserName'];
        $map['action_user_id'] = $_SESSION[C('USER_AUTH_KEY')];
        $map['action_note'] = "将订单设置为已发货";
        $map['money'] = $info['order_amount'];
        $map['pay_status'] = 1;
        $map['order_status'] = $info['order_status'];
        $id = $orderActionModel->add($map);
        if (!$id) {
            $orderModel->rollback();
            $back->status = 0;
            $back->prompt = '参数错误';
            ajax_return($back);
        }
        
        $orderModel->commit();
        $back->status = 1;
        ajax_return($back);
    }
}