<?php
/**
* 支付宝接口类
* @author yaoke
* @version 2014-2-25
*/

class AliPay {	
	public $cfg;	
	function __construct($alipay) {
		$this->cfg  = $alipay;
	}

	/**
	* 构造,发送请求数据
	* @param  $order Array
	* @return string
	*/
	function getCode($order) {		
        if (!isset($order['subject'])) $order['subject'] = $order['ordersn'];      				
		$parameter = array(
			/*基本参数*/
			// 接口名称
			'service'           => 'create_direct_pay_by_user', //即时到帐
			//'service'           => 'create_partner_trade_by_buyer',  // 担保交易
			//'service'           => 'trade_create_by_buyer', // 担保、即时到帐 双功能
			'seller_email'      => $this->cfg['account'],
			// 合作身份者ID
			'partner'           => $this->cfg['partner'],
			// 参数编码字符集
			'_input_charset'    => 'utf-8',
			// 同步通知页面
			'return_url'        => "http://".$_SERVER['HTTP_HOST'].$sitepath.'/static/alipayrespond.php',
			// 异步通知页面
			'notify_url'        => "http://".$_SERVER['HTTP_HOST'].$sitepath.'/static/alipayrespond.php',
			// 支付类型，固定1
			'payment_type'      => 1, 
			
			/* 业务参数 */
			'subject'           => $order['subject'], //标题
			'out_trade_no'      => $order['ordersn'], //订单号
			'total_fee'			=> $order['count'],
			//附加信息
			//'extra_common_param'  => $order['extra']
			
			//担保交易必须
			"logistics_fee"		=> "0.00",
			"logistics_type"	=> "EXPRESS",
			"logistics_payment"	=> "SELLER_PAY",			
			"receive_name"		=> "无",
			"receive_address"	=> "无",
			
		);

        //快捷登录
        if(!empty($order['token'])){
            $parameter['token'] = $order['token'];
        }

		ksort($parameter);
		reset($parameter);

		$param = '';
		$sign  = '';
		foreach ($parameter AS $key => $val)
		{
		  $param .= "$key =".urlencode($val). "&";
		  $sign  .= "$key = $val&";
		}

		$param = substr($param, 0, -1);
		$sign  = md5(substr($sign, 0, -1).$this->cfg['key']);
		$urlcode = 'https://www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.$sign.'&sign_type=MD5';
		return $urlcode;
	}

	function respond() {
		if (!empty($_POST)) {
			foreach($_POST as $key => $data) {
				$_GET[$key] = $data;
			}
		}

		ksort($_GET);
		reset($_GET);

		$sign = '';
		foreach ($_GET AS $key=>$val) {
			if ($key != 'sign' && $key != 'sign_type' && $key!='action') {
				$sign .= "$key=$val&";
			}
		}

		$sign = substr($sign, 0, -1).$this->cfg['key'];
		if (md5($sign) != $_GET['sign']) {
			return 	false;
		}
		if ( $_GET['trade_status'] == 'TRADE_FINISHED' || 
			$_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS' ||
			$_GET['trade_status'] == 'TRADE_SUCCESS') {
			return true;
		} else {
			return false;
		}
	}
}
?>