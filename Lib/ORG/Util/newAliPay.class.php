<?php
/* *
 * 类名：AliPay
 * 功能：支付宝各接口处理类
 * 详细：构造支付宝各接口请求，获取远程HTTP数据，处理返回数据
 * 作者：Yaoke
 * 日期：2014-02-25
 * 说明：
 * 		合作者身份ID：2088011945205711	
 *		安全校验码KEY：x9hz6ew3fr3ivbyimksu5itagwhvchgu
 */
class newAliPay {
	public $config;	
	public $cacert;
	public $transport;
	public $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	// HTTP形式消息验证地址
	public $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';	
	function __construct() {    	
    	// 合作身份者id，以2088开头的16位纯数字
    	$this->config['partner'] = '2088011945205711';
    	// 安全检验码，以数字和字母组成的32位字符
		$this->config['key'] = 'x9hz6ew3fr3ivbyimksu5itagwhvchgu';
		// 卖家邮箱
		$this->config['seller_email'] = 'gxiang@pansi.com';				
		// 支付宝网关地址（新）
		// $this->config['gateway_new'] = 'https://mapi.alipay.com/gateway.do?';
		// 接口名称
		$this->config['service'] = 'create_direct_pay_by_user'; //即时到帐
		//$this->config['service'] = 'create_partner_trade_by_buyer';  // 担保交易
		//$this->config['service']  = 'trade_create_by_buyer'; // 担保、即时到帐 双功能
		// 签名方式，不需修改，PHP只能用MD5
		$this->config['sign_type']    = strtoupper('MD5');		
		// 字符编码格式 目前支持 gbk 或 utf-8
		$this->config['_input_charset']= strtolower('utf-8');				

		// 商品数量，默认1
		$this->config['quantity']    = "1";
    	// 支付类型，默认
    	$this->config['payment_type'] = "1";
    	// 邮费支付方式
    	$this->config['logistics_payment'] = "BUYER_PAY";
    	// 运费
    	$this->config['logistics_fee'] = "0.00";
    	// 物流方式,快递
        $this->config['logistics_type']  = "EXPRESS";

        // 同步通知地址
    	$this->config['return_url'] = "http://www.pansi.com/Pay/aliPayReturn";    	
    	// 异步通知地址
    	$this->config['notify_url'] = "http://www.pansi.com/Pay/aliPayNotify";
    
    	//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
    	$this->cacert= getcwd().'\\cacert.pem';
    	//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$this->transport   = 'http';
    }
    /**
     * 建立请求，以URL形式构造
     * @param $para_temp 请求参数数组
     * @return 提交url
     */
	function buildRequestUrl($para_temp) {
		$basic = $this->config;
		unset($basic['key']);
		$para_temp = array_merge($para_temp,$basic);
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		$url = $this->createLinkstringUrlencode($para);
		$sHtml = 'https://mapi.alipay.com/gateway.do?'.$url;		
		return $sHtml;
		//return $para['sign'];
	}
	
	/**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
	function verifyReturn($type) {
		$method = $type == 'get' ? $_GET : $_POST;
		if(empty($method)) {//判断POST来的数组是否为空
			return false;
		} else {
			//生成签名结果
			$isSign = $this->getSignVeryfy($method, $method["sign"]);
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (!empty($_GET["notify_id"])) {$responseTxt = $this->getResponse($_GET["notify_id"]);}
			
			//写日志记录
			//if ($isSign) {
			//	$isSignStr = 'true';
			//}
			//else {
			//	$isSignStr = 'false';
			//}
			//$log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
			//$log_text = $log_text.createLinkString($_GET);
			//logResult($log_text);
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return ture;
			} else {
				return false;
			}
		}
	}

	/**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
	function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);

		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);
		
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->config['sign_type']));
		
		return $para_sort;
	}
    /**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	public function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);		
		$mysign = $this->md5Sign($prestr, $this->config['key']);
		return $mysign;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	private function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);

		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	function createLinkstringUrlencode($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	public function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $key == '_URL_' || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	public function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
	/**
	 * 签名字符串
	 * @param $prestr 需要签名的字符串
	 * @param $key 私钥
	 * return 签名结果
	 */
	public function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5($prestr);
	}

	/**
	 * 验证签名
	 * @param $prestr 需要签名的字符串
	 * @param $sign 签名结果
	 * @param $key 私钥
	 * return 签名结果
	 */
	public function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5($prestr);

		if($mysgin == $sign) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
	public function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		
		$isSgin = $this->md5Verify($prestr, $sign, $this->config['key']);
		return $isSgin;
	}

	/**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
	function getResponse($notify_id) {
		$transport = strtolower(trim($this->config['transport']));
		$partner = trim($this->config['partner']);
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		//$responseTxt = $veryfy_url;
		$responseTxt = $this->getHttpResponseGET($veryfy_url, $this->cacert);
		
		return $responseTxt;
	}
	/**
	 * 远程获取数据，GET模式
	 * 注意：
	 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
	 * @param $url 指定URL完整路径地址
	 * @param $cacert_url 指定当前工作目录绝对路径
	 * return 远程输出的数据
	 */
	function getHttpResponseGET($url,$cacert_url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		//var_dump($responseText);		
		return $responseText;
	}
}
?>