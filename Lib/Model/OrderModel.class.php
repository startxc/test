<?php
/**
 * 购物车模型文件
 * @author   Lyon
 * @version  OrderModel.calss.php 2013-10-17
 */

class OrderModel extends CommonModel {
    public $order;
    public function __construct() {
        parent::__construct();
        $this->order = M('order');
    }
    
    /**
     * 商品加入订单
     * @param integer $aid 收货地址ID
     * @param integer or string $gid 结算的商品ID
     * @param integer $shipping_id 物流方式
     * @param string $note 买家留言
     * @param integer $charityType 捐赠类型
     * @return boolean last
     * @version last update 2014-07-25 mike
     */
    public function insert($aid, $gid, $shipping_id, $note, $charityType) {
        $back = new stdClass();
        if (empty($aid) && $aid != 'deliveryHome') { //aid==deliveryHome代售订单忽略收货地址
            $back->prompt = "收货地址不能为空";
            $back->status = 0;
            return $back;
        }
        if (empty($_SESSION['uid'])) {
            $back->prompt = "参数错误";
            $back->status = 0;
            return $back;
        }
        if (!is_numeric($aid) && $aid != 'deliveryHome') {
            $back->prompt = "收货地址错误";
            $back->status = 0;
            return $back;
        }        
        if ($aid != 'deliveryHome') {
	        $address = $this->getAddress($aid);//收货地址
	        if ($address == false) {
	            $back->prompt = "收货地址错误";
	            $back->status = 0;
	            return $back;
	        }
        }
        $info = array();
        $info = array_merge($info, $address);
        unset($info['id']);

        if (!empty($note)) {
            $info['buyer_note'] = $note;//买家留言
        } 
        $cart = M('cart');
        $where = array();
        $where['member_id'] = $_SESSION['uid'];
        if (!empty($gid)) {
            $where['id'] = array("in",$gid);
        } 
        $goodsList = $cart->where($where)->select();                
        if ($goodsList == false) {
            $back->prompt = "购物车中无对应商品";           
            $back->status = 0;
            return $back;
        }
        $normal = array(); //现货
        $prebuy = array(); //预售
        $forSaleNormal = array(); //代售现货
        $forSalePrebuy = array(); //代售预售
        $batchPreby = array(); //代售整批预售
    	foreach ($goodsList as $key => $val) {
            if ($val['is_prebuy'] == 0 && $val['channel_type'] == 'normal' && $val['is_batch'] == 0) {
                $normal[] = $val;
            } elseif ($val['is_prebuy'] == 1 && $val['channel_type'] == 'normal' && $val['is_batch'] == 0) {
                $prebuy[] = $val;
            } elseif ($val['is_prebuy'] == 0 && $val['channel_type'] != 'normal' && $val['is_batch'] == 0) {
                $forSaleNormal[] = $val;
            } elseif ($val['is_prebuy'] == 1 && $val['channel_type'] != 'normal' && $val['is_batch'] == 0) {
                $forSalePrebuy[] = $val;
            } elseif ($val['is_prebuy'] == 1 && $val['channel_type'] != 'normal' && $val['is_batch'] == 1) {
            	$batchPreby[] = $val;
            }
        }
        $cart->startTrans();
        $sid = explode("|", $shipping_id);
        $shipNormal = explode(",", $sid[0]);
        $shipPrebuy = explode(",", $sid[1]);
        //普通商品
        $orderid = "";
        $order_amount = "";
        $call = $this->order->where("member_id = '{$_SESSION['uid']}' AND order_status <> 'canceled'")->find();                       
        $info['order_type'] = $call == false ? 1 : 0 ;       
        if ((!empty($normal) && !empty($prebuy)) || count($prebuy) > 1) {
            $info['combine_pay_no'] = "u".$this->createNumber();
            $orderid = $info['combine_pay_no'];
        }

        if (!empty($normal)) {           
            $res = $this->inventory($normal);//库存
            if ($res->status == 0) {        
                return $res;
            }
            $info['number'] = count($normal);               
            $normal = $this->getTotalPrice($normal,'normal',$info['order_type']);
            $priceInfo = array();
            $priceInfo['goods_amount'] = $normal['goods_amount'];
            $priceInfo['order_amount'] = $normal['order_amount'];
            $order_amount = $normal['order_amount'];
            $normalid = $this->saveOrderInfo($info,'normal',$priceInfo);
            if ($normalid === false) {
                $cart->rollback();            
                $back->prompt = "亲,对不起,系统出现错误啦";
                //$back->info = $this->order->getLastSql();
                $back->status = 0;
                return $back;
            }            
            unset($normal['order_amount']);
            unset($normal['goods_amount']);
            foreach ($normal as $k => $v) {
                $normal[$k]['order_id'] = $normalid;
                $normal[$k]['member_id'] = $_SESSION['uid'];
                $normal[$k]['charity_type'] = $charityType;
                $normal[$k]['shipping_id'] = empty($shipNormal[$k])? 0:1;
                unset($normal[$k]['id']);
                $result = M('order_goods')->add($normal[$k]);
                if ($result === false) {
                    $back->prompt = 0;
                    //$back->orderid = $normalid;
                    return $back;
                }               
            }
            if (empty($orderid)) {
                $orderid = $normalid;           
            }
        }       
        if (!empty($prebuy)) {
            $info['number'] = 1;//一件商品一条订单 
            $info['is_prebuy'] = 1;            
            foreach ($prebuy as $key => $val) {
                $prebuy[$key] = $this->getTotalPrice($prebuy[$key],'prebuy');
                $priceInfo = array();
                $priceInfo['goods_amount'] = $prebuy[$key]['goods_amount'];
                $priceInfo['order_amount'] = $prebuy[$key]['order_amount'];
                $priceInfo['deposit'] = $prebuy[$key]['deposit'];
                $order_amount += $priceInfo['deposit'];
                //dump($prebuy[$key]);
                //dump($priceInfo);
                $prebuyid = $this->saveOrderInfo($info,'prebuy',$priceInfo,$val['channel_type']);
                if ($prebuyid === false) {
                    $cart->rollback();           
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    $back->info = $this->order->getLastSql();
                    $back->status = 0;
                    return $back;
                }
                unset($prebuy[$key]['order_amount']);
                unset($prebuy[$key]['goods_amount']);
				
                $prebuy[$key]['order_id'] = $prebuyid;
                $prebuy[$key]['member_id'] = $_SESSION['uid'];
                $prebuy[$key]['shipping_id'] = empty($shipPrebuy[$k])? 0:1;
                $prebuy[$key]['charity_type'] = $charityType;
                unset($prebuy[$key]['id']);

                $savePrebuy = M('order_goods')->add($prebuy[$key]);            
                if ($savePrebuy === false) {
                    $cart->rollback();    
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    //$back->goods = $goodsList;
                    $back->status = $shipPrebuy;
                    $back->sta = M('order_goods')->getLastSql();
                    return $back;
                }
                if (empty($orderid)) {
                    $orderid = $prebuyid;           
                }        
            }                                                                               
        }
        if (!empty($forSaleNormal)) {
        	$info['number'] = 1;//一件商品一条订单 
            $info['is_prebuy'] = 0;          
              
            foreach ($forSaleNormal as $key => $val) {
                $forSaleNormal[$key] = $this->getTotalPrice($forSaleNormal[$key],'forSaleNormal');
                
                $priceInfo = array();
                $priceInfo['goods_amount'] = $forSaleNormal[$key]['goods_amount'];
                $priceInfo['order_amount'] = $forSaleNormal[$key]['order_amount'];
                //$priceInfo['deposit'] = $forSaleNormal[$key]['deposit'];
                //$order_amount += $priceInfo['deposit'];
                $order_amount += $forSaleNormal[$key]['order_amount'];
                
                $prebuyid = $this->saveOrderInfo($info,'normal',$priceInfo,$val['channel_type']);
                if ($prebuyid === false) {
                    $cart->rollback();           
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    $back->info = $this->order->getLastSql();
                    $back->status = 0;
                    return $back;
                }
                unset($forSaleNormal[$key]['order_amount']);
                unset($forSaleNormal[$key]['goods_amount']);
				
                $forSaleNormal[$key]['order_id'] = $prebuyid;
                $forSaleNormal[$key]['member_id'] = $_SESSION['uid'];
                $forSaleNormal[$key]['shipping_id'] = empty($shipPrebuy[$k])? 0:1;
                $forSaleNormal[$key]['charity_type'] = $charityType;
                unset($forSaleNormal[$key]['id']);

                $savePrebuy = M('order_goods')->add($forSaleNormal[$key]);            
                if ($savePrebuy === false) {
                    $cart->rollback();    
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    //$back->goods = $goodsList;
                    $back->status = $shipPrebuy;
                    $back->sta = M('order_goods')->getLastSql();
                    return $back;
                }
                if (empty($orderid)) {
                    $orderid = $prebuyid;           
                }        
            }
        }
    	if (!empty($forSalePrebuy)) {
        	$info['number'] = 1;//一件商品一条订单 
            $info['is_prebuy'] = 1;          
              
            foreach ($forSalePrebuy as $key => $val) {
                $forSalePrebuy[$key] = $this->getTotalPrice($forSalePrebuy[$key],'forSalePrebuy');
                
                $priceInfo = array();
                $priceInfo['goods_amount'] = $forSalePrebuy[$key]['goods_amount'];
                $priceInfo['order_amount'] = $forSalePrebuy[$key]['order_amount'];
                $priceInfo['deposit'] = $forSalePrebuy[$key]['deposit'];
                $order_amount += $priceInfo['deposit'];
				
                $prebuyid = $this->saveOrderInfo($info,'prebuy',$priceInfo,$val['channel_type']);
                if ($prebuyid === false) {
                    $cart->rollback();
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    $back->info = $this->order->getLastSql();
                    $back->status = 0;
                    return $back;
                }
                unset($forSalePrebuy[$key]['order_amount']);
                unset($forSalePrebuy[$key]['goods_amount']);
				
                $forSalePrebuy[$key]['order_id'] = $prebuyid;
                $forSalePrebuy[$key]['member_id'] = $_SESSION['uid'];
                $forSalePrebuy[$key]['shipping_id'] = empty($shipPrebuy[$k])? 0:1;
                $forSalePrebuy[$key]['charity_type'] = $charityType;
                unset($forSalePrebuy[$key]['id']);

                $savePrebuy = M('order_goods')->add($forSalePrebuy[$key]);            
                if ($savePrebuy === false) {
                    $cart->rollback();    
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    //$back->goods = $goodsList;
                    $back->status = $shipPrebuy;
                    $back->sta = M('order_goods')->getLastSql();
                    return $back;
                }
                if (empty($orderid)) {
                    $orderid = $prebuyid;           
                }        
            }
        }
        if (!empty($batchPreby)) {
        	$info['number'] = 1;//一件商品一条订单 
            $info['is_prebuy'] = 1;          
            $info['is_batch'] = 1;
            
            foreach ($batchPreby as $key => $val) {
                $batchPreby[$key] = $this->getTotalPrice($batchPreby[$key],'batchPreby');
                
                $priceInfo = array();
                $priceInfo['goods_amount'] = $batchPreby[$key]['goods_amount'];
                $priceInfo['order_amount'] = $batchPreby[$key]['order_amount'];
                $priceInfo['deposit'] = $batchPreby[$key]['deposit'];
                $order_amount += $priceInfo['deposit'];

                $prebuyid = $this->saveOrderInfo($info,'prebuy',$priceInfo,$val['channel_type']);
                if ($prebuyid === false) {
                    $cart->rollback();
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    $back->info = $this->order->getLastSql();
                    $back->status = 0;
                    return $back;
                }
                unset($batchPreby[$key]['order_amount']);
                unset($batchPreby[$key]['goods_amount']);
				
                $batchPreby[$key]['order_id'] = $prebuyid;
                $batchPreby[$key]['member_id'] = $_SESSION['uid'];
                $batchPreby[$key]['shipping_id'] = empty($shipPrebuy[$k])? 0:1;
                $batchPreby[$key]['charity_type'] = $charityType;
                unset($batchPreby[$key]['id']);

                $savePrebuy = M('order_goods')->add($batchPreby[$key]);            
                if ($savePrebuy === false) {
                    $cart->rollback();    
                    $back->prompt = "亲,对不起,系统出现错误啦";
                    //$back->goods = $goodsList;
                    $back->status = $shipPrebuy;
                    $back->sta = M('order_goods')->getLastSql();
                    return $back;
                }
                if (empty($orderid)) {
                    $orderid = $prebuyid;           
                }        
            }
        }
        $remove = $cart->where($where)->delete();
        if ($remove === false) {
            $cart->rollback();    
            $back->prompt = "亲,对不起,系统出现错误啦";
            $back->status = 0;
            return $back;
        }
        $cart->commit();
        S('h_order_id_'.$_SESSION['uid'],rtrim($orderid),C('DATA_CACHE_TIME'));
        S("h_order_amount_".$_SESSION['uid'],$order_amount,C('DATA_CACHE_TIME'));
        S("h_goto_order_".$_SESSION['uid'],null);
        S("h_cart_list".$_SESSION['uid'],null);
        $back->status = 1;
        return $back;       
    }
    public function createNumber() {
        /*$date = date('ymd');
        $secs = str_pad((time()+28800)%86400,5,"0",STR_PAD_LEFT);;
        list($s1, $s2) = explode(' ', microtime());            
        $msec = (float)sprintf('%.0f',floatval($s1)*1000);
        $number = $date.$secs.$msec;
        return $number;*/
        $date = date('ymd');
        list($s1, $s2) = explode(' ', microtime());
        $secs = str_pad(intval($s2) %86400,5,"0",STR_PAD_LEFT);       
        $msec =  str_pad(intval($s1*1000),3,"0",STR_PAD_LEFT); 
        $number = $date.$secs.$msec;
        return $number; 
    }
    /**
    * 收货地址
    * @param  integer  $id
    * @return bool  
    */
    private function getAddress($id) {
        $addr = M('member_address');
        $result = $addr->where("id = '$id' AND member_id = '{$_SESSION['uid']}' AND is_default = 1")->find();        
        return $result;
    }

