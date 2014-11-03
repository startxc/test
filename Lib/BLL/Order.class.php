<?php
/**
* 确认收货业务类
* @param $gid 订单商品表 id
* @param $oid 订单表 id
* @return bool
*/
class Order {
	public function goodsConfirm($gid,$oid) {
		$msg = true;
		$model = M('order');
		$model->startTrans();

		try {
			// 订单检测
			$info = $this->_checkOrderStatus($gid,$oid);
			$row = $info['goods'];

			// 更新商品物流状态
			$this->_setGoodsStatus($gid);

			// 商家结算				
			$this->_saleCount($row);

			if ($row['charity_id'] > 0) {
				// 捐款记录
				$this->_donationRecore($row);

				// 慈善项目金额累加
				$this->_charityMoney($row);

				// 慈善总金额累加
				$this->_chrityTotalMoney($row['charity_total']);

				// 个人捐款总额累加
				$this->_donation($row['charity_total']);               
			}

            $model->commit();
		} catch (Exception $e) {
			$msg = $e->getMessage();

            $model->rollback();
		}

		return $msg;
				
		$map = array();
		$confirm_number = $info['confirm_number'] + 1;
        $contact_number = $confirm_number + $info['return_number'];
        // 更新订单商品收货数量
	}

	/**
	* 订单状态检测
	*/
	private function _checkOrderStatus($gid,$oid){
		$info = array();

        $order = M('order')->where("id = '$oid'")->field('order_status,number,confirm_number,return_number,order_amount,order_no')->find();
        $status = array("shipped", "shipped_part");
        if (!in_array($order['order_status'], $status)) {           
            throw new Exception("Error 1");           
        }

        $info['order'] = $order;

        // 商品是否已发货
        $goods = M('order_goods')->where("member_id = '{$_SESSION['uid']}' AND id = '$gid'")->field('goods_name,goods_id,image,price,shipping_status,goods_sn,factory_id,purchase_price,number,charity_id,charity_total,charity_type')->find();
        if ($goods['shipping_status'] != 1 && $goods['shipping_status'] != 8) {
            throw new Exception("Error 2");
        }

        $info['goods'] = $goods;

        return $info;
	}

	/**
	* 更新商品物流状态-已收货
	*/
	private function _setGoodsStatus($gid) {		
		$result = M('order_goods')->where("member_id = '{$_SESSION['uid']}' AND id = '$gid'")->setField('shipping_status', 2);
        if (!$result) {
            throw new Exception("Error 3");           
        }
	}

	/**
	* 商家结算
	* @param $row 商品信息数组
	*/
	private function _saleCount($row) {
        $data = array();
        $factory = M('member_factory')->where("member_id = '{$row['factory_id']}'")->find();
        if ($factory) {
            $data['total'] = array('exp',"total + ".$row['number']*$row['purchase_price']);
            $data['is_not_checkout'] = array('exp',"is_not_checkout + ".$row['number']*$row['purchase_price']);
            $result = M('member_factory')->where("member_id = '{$row['factory_id']}'")->save($obj);
        } else {
            $data['total'] = $row['purchase_price']*$row['number'];
            $data['is_not_checkout'] = $row['purchase_price']*$row['number'];
            $data['member_id'] = $row['factory_id'];
            $result = M('member_factory')->add($data);
        }                
        if ($result === false) {            
            throw new Exception("Error 4");
        }
	}

	/**
	* 捐款记录
	*/ 
	private function _donationRecore($row) {
        $charityData = array();
        $name = M('member')->where("id = '{$_SESSION['uid']}'")->field('username,nickname,avatar,charity_total')->find();
        $charityData['donation_name'] = empty($name['nickname']) ? $name['username'] : $name['nickname'];
        $charityData['member_id'] = $_SESSION['uid'];

        $info = M('charity')->where("id = '{$row['charity_id']}'")->find();
        $charityData['charity_name'] = $info['title'];
        $charityData['name'] = $info['title'];
        $charityData['charity_id'] = $info['id'];
        $charityData['charity_image'] = $info['image'];
        $charityData['charity_code'] = $info['code'];
        $charityData['goods_id'] = $row['goods_id'];
        $charityData['goods_image'] = $row['image'];
        $charityData['avatar'] = $name['avatar'];
        $charityData['goods_name'] = $row['goods_name'];
        $charityData['money'] = $row['charity_total'];
        $charityData['create_time'] = time();
        $saveDonation = M('donation')->add($charityData);     
        if ($saveDonation === false) {
            throw new Exception("Error 5");
        }
	}

	/**
	* 项目金额累加- charity_type:1,满额时继续叠加,charity_type:0,转移到别的项目
	* @param $row 商品信息数组
	*/
	private function _charityMoney($row) {
        $money = array();
        $money['raise_money'] = array('exp',"raise_money +".$row['charity_total']);
        if ($row['charity_type'] == 1) {           
            $raiseMoney = M('charity')->where("id = '{$row['charity_id']}'")->save($money);
        } else {
            $cid = M('goods')->where("id = '{$row['goods_id']}'")->getField("charity_id");
            $raiseMoney = M('charity')->where("id = '$cid'")->save($money);
        }                      
        if ($raiseMoney === false) {
            throw new Exception("Error 6");
        }
    }

