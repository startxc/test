<?php
$apis = array();

//会员相关接口
$apis[] = array(
	'category'=>'会员相关接口',
	'api'=>array(
			'Member/login'=>array(
					'desc' => '用户登录',
					'field'=>array(
							'mobile'=>array('desc'=>'手机号码'),
							'password'=>array('desc'=>'用户密码'),
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"登录成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表登录成功，0代表登录失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/register'=>array(
					'desc' => '用户注册',
					'field'=>array(
							'mobile'=>array('desc'=>'手机号码'),
							'verify'=>array('desc'=>'验证号'),
							'password'=>array('desc'=>'登录密码'),
							'confirm_password'=>array('desc'=>'确认密码'),
							'nickname'=>array('desc'=>'昵称')
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"注册成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表注册成功，0代表注册失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setPassword'=>array(
					'desc' => '修改登录密码',
					'field'=>array(
							'password'=>array('desc'=>'原密码'),
							'new_password'=>array('desc'=>'新密码'),
							'confirm_password'=>array('desc'=>'确认密码')
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"修改登录密码成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表修改登录密码成功，0代表修改登录密码失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setPayPassword'=>array(
					'desc' => '修改支付密码',
					'field'=>array(
							'password'=>array('desc'=>'原密码'),
							'new_password'=>array('desc'=>'新密码'),
							'confirm_password'=>array('desc'=>'确认密码')
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"修改支付密码成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表修改支付密码成功，0代表修改支付密码失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setMobile'=>array(
					'desc' => '修改手机号码',
					'field'=>array(
							'mobile'=>array('desc'=>'手机号码'),
							'verify'=>array('desc'=>'验证号')
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"修改手机号码成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表修改手机号码成功，0代表修改手机号码失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/getAddress'=>array(
					'desc' => '获取地址列表',
					'type'=>'GET',
					'response'=>'{"status":1,"info":"",data:{id:1,member_id:8,consignee:"startcc",province_id:6,city_id:86,area_id:786,province_name:"广东",city_name,"梅州",area_name:"五华县",address:"cccc",mobile:15820429581,is_default:1}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'地址编号'),
						array('field'=>'member_id','desc'=>'会员id'),
						array('field'=>'consignee','desc'=>'收货人'),
						array('field'=>'province_id','desc'=>'省份id'),
						array('field'=>'city_id','desc'=>'城市id'),
						array('field'=>'area_id','desc'=>'区县id'),
						array('field'=>'community_id','desc'=>'小区id'),
						array('field'=>'province_name','desc'=>'省份名称'),
						array('field'=>'city_name','desc'=>'城市名称'),
						array('field'=>'area_name','desc'=>'区县名称'),
						array('field'=>'community_name','desc'=>'小区名称'),
						array('field'=>'address','desc'=>'详细地址'),
						array('field'=>'mobile','desc'=>'手机号码'),
						array('field'=>'is_default','desc'=>'是否默认地址')
					)
			),
			'Member/addAddress'=>array(
					'desc' => '添加收货地址',
					'type'=>'POST',
					'field'=>array(
							'consignee'=>array('desc'=>'收货人姓名'),
							'mobile'=>array('desc'=>'收货人手机'),
							'province_id'=>array('desc'=>'省份id'),
							'city_id'=>array('desc'=>'城市id'),
							'area_id'=>array('desc'=>'区县id'),
							'community_id'=>array('desc'=>'小区id'),
							'address'=>array('desc'=>'详细地址'),
					),
					'response'=>'{"status":1,"info":"添加收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表添加收货地址成功，0代表添加收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setAddress'=>array(
					'desc' => '修改收货地址',
					'type'=>'POST',
					'field'=>array(
							'address_id'=>array('desc'=>'地址id编号'),
							'consignee'=>array('desc'=>'收货人姓名'),
							'mobile'=>array('desc'=>'收货人手机'),
							'province_id'=>array('desc'=>'省份id'),
							'city_id'=>array('desc'=>'城市id'),
							'area_id'=>array('desc'=>'区县id'),
							'community_id'=>array('desc'=>'小区id'),
							'address'=>array('desc'=>'详细地址'),
					),
					'response'=>'{"status":1,"info":"修改收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表修改收货地址成功，0代表修改收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/deleteAddress'=>array(
					'desc' => '删除收货地址',
					'type'=>'POST',
					'field'=>array(
							'address_id'=>array('desc'=>'地址id编号')
					),
					'response'=>'{"status":1,"info":"删除收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表删除收货地址成功，0代表删除收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setDefaultAddress'=>array(
					'desc' => '设置默认收货地址',
					'type'=>'POST',
					'field'=>array(
							'address_id'=>array('desc'=>'地址id编号')
					),
					'response'=>'{"status":1,"info":"设置默认收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表设置默认收货地址成功，0代表设置默认收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/setBindMember'=>array(
					'desc' => '绑定会员',
					'type'=>'POST',
					'field'=>array(
							'bind_member'=>array('desc'=>'会员卡号')
					),
					'response'=>'{"status":1,"info":"绑定会员成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表绑定会员成功，0代表绑定会员失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/loginOut'=>array(
					'desc' => '退出帐号',
					'type'=>'GET',
					'response'=>'{"status":1,"info":"退出帐号成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表退出帐号成功，0代表退出帐号失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
	 ),
);

//商品与伙拼相关接口
$apis[] = array(
	'category'=>'商品与伙拼相关接口',
	'api'=>array(
			'Goods/getGoodsByCategoryId'=>array(
					'desc' => '获取某一分类下的商品',
					'field'=>array(
							'cid'=>array('desc'=>'分类id'),
					),
					'type'=>'GET',
					'response'=>'{"status":1,"info":"",{id:1,category_id:1,production_id:1,name:"蔬菜1",price:10.00,image:201410305451ed907043a.jpg,description:"好吃的蔬菜",sale_count:0,spec:100}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'商品id'),
						array('field'=>'category_id','desc'=>'分类id'),
						array('field'=>'production_id','desc'=>'产地id'),
						array('field'=>'name','desc'=>'商品名称'),
						array('field'=>'price','desc'=>'商品价格'),
						array('field'=>'image','desc'=>'商品图片'),
						array('field'=>'description','desc'=>'商品描述'),
						array('field'=>'sale_count','desc'=>'商品销量'),
						array('field'=>'spec','desc'=>'商品规格')
					)
			),
			'Goods/getCategoryList'=>array(
					'desc' => '获取商品分类列表',
					'type'=>'GET',
					'response'=>'{"status":1,"info":"",{id:1,name:"叶菜类",image:""}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'分类id'),
						array('field'=>'name','desc'=>'分类名称'),
						array('field'=>'image','desc'=>'分类图片')
					)
			),
			'Goods/getProductionList'=>array(
					'desc' => '获取商品产地列表',
					'type'=>'GET',
					'field'=>array(
							'goods_id'=>array('desc'=>'商品id'),
					),
					'response'=>'{"status":1,"info":"",{id:2,name:"湖南"}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'产地id'),
						array('field'=>'name','desc'=>'产地名称')
					)
			),
			'Goods/getGoodsList'=>array(
					'desc' => '获取商品列表',
					'type'=>'get',
					'field'=>array(
							'is_recommend'=>array('desc'=>'是否推荐'),
							'is_group'=>array('desc'=>'是否可以伙拼'),
							'size'=>array('desc'=>'商品个数'),
							'order'=>array('desc'=>'商品排序规则'),
							'cid'=>array('desc'=>'商品分类id')
					),
					'response'=>'{"status":1,"info":"",{id:1,category_id:1,name:"蔬菜1",price:10.00,image:201410305451ed907043a.jpg,description:"好吃的蔬菜",sale_count:0,spec:100}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'商品id'),
						array('field'=>'category_id','desc'=>'分类id'),
						array('field'=>'name','desc'=>'商品名称'),
						array('field'=>'price','desc'=>'商品价格'),
						array('field'=>'image','desc'=>'商品图片'),
						array('field'=>'description','desc'=>'商品描述'),
						array('field'=>'sale_count','desc'=>'商品销量'),
						array('field'=>'spec','desc'=>'商品规格')
					)
			),
			'Goods/applyGroup'=>array(
					'desc' => '申请伙拼',
					'type'=>'POST',
					'field'=>array(
							'goods_id'=>array('desc'=>'商品id'),
							'production_id'=>array('desc'=>'产地id'),
							'remark'=>array('desc'=>'个人说明'),
							'member_type'=>array('desc'=>'广而告知对象'),
					),
					'response'=>'{"status":1,"info":"提交伙拼申请信息成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表提交伙拼申请信息成功，0代表提交伙拼申请信息失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Goods/getGroupList'=>array(
					'desc' => '获取伙拼商品列表',
					'type'=>'GET',
					'field'=>array(
							'size'=>array('desc'=>'条数'),
							'order'=>array('desc'=>'排序'),
							'is_recommend'=>array('desc'=>'是否推荐：1推荐、0不推荐'),
							'date'=>array('desc'=>'日期'),
							'keyword'=>array('desc'=>'查询关键词')
					),
					'response'=>'{"status":1,"info":"",{id:1,goods_id:1,category_id:1,production_id:3,name:"蔬菜2",description:"dddgda",price:30,min_price:15.00,min_price_spec:3000,real_price:30.00,image:"201410305451ed907043a.jpg",moq_spec:1000,sale_count:0,sale_spec:0,sale_total_count:0,sale_total_spec:0,start_time:1414684800,end_time:1418399999}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'伙拼商品id'),
						array('field'=>'goods_id','desc'=>'商品id'),
						array('field'=>'category_id','desc'=>'分类id'),
						array('field'=>'production_id','desc'=>'产地id'),
						array('field'=>'name','desc'=>'伙拼商品名称'),
						array('field'=>'description','desc'=>'伙拼商品描述'),
						array('field'=>'price','desc'=>'伙拼商品价格'),
						array('field'=>'min_price','desc'=>'伙拼商品最低价格'),
						array('field'=>'min_price_spec','desc'=>'伙拼商品最低价格对应的规格'),
						array('field'=>'real_price','desc'=>'伙拼商品实际价格'),
						array('field'=>'image','desc'=>'伙拼商品图片'),
						array('field'=>'moq_spec','desc'=>'伙拼商品起拼规格'),
						array('field'=>'sale_count','desc'=>'伙拼商品销售数量'),
						array('field'=>'sale_spec','desc'=>'伙拼商品销售规格'),
						array('field'=>'sale_total_count','desc'=>'伙拼商品总销售数量'),
						array('field'=>'sale_total_spec','desc'=>'伙拼商品总销售规格'),
						array('field'=>'start_time','desc'=>'伙拼商品开始日期'),
						array('field'=>'end_time','desc'=>'伙拼商品结束日期')
					)
			),
	)		
);

//购物车相关接口
$apis[] = array(
	'category'=>'购物车相关接口',
	'api'=>array(
			'Cart/addToCart'=>array(
					'desc' => '商品加入购物车',
					'type'=>'POST',
					'field'=>array(
						'goods_id'=>array('desc'=>'商品ID'),
						'goods_qty'=>array('desc'=>'商品数量'),
						'delivery_time'=>array('desc'=>'配送日期20141111')
					),
					'response'=>'{"status":1,"info":"商品加入购物车成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表商品加入购物车成功，0代表商品加入购物车失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Cart/batchAddToCart'=>array(
					'desc' => '商品批量加入购物车',
					'type'=>'POST',
					'field'=>array(
						'goods_id'=>array('desc'=>'商品ID,商品ID'),
						'goods_qty'=>array('desc'=>'商品数量,商品数量'),
						'delivery_time'=>array('desc'=>'配送日期20141111,配送日期20141111')
					),
					'response'=>'{"status":1,"info":"商品批量加入购物车成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表商品批量加入购物车成功，0代表商品批量加入购物车失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Cart/getCartList'=>array(
					'desc' => '获取购物车商品',
					'type'=>'POST',
					'field'=>array(
					),
					'response'=>'{"status":1,"info":"","data":{"data":[{"cart_id":"3","goods_id":"1","goods_name":"\u8461\u8404","price":"10.00","number":"7","image":"image","subtotal":"70.00"}],"total":70,"total_goods_qty":7}}',
					'res_field'=>array(
						array('field'=>'data','desc'=>'cart_id：购物车ID，goods_id：商品ID，goods_name：商品名，price：价格，number：商品数量，image：商品图片，subtotal：小计'),
						array('field'=>'total','desc'=>'订单总金额'),
						array('field'=>'total_goods_qty','desc'=>'订单商品总数')
					)
			),
			'Cart/updateCart'=>array(
					'desc' => '更新购物车商品数量',
					'type'=>'POST',
					'field'=>array(
						'cart_id'=>array('desc'=>'购物车ID'),
						'goods_qty'=>array('desc'=>'商品数量')
					),
					'response'=>'{"status":1,"info":"商品加入购物车成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表商品加入购物车成功，0代表商品加入购物车失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Cart/deleteCart'=>array(
					'desc' => '删除购物车商品',
					'type'=>'POST',
					'field'=>array(
						'cart_id'=>array('desc'=>'购物车ID')
					),
					'response'=>'{"status":1,"info":"删除购物车商品成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表删除购物车商品成功，0代表删除购物车商品失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Cart/emptyCart'=>array(
					'desc' => '清空购物车商品',
					'type'=>'POST',
					'field'=>array(
					),
					'response'=>'{"status":1,"info":"清空购物车商品成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表清空购物车商品成功，0代表清空购物车商品失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			)
	)		
);

//订单相关接口
$apis[] = array(
	'category'=>'订单相关接口',
	'api'=>array(
			'Order/getOrderById'=>array(
					'desc' => '根据订单ID获取订单',
					'type'=>'GET',
					'field'=>array(
						'id'=>array('desc'=>'订单id')
					),
					'response'=>'',
					'res_field'=>array(
						array('field'=>'id','desc'=>'订单id'),
						array('field'=>'combine_pay_no','desc'=>'合并支付号'),
						array('field'=>'order_no','desc'=>'订单编号'),
						array('field'=>'member_id','desc'=>'会员ID'),
						array('field'=>'order_status','desc'=>'订单状态：created待付款，payed已付款，待发货，shipped已发货，received已收货，canceled已取消，refund已退款，refund_appiled申请退款'),
						array('field'=>'shipping_status','desc'=>'发货状态：0未发货，1已发货'),
						array('field'=>'shipping_no','desc'=>'快递单号'),
						array('field'=>'shippint_time','desc'=>'发货时间'),
						array('field'=>'pay_status','desc'=>'付款状态：0未付款，1已付款'),
						array('field'=>'consignee','desc'=>'收货人'),
						array('field'=>'province_id','desc'=>'省份ID'),
						array('field'=>'city_id','desc'=>'城市ID'),
						array('field'=>'area_id','desc'=>'区域ID'),
						array('field'=>'address','desc'=>'详细地址'),
						array('field'=>'tel','desc'=>'电话'),
						array('field'=>'mobile','desc'=>'手机'),
						array('field'=>'pay_method','desc'=>'付款方式'),
						array('field'=>'goods_amount','desc'=>'商品的总金额'),
						array('field'=>'order_amount','desc'=>'订单应付总金额'),
						array('field'=>'payed_amount','desc'=>'支付总金额'),
						array('field'=>'shipping_fee','desc'=>'配送费用'),
						array('field'=>'invoice_title','desc'=>'发票抬头'),
						array('field'=>'confirm_time','desc'=>'确认时间'),
						array('field'=>'note','desc'=>'管理员备注'),
						array('field'=>'buyer_note','desc'=>'买家留言'),
						array('field'=>'pay_time','desc'=>'付款时间'),
						array('field'=>'order_type','desc'=>'订单类型：normal普通订单，group伙拼订单'),
						array('field'=>'expire_time','desc'=>'过期时间'),
						array('field'=>'create_time','desc'=>'生成订单时间'),
						array('field'=>'is_show','desc'=>'0：用户删除订单，1：默认显示')
					)
			),
			'Order/getOrderByOrderNo'=>array(
					'desc' => '根据订单编号获取订单',
					'type'=>'GET',
					'field'=>array(
						'order_no'=>array('desc'=>'订单编号')
					),
					'response'=>'',
					'res_field'=>array(
						array('field'=>'id','desc'=>'订单id'),
						array('field'=>'combine_pay_no','desc'=>'合并支付号'),
						array('field'=>'order_no','desc'=>'订单编号'),
						array('field'=>'member_id','desc'=>'会员ID'),
						array('field'=>'order_status','desc'=>'订单状态：created待付款，payed已付款，待发货，shipped已发货，received已收货，canceled已取消，refund已退款，refund_appiled申请退款'),
						array('field'=>'shipping_status','desc'=>'发货状态：0未发货，1已发货'),
						array('field'=>'shipping_no','desc'=>'快递单号'),
						array('field'=>'shippint_time','desc'=>'发货时间'),
						array('field'=>'pay_status','desc'=>'付款状态：0未付款，1已付款'),
						array('field'=>'consignee','desc'=>'收货人'),
						array('field'=>'province_id','desc'=>'省份ID'),
						array('field'=>'city_id','desc'=>'城市ID'),
						array('field'=>'area_id','desc'=>'区域ID'),
						array('field'=>'address','desc'=>'详细地址'),
						array('field'=>'tel','desc'=>'电话'),
						array('field'=>'mobile','desc'=>'手机'),
						array('field'=>'pay_method','desc'=>'付款方式'),
						array('field'=>'goods_amount','desc'=>'商品的总金额'),
						array('field'=>'order_amount','desc'=>'订单应付总金额'),
						array('field'=>'payed_amount','desc'=>'支付总金额'),
						array('field'=>'shipping_fee','desc'=>'配送费用'),
						array('field'=>'invoice_title','desc'=>'发票抬头'),
						array('field'=>'confirm_time','desc'=>'确认时间'),
						array('field'=>'note','desc'=>'管理员备注'),
						array('field'=>'buyer_note','desc'=>'买家留言'),
						array('field'=>'pay_time','desc'=>'付款时间'),
						array('field'=>'order_type','desc'=>'订单类型：normal普通订单，group伙拼订单'),
						array('field'=>'expire_time','desc'=>'过期时间'),
						array('field'=>'create_time','desc'=>'生成订单时间'),
						array('field'=>'is_show','desc'=>'0：用户删除订单，1：默认显示')
					)
			),
			'Order/getOrderList'=>array(
					'desc' => '获取用户所有订单',
					'type'=>'GET',
					'field'=>array(
						'order_type'=>array('desc'=>'订单类型：normal普通订单，group伙拼订单'),
						'order_status'=>array('desc'=>'订单状态：created待付款，payed待发货，shipped待收货')
					),
					'response'=>'',
					'res_field'=>array(
					)
			),
			'Order/getOrderStatusCount'=>array(
					'desc' => '获取用户订单各状态统计',
					'type'=>'GET',
					'field'=>array(
					),
					'response'=>'{"status":1,"info":"","data":{"order_num":1,"created_num":1,"payed_num":0,"shipped_num":0}}',
					'res_field'=>array(
						array('field'=>'order_num','desc'=>'所有订单数'),
						array('field'=>'created_num','desc'=>'待付款订单数'),
						array('field'=>'payed_num','desc'=>'待发货订单数'),
						array('field'=>'shipped_num','desc'=>'待收货订单数')
					)
			),
			'Order/addOrder'=>array(
					'desc' => '提交订单',
					'type'=>'POST',
					'field'=>array(
						'address_id'=>array('desc'=>'收货地址ID'),
						'order_type'=>array('desc'=>'订单类型'),
						'buyer_note'=>array('desc'=>'买家备注')
					),
					'response'=>'{"status":1,"info":"提交订单成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表提交订单成功，0代表提交订单失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Order/deleteOrder'=>array(
					'desc' => '删除订单',
					'type'=>'POST',
					'field'=>array(
						'id'=>array('desc'=>'订单id')
					),
					'response'=>'{"status":1,"info":"删除订单成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表删除订单成功，0代表删除订单失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Order/confirmOrder'=>array(
					'desc' => '确认收货',
					'type'=>'POST',
					'field'=>array(
						'id'=>array('desc'=>'订单id')
					),
					'response'=>'{"status":1,"info":"确认收货成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表确认收货成功，0代表确认收货失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			)
	)		
);

//代金券相关接口
$apis[] = array(
	'category'=>'代金券相关接口',
	'api'=>array(
			'Coupon/addCoupon'=>array(
					'desc' => '添加一张代金券',
					'type'=>'POST',
					'field'=>array(
						'code'=>array('desc'=>'券号')
					),
					'response'=>'{"status":1,"info":"添加代金券成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表添加代金券成功，0代表添加代金券失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Coupon/getCouponById'=>array(
					'desc' => '根据代金券ID获取一张代金券',
					'type'=>'GET',
					'field'=>array(
						'id'=>array('desc'=>'代金券id')
					),
					'response'=>'',
					'res_field'=>array(
						array('field'=>'id','desc'=>'代金券id'),
						array('field'=>'code','desc'=>'券号'),
						array('field'=>'face_value','desc'=>'面额'),
						array('field'=>'start_time','desc'=>'可用开始时间'),
						array('field'=>'end_time','desc'=>'可用结束时间'),
						array('field'=>'create_time','desc'=>'创建时间'),
						array('field'=>'coupon_type_id','desc'=>'优惠券类型ID'),
						array('field'=>'min_use_value','desc'=>'最小使用金额'),
						array('field'=>'use_mid','desc'=>'使用者mid')
					)
			),
			'Coupon/getCouponByCode'=>array(
					'desc' => '根据券号获取一张代金券',
					'type'=>'GET',
					'field'=>array(
						'code'=>array('desc'=>'券号')
					),
					'response'=>'',
					'res_field'=>array(
						array('field'=>'id','desc'=>'代金券id'),
						array('field'=>'code','desc'=>'券号'),
						array('field'=>'face_value','desc'=>'面额'),
						array('field'=>'start_time','desc'=>'可用开始时间'),
						array('field'=>'end_time','desc'=>'可用结束时间'),
						array('field'=>'create_time','desc'=>'创建时间'),
						array('field'=>'coupon_type_id','desc'=>'优惠券类型ID'),
						array('field'=>'min_use_value','desc'=>'最小使用金额'),
						array('field'=>'use_mid','desc'=>'使用者mid')
					)
			),
			'Coupon/getCouponList'=>array(
					'desc' => '获取用户所有代金券',
					'type'=>'GET',
					'field'=>array(
						'coupon_status'=>array('desc'=>'代金券状态：use可用，used已使用，exceed已过期')
					),
					'response'=>'',
					'res_field'=>array(
					)
			),
			'Coupon/getCouponStatusCount'=>array(
					'desc' => '获取用户代金券各状态统计',
					'type'=>'GET',
					'field'=>array(
					),
					'response'=>'{"status":1,"info":"","data":{"coupon_num":0,"use_num":0,"used_num":0,"exceed_num":0}}',
					'res_field'=>array(
						array('field'=>'coupon_num','desc'=>'所有代金券数'),
						array('field'=>'use_num','desc'=>'可用代金券数'),
						array('field'=>'exceed_num','desc'=>'已过期代金券数'),
						array('field'=>'used_num','desc'=>'已使用代金券数')
					)
			),
			'Coupon/getExpireCouponNumber'=>array(
					'desc' => '获取即将过期的代金券数',
					'type'=>'GET',
					'field'=>array(
						'days'=>array('desc'=>'还剩n天过期')
					),
					'response'=>'',
					'res_field'=>array(
					)
			)
	)		
);
	
return $apis;