    private function saveOrderInfo($info,$type,$priceInfo,$channelType='') {
        $info['member_id'] = $_SESSION['uid'];
        $info['create_time'] = time();
        $info['expire_time'] = time()+3600*24*3;
        $info['order_amount'] = $priceInfo['order_amount'];
        $info['goods_amount'] = $priceInfo['goods_amount'];
        //$info['order_type'] = $_SESSION['role_type'];
       		
        if (in_array($channelType, array('not_produce', 'pre_sale', 'spot_goods', 'presale_spot'))) {
        	$info['order_type'] = 'for_sale'; //代售订单
        } else {
        	$info['order_type'] = 'normal';
        }
        $info['channel_type'] = $channelType;
        
        if ($type == 'normal') {
            $info['order_no'] = $this->createNumber();
        } else {
            $info['deposit'] = $priceInfo['deposit']; //订金
            $info['order_no'] = "PRE".$this->createNumber();
        }
        $res = $this->order->add($info);
        if ($res === false) {
            $id = false;
        } else {
            $id = $this->order->getLastInsID();
        }
        return $id;       
    }

    /**
    * 计算商品价格
    * @param  array $goods 商品数组
    * @param  string $type 商品类型
    * @param  integer $buyType 是否首单
    * @return array  
    */
    public function getTotalPrice($goods,$type,$buyType) {
        $model = M('goods_property_sku');
        if ($type == 'normal') { //现货
            $field = $_SESSION['role_type'] == 1 ? 'price':'wholesale_price'; //1普通用户，2批发商
            foreach ($goods as $key => $val) {  
                $price = $model->where("pvs = '{$val['sku_id']}' AND goods_id ='{$val['goods_id']}'")->getField($field);                  
                $goods[$key]['price'] = $price;   //商品单价
                $goods['goods_amount'] += $price*$val['number']; //商品总价格  
                $goods['order_amount'] += $buyType == 3 ? round($price*C('MEMBER_DISCOUNT')/100,2)*$val['number']:$price*$val['number']; 
                //捐赠慈善，id-金额
                $goods[$key]['charity_id'] = M('goods')->where("id = '{$val['goods_id']}'")->getField("charity_id");
                $goods[$key]['charity_total'] = $buyType == 3 ? round($price*C('MEMBER_DISCOUNT')/100*1/100,2)*$val['number']:round($price*1/100,2)*$val['number'];
            }
        } elseif ($type == 'prebuy' || $type == 'forSalePrebuy') { //预售、代售预售
            $field = $_SESSION['role_type'] == 1 ? 'pre_price':'pre_wholesale_price';
            $price = $model->where("pvs = '{$goods['sku_id']}' AND goods_id ='{$goods['goods_id']}'")->getField($field);
            $goods['price'] = $price;   //商品单价
            $goods['goods_amount'] += $price*$goods['number']; //商品总价格
            $deposit = M('goods_group')->where("id = '{$goods['prebuyid']}'")->getField('deposit');
            $goods['deposit'] = $deposit*$goods['number'];
            $goods['order_amount'] += $price*$goods['number'];
            // 捐赠慈善，id-金额
            $goods['charity_id'] = M('goods')->where("id = '{$goods['goods_id']}'")->getField("charity_id");
            // 定金不作捐赠部分
            $goods['charity_total'] = round(($price-$deposit)*1/100,2)*$goods['number'];
            
        } elseif ($type == 'forSaleNormal') { //代售现货
        	$field = $_SESSION['role_type'] == 1 ? 'price':'wholesale_price'; //1普通用户，2批发商
        	$price = $model->where("pvs = '{$goods['sku_id']}' AND goods_id ='{$goods['goods_id']}'")->getField($field);                  
            $goods['price'] = $price;   //商品单价
            $goods['goods_amount'] += $price*$goods['number']; //商品总价格  
            $goods['order_amount'] += $buyType == 3 ? round($price*C('MEMBER_DISCOUNT')/100,2)*$goods['number']:$price*$goods['number']; 
            //捐赠慈善，id-金额
            $goods['charity_id'] = M('goods')->where("id = '{$goods['goods_id']}'")->getField("charity_id");
            $goods['charity_total'] = $buyType == 3 ? round($price*C('MEMBER_DISCOUNT')/100*1/100,2)*$goods['number']:round($price*1/100,2)*$goods['number'];
		
        } elseif ($type == 'batchPreby') { //代售整批预售
        	$price = $model->where("pvs = '{$goods['sku_id']}' AND goods_id ='{$goods['goods_id']}'")->getField('batch_price'); //整批单价
        	$goods['price'] = $price; //商品单价
        	$goods['goods_amount'] += $price * $goods['number']; //商品总价格
        	$deposit = $price * 0.1; //商品单价定金
        	$goods['deposit'] = $deposit * $goods['number']; //商品总定金
        	$goods['order_amount'] += $price * $goods['number']; //商品总金额
        	//捐赠慈善，id-金额
            $goods['charity_id'] = M('goods')->where("id = '{$goods['goods_id']}'")->getField("charity_id");
            //定金不作捐赠部分
            $goods['charity_total'] = round(($price-$deposit)*1/100,2)*$goods['number'];
        }
        return $goods;       
    }

