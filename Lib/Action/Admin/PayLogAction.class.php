<?php
/*
 * 后台订单支付记录管理
 * @author   Lyon
 * @version  PayLogAction.calss.php 2014-3-13
 * 
 */
class PayLogAction extends CommonAction {
    public function index() {
        $where = "1 = 1";      
        if(!empty($_GET['keywords'])) {
            $keywords = trim($_GET['keywords']);           
            $where .= " AND (order_no like '%{$keywords}%' OR trade_no like '%{$keywords}%' OR bank_seq_no like '%{$keywords}%')";
        }      

        $log = M('order_pay_log');
        $count = $log->where($where)->count('id');
    
        if($count>0) {
            import("@.ORG.Util.Page");
            if (!empty($_REQUEST ['listRows'])) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = '20';
            }
            $p = new Page($count, $listRows);
            $page = $p->show();
            $list = $log->where($where)->limit($p->firstRow . ',' . $p->listRows)->order("pay_time DESC")->select();                
        }
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display();
    }
       
}