    /**
	* 慈善总金额结算
	* @param $charity_total 商品捐赠总额
	*/
    private function _chrityTotalMoney($charity_total) {
    	$money = array();
    	$money['total_money'] = array('exp',"total_money +".$charity_total);
        $money['update_time'] = time();
        $saveMoney = M('charity_money')->where("id = 1")->save($money);
        if ($saveMoney === false) {           
            throw new Exception("Error 7");
        }
    }

    /**
	* 个人捐赠总额
	* @param $charity_total 商品捐赠总额
	*  
	*/
    private function _donation($charity_total) {
    	$data = array();
    	$data['charity_total'] = array('exp',"charity_total + ".$charity_total);
        $saveCharity = M('member')->where("id = '{$_SESSION['uid']}'")->save($data);
        if ($saveCharity === false) {
            throw new Exception("Error 8");
        }
    }

    /**
	* 个人捐赠总额
	* @param $charity_total 商品捐赠总额
	*  
	*/
    private function _updateConfirmNum($confirm_number,$contact_number,$oid,&$map) {
    	if ($confirm_number < $info['number'] && $contact_number != $info['number']) {
            $backNum = M('order')->where("id = '$oid'")->setField("confirm_number", $confirm_number);
            if ($backNum === false) {
                throw new Exception("Error 9");
            }
        }
        if ($confirm_number == $info['number'] || $contact_number == $info['number']) {
            $data = array("confirm_number" => $confirm_number, "order_status" => "received");
            $order->where("id = '$oid'")->setField($data);
            $map['order_status'] = "received";
            $type = $confirm_number == $info['number'] ? 1 : 2;

            // 订单全部收货，结算订单总成交金额
            $saleAmount = $this->_getSaleAmount($oid, $type);
            // 计算会员推荐收益
            $income = $this->_saveMemberIncome($oid, $saleAmount, $info['order_no']);
            
            // if ($income === false) {
            //     throw new Exception("Error 10");
            // }
        }
    }

    /**
	* 计算订单总金额
	* @param $oid 订单ID
	* @param $type 结算类型 1 无退货，其他 有退货
	* @return $saleAmount 
	*/
    private function _getSaleAmount($oid, $type) {
        $saleAmount = "";
        if ($type == 1) {
            $saleAmount = M('order')->where("id = '$oid'")->getField("order_amount");           
        } else {            
            $goodsList = M('order_goods')->where("order_id = '$oid' AND shipping_status = 2")->field('price')->select();
            foreach ($goodsList as $key => $val) {
                $saleAmount += $val['price'];
            }         
        }

        $order_type =  M('order')->where("id = '$oid'")->getField("order_type");
        if ($order_type == 1) {
            $saleAmount = round($saleAmount*C('MEMBER_DISCOUNT')/100,2);
        }
        M('order')->where("id = '$oid'")->setField("sale_amount",$saleAmount);
        
        return $saleAmount;
    }

    /**
    * 计算会员收益
    * @param orderid 订单id
    * @param 订单总金额
    */
    private function _saveMemberIncome($orderid,$salerAmount,$order_no) {
        $member = M('member');
        $inviteInfo = $member->where("id = '{$_SESSION['uid']}'")->field("invite_code,is_auth_code")->find();
        if ($inviteInfo['is_auth_code'] == 1) {
            // 介绍会员未认证退出
            return 0;            
        }

        $memberInfo = $member->where("member_code = '{$inviteInfo['invite_code']}'")->field("id,username,income")->find();
        if ($memberInfo) {
            $money = round($salerAmount*$memberInfo['income']/100,2);

            // 全站account_log
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

            //member_account 会员个人账户记录
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
                throw new Exception("Error 10");
            }

            //member_income 会员提成记录
            $income = M('member_income');
            $auth = M('wholesaler_auth');
            $authInfo = $auth->where("member_id = '$uid'")->field('company_name,corporation')->find();
            $row = array();
            $row['invite_id'] = $uid;
            $row['member_id'] = $memberInfo['id'];
            $row['shop_name'] = $authInfo['company_name'];
            $row['corporation'] = $authInfo['corporation'];
            $row['order_amount'] = $salerAmount;
            $row['income_amount'] = $money;
            $row['order_no'] = $order_no;     
            $row['create_time'] = time();
            $incomeAdd = $income->add($row);
            if (!$incomeAdd) {
                throw new Exception("Error 11");
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
                    throw new Exception("Error 12");
                }
            } else {
                $obj = array();         
                $obj['total_income'] = $aid['total_income'] + $money;
                $obj['now_amount'] = $aid['now_amount'] + $money;
                $obj['update_time'] = time();
                $amountSave = $amount->where("member_id = '{$memberInfo['id']}'")->save($obj);
                if (!$amountSave) {
                    throw new Exception("Error 13");
                }
            }       
        }    
    }
}