    /**
    * 检查商品库存
    * @param  integer  $id 商品ID
    * @param  integer  $num 商品购买数量
    * @return bool  
    */
    public function inventory($goodsList) {
        $back = new stdClass();
        $sku = M('goods_property_sku');
        $goods = M('goods');
        foreach ($goodsList as $key => $val) {
            $number = $sku->where("pvs = '{$val['sku_id']}' AND goods_id ='{$val['goods_id']}'")->getField('number');
            if ($val['number'] > $number) {
                $info = $goods->where("id = '{$val['goods_id']}'")->getField('goods_no,goods_name');    
                $back->status = 0;
                $back->prompt = $val['goods_sn']."-".$val['goods_name']."-商品库存不足";
                return $back;
            }
        }
        $back->status = 1;
        return $back;     
    }
   
    /**
    * 删除订单(单个or批量)
    * @param  mixed  $sid
    * @param  array  $map
    * @return bool  
    */
    public function remove($sid) {
        $map = array();
        $map['member_id'] = $_SESSION['uid'];
        $map['id'] = $sid;
        if (!is_numeric($sid)) {
            if (!preg_match("/^[1-9]+[0-9,]*[0-9]+$/",$sid)) {              
                return false;
            }
            $map['id'] = array('in',$sid);
        }
        $result = $this->order->where($map)->delete();
        if (!$result) {
            return false;
        }
        return true;    
    }

    public function getSaleAmount($id, $type) {
        $saleAmount = "";
        if ($type == 1) {
            $saleAmount = $this->order->where("id = '$id'")->getField("order_amount");           
        } else {            
            $goods = M('order_goods');
            $goodsList = $goods->where("order_id = '$id' AND shipping_status = 2")->field('price')->select();
            foreach ($goodsList as $key => $val) {
                $saleAmount += $val['price'];
            }         
        }
        $order_type =  $this->order->where("id = '$id'")->getField("order_type");
        if ($order_type == 1) {
            $saleAmount = round($saleAmount*C('MEMBER_DISCOUNT')/100,2);
        }
        $this->order->where("id = '$id'")->setField("sale_amount",$saleAmount);
        return $saleAmount;
    }

    public function saveMemberIncome($orderid,$salerAmount,$order_no,$uid) {
        $member = M('member');
        $inviteInfo = $member->where("id = '$uid'")->field("invite_code,is_auth_code")->find();
        // 普通会员和批发商购买,推荐者均可获得提成 
        //if ($inviteInfo['is_auth_code'] == 1) {
            // return "not auth";            
        //}        
        $memberInfo = $member->where("member_code = '{$inviteInfo['invite_code']}'")->field("id,username,income")->find();
        if ($memberInfo) {
            $money = round($salerAmount*$memberInfo['income']/100,2);
            //全站account_log。
            $data = array();
            $data['money'] = $money;
            $data['change_time'] = time();
            $data['create_time'] = time();
            $data['change_desc'] = "会员提成";
            $data['change_type'] = 2;
            $log = M('account_log');
            $logAdd = $log->add($data);
            if (!$logAdd) {               
                return false;
            }
            //member_account 会员个人账户记录 insert
            $account = M('member_account');
            $map = array();
            $map['member_id'] = $memberInfo['id'];
            $map['member_name'] = $memberInfo['username'];
            $map['amount'] = $money;
            $map['paid_time'] = time();
            $map['admin_note'] = "购物提成";
            $map['payment'] = "会员提成";
            $map['create_time'] = time();
            $accountAdd = $account->add($map);
            if (!$accountAdd) {
                return false;
            }
            //member_income 会员提成记录 insert
            $income = M('member_income');
            $auth = M('wholesaler_auth');
            $authInfo = $auth->where("member_id = '$uid'")->field('company_name,corporation')->find();
            $row = array();
            $row['invite_id'] = $uid;
            $row['member_id'] = $memberInfo['id'];
            $row['shop_name'] = empty($authInfo['company_name']) ? '' : $authInfo['company_name'];
            $row['corporation'] = empty($authInfo['corporation']) ? '' : $authInfo['corporation'];
            $row['order_amount'] = $salerAmount;
            $row['income_amount'] = $money;
            $row['order_no'] = $order_no;     
            $row['create_time'] = time();
            $incomeAdd = $income->add($row);
            if (!$incomeAdd) {
                return $income->getLastSql();
            }            
            //member_amount 会员现有资金记录,update
            $amount = M('member_amount');
            $aid = $amount->where("member_id = '{$memberInfo['id']}'")->find();
            //当前账户为空，新建账户
            if (!$aid) {
                $obj = array();
                $obj['member_id'] = $memberInfo['id'];
                $obj['member_name'] = $memberInfo['username'];
                $obj['total_income'] = $money;
                $obj['now_amount'] = $money;
                $obj['update_time'] = time();
                $amountAdd = $amount->add($obj);
                if (!$amountAdd) {
                    return false;
                }
            } else {
                $obj = array();         
                $obj['total_income'] = $aid['total_income'] + $money;
                $obj['now_amount'] = $aid['now_amount'] + $money;
                $obj['update_time'] = time();
                $amountSave = $amount->where("member_id = '{$memberInfo['id']}'")->save($obj);
                if (!$amountSave) {
                    return false;
                }
            }       
        }
        return true;    
    }
    
    /**
     * 添加买断、支持孵化订单
     */
    public function saveHatchOrder($info) { 
        $order = M('Order');
        $order->startTrans();
        $member_id = $_SESSION['uid'];
        $data['member_id'] = $member_id;
        $data['order_type'] = 'hatch';
        $data['order_amount'] = $info['amount'];
        if($info['hatch_status'] == "for_buyout"){
            $data['order_no'] = "MD".$this->createNumber();
        }else{
            $data['order_no'] = "FH".$this->createNumber();
        }
        $order_no = $data['order_no'];
        $now = time();
        $data['create_time'] = $now;
        $orderid = $order->data($data)->add();
        if(empty($orderid)){
            return false;
        }
        $member_info = M('Member')->field("id,username,nickname,avatar")->where("id={$member_id}")->find();
        $data = array("order_id"=>$orderid,
                      "hatch_id"=>$info['hatch_id'],
                      "member_id"=>$member_info['id'],
                      "member_name"=>empty($member_info['nickname'])?$member_info['username']:$member_info['nickname'],
                      "member_avatar"=>$member_info['avatar'],
                      "order_amount"=>$info['amount'],
                      "create_time"=>$now
        );
        if(!M('Order_hatch')->data($data)->add()){
            $order->rollback();
            return false;
        }
        $order->commit();
        return $order_no;
    }
}
?>