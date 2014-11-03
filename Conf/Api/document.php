<?php
$apis = array();

$apis[] = array(
	'category'=>'用户登录',
	'api'=>array(
			'Public/checkLogin'=>array(
					'desc' => '用户登录',
					'field'=>array(
							'account'=>array('desc'=>'用户名'),
							'password'=>array('desc'=>'用户密码'),
							'entry_system'=>array('desc'=>'App版本信息'),
							'device_info'=>array('desc'=>'设备信息'),
					),
					'type'=>'POST',
					'response'=>'{"token":"qe61nj02mhusk7jllc2klaoa40","status":1,"info":"","data":{"id":1,"team_id":"2","region_id":"9","office_id":"3","account":"admin","screen_name":"admininistor","first_name":"admin","last_name":"admin","avatar":"Upload\/Avatar\/2014\/07\/31\/1406789746897270.jpg","quote":"Art of the Possible t","employee_no":"9899","email":"jack@163.com","country":"China","telephone":"18666297289","last_login_time":"1406858378","login_count":"3532","area":"China","max_timeline_id":"0","update_time":"0","create_time":"1403256987","status":"1","parent_id":"2","level":"0","position":"Manager","deleted":"0","team":{"id":"2","region_id":"9","office_id":"3","leader_uid":"1","name":"tes","avatar":"","update_time":"1234","create_time":"222","deleted":"0"},"region_name":"Asia","office_name":"SCRO"}}',
					'res_field'=>array(
						array('field'=>'id','desc'=>'用户ID'),
						array('field'=>'region_id','desc'=>'用户所在地区的ID'),
						array('field'=>'office_id','desc'=>'用户所在Office的ID'),
						array('field'=>'team_id','desc'=>'用户所在Team的ID'),
						array('field'=>'account','desc'=>'用户登录名'),
						array('field'=>'screen_name','desc'=>'网名'),
						array('field'=>'first_name','desc'=>'名字'),
						array('field'=>'last_name','desc'=>'姓'),
						array('field'=>'avatar','desc'=>'头像地址'),
						array('field'=>'employee_no','desc'=>'工号'),
						array('field'=>'email','desc'=>'Email'),
						array('field'=>'country','desc'=>'国家'),
						array('field'=>'telephone','desc'=>'电话'),
						array('field'=>'last_login_time','desc'=>'最后登录时间戳'),
						array('field'=>'login_count','desc'=>'登录次数'),
						array('field'=>'area','desc'=>'区域'),
						array('field'=>'create_time','desc'=>'账号注册时间戳'),
						array('field'=>'token','desc'=>'登录成功后的Token'),
							
						array('field'=>'team.*','desc'=>'所在team的信息'),
					)
			),
			'Public/logout'=>array(
					'desc' => '退出登录',
					'response'=>'{"status":1,"info":"","data":"Logout Successfully"}',
					'res_field'=>array(
							array('field'=>'status','desc'=>'1代表返回成功，0说明失败')
					)
			),
	 ),
);

$apis[] = array(
	'category'=>'Observation && Inspection',
	'api'=>array(
			'Inspection/my'=>array(
					'desc' => '统计我的Inspection',
					'field'=>array(
						'type'=>array('desc'=>'类型，有type=week(当前周),type=month(当前月),type=day(今天).默认为day'),
					    
						'start'=>array('desc'=>'开始时间戳'),
						'end'=>array('desc'=>'结束时间戳'),
							
						'uid'=>array('desc'=>'用户ID'),
						'serial_no'=>array('desc'=>'Serial NO.'),
						'item_no'=>array('desc'=>'Item NO.'),
						'vendor_no'=>array('desc'=>'Vendor NO.'),
                        'team_id'=>array('desc'=>'Team ID'),
						'office_id'=>array('desc'=>'Office ID'),
						'from_manager_filter'=>array('desc'=>'是否来自Manager View Filter(0不是,1是),默认0'),
						'kw'=>array('desc'=>'关键字搜索'),
						'inspection_position'=>array('desc'=>'Packing,Finished Goods Warehouse ect.'),
						
						
					),
					'response'=>'{"status":1,"info":"","data":{"inspection_goal":0,"reject_quantity_goal":12,"reject_rate_goal":100,"inspection_company_goal":20,"reject_quantity_company_goal":12,"reject_rate_company_goal":100,"inspection":6,"reject_quantity":6,"reject_rate":100}}',
					'res_field'=>array(
							array('field'=>'inspection_goal','desc'=>'我的目标-inspection'),
							array('field'=>'reject_quantity_goal','desc'=>'reject quantity基数'),
							array('field'=>'reject_rate_goal','desc'=>'一般为100，Reject Rate基数'),
							
							array('field'=>'inspection','desc'=>'我当前的inspection'),
							array('field'=>'reject_quantity','desc'=>'我当前的reject_quantity'),
							array('field'=>'reject_rate','desc'=>'无%,reject_quantity/inspection'),
							
							array('field'=>'inspection_company_goal','desc'=>'公司给我定的inspection目标'),
							array('field'=>'reject_rate_company_goal','desc'=>'一般为100，公司定的reject rate基数'),
							array('field'=>'reject_quantity_company_goal','desc'=>'reject quantity基数'),
					 )
			),
			'Inspection/team'=>array(
					'desc' => '统计团队的Inspection',
					'field'=>array(
						'type'=>array('desc'=>'类型，有type=week(当前周),type=month(当前月),type=day(今天).默认为day'),
					     
						 'start'=>array('desc'=>'开始时间戳'),
						 'end'=>array('desc'=>'结束时间戳'),
							
                        'uid'=>array('desc'=>'用户ID'),
                        'team_id'=>array('desc'=>'Team ID'),
						'serial_no'=>array('desc'=>'Serial'),
						'item_no'=>array('desc'=>'Item NO.'),
						'vendor_no'=>array('desc'=>'Vendor NO.'),
						'kw'=>array('desc'=>'关键字搜索'),
							
						'inspection_position'=>array('desc'=>'Finished Goods Warehouse ect.'),
					),
					'response'=>'{"status":1,"info":"","data":{"inspection_goal":0,"reject_quantity_goal":20,"reject_rate_goal":100,"inspection_company_goal":30,"reject_quantity_company_goal":20,"reject_rate_company_goal":100,"inspection":6,"reject_quantity":6,"reject_rate":100}}',
					'res_field'=>array(
							array('field'=>'inspection_goal','desc'=>'我团队的目标-inspection'),
							array('field'=>'reject_quantity_goal','desc'=>'reject quantity基数'),
							array('field'=>'reject_rate_goal','desc'=>'一般为100，Reject Rate基数'),
							
							array('field'=>'inspection','desc'=>'我团队当前的inspection'),
							array('field'=>'reject_quantity','desc'=>'我团队当前的reject_quantity'),
							array('field'=>'reject_rate','desc'=>'无%,reject_quantity/inspection'),
							
							array('field'=>'inspection_company_goal','desc'=>'公司给我团队定的inspection目标'),
							array('field'=>'reject_rate_company_goal','desc'=>'一般为100，公司给我团队的reject rate基数'),
							array('field'=>'reject_quantity_company_goal','desc'=>'reject quantity基数'),
					)
			),
			'Inspection/addInspection'=>array(
					'type'=>'POST',
					'desc' => '添加Inspection',
					'field'=>array(
							'item_no'=>array('desc'=>'Item Number'),
							'serial_no'=>array('desc'=>'Serial Number'),
							'promote_scope'=>array('desc'=>'Scope of Promote.有(region,office,team,no promote)三个值'),
							'construction'=>array('desc'=>'Construction,3,2,1'),
							'color'=>array('desc'=>'Color,3,2,1'),
							'packing'=>array('desc'=>'packing,3,2,1'),
							'entry_system'=>array('desc'=>'如IOS V1.0等字符,最大长度45'),
							'issue'=>array('desc'=>'json格式'),
							'create_time'=>array('desc'=>'创建时间'),
							
							'vendor_name'=>array('desc'=>'Vendor名字'),
							'inspection_position'=>array('desc'=>'Inspection of Position'),
					),
					'response'=>'{"status":1,"info":"","data":{"vendor_name":"YanChen","item_no":"MH370","serial_no":"B222","construction":"3","color":"1","packing":"1","promote_scope":"region","uid":1,"team_id":2,"create_time":1406778262,"update_time":1406778262,"reject":1,"promote_region":"9","promote_office":0,"content":"YanChen MH370 ABC B88 ABC B88","parent_id":125,"issue":[{"location":"ABC","code":"B88","type":"color","image":"","images":""},{"location":"ABC","code":"B88","type":"color","image":"","images":""}]}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'Inspection ID'),
					),
			),
			'Inspection/addObservation'=>array(
					'type'=>'POST',
					'desc' => '添加Observation',
					'field'=>array(
							'item_no'=>array('desc'=>'Item Number'),
							'description'=>array('desc'=>'Description'),
							'images'=>array('image','desc'=>'Upload pictures'),
							'entry_system'=>array('desc'=>'如IOS V1.0等字符,最大长度45'),
							'promote_scope'=>array('desc'=>'Scope of Promote.有(region,office,team,no promote)三个值'),
							'create_time'=>array('desc'=>'创建时间'),
							
							'vendor_name'=>array('desc'=>'Vendor名字'),
							'inspection_position'=>array('desc'=>'Inspection of Position'),
					),
					'response'=>'{"status":1,"info":"","data":{"ticket_no":"t55r","amount":"3455","time_spend":"55","description":"test desc","images":"Upload\/Observation\/2014\/05\/21\/1400637348569246.jpg","promote_region":"3","promote_office":"34","promote_scope":"office","uid":1,"team_id":"0","create_time":1400637374,"contact_id":29,"id":30}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'Observation ID'),
					),
					//'add'=>true,
			),
			
	),
);

$apis[] = array(
		'category'=>'Leaderboard',
		'api'=>array(
				'Leaderboard/index'=>array(
						'desc' => 'Individual Leaderboard',
						'field'=>array(
								'group'=>array('desc'=>'有team(团队)和individual(个人)两个值，默认为individual'),
								'time_span'=>array('desc'=>'按时间跨度查询，有day,week,month,all四个值，如果传其他值或空都默认为day'),
								'start'=>array('desc'=>'开始时间戳'),
								'end'=>array('desc'=>'结束时间戳'),
								'cate'=>array('desc'=>'按哪种分类查询，有region和office两个值,分别代表同一个Regional的排名，或同一个Office的排名，如果传其他值或空都默认为office'),
								'type'=>array('desc'=>'inspection,observation,all默认为inspection'),
								'page'=>array('desc'=>'页码'),
								
						),
						'res_field'=>array(
								array('field'=>'my.amount','desc'=>'我(或所在Team)当前的Individual'),
								array('field'=>'my.rank','desc'=>'我(或所在Team)当前所处的排名'),
								
								array('field'=>'下面Leaderboard为二维数组','desc'=>''),
								array('field'=>'leaderboard.team_id','desc'=>'Team的id'),
								array('field'=>'leaderboard.office_id','desc'=>'Team所在office的ID'),
								array('field'=>'leaderboard.region_id','desc'=>'Team所在地区的ID'),
								array('field'=>'leaderboard.amount','desc'=>'Team的总数'),
								array('field'=>'leaderboard.team.*','desc'=>'Team信息(包括名字，头像)，group为team时有值'),
								array('field'=>'leaderboard.user.*','desc'=>'用户信息(包括名字，头像等)，group为individual时有值'),
								
								array('field'=>'page','desc'=>'当前页'),
								array('field'=>'page_size','desc'=>'每页数量'),
								array('field'=>'page_sum','desc'=>'总页数'),
								array('field'=>'record_sum','desc'=>'总记录数'),
						),
				),
		)
);

$apis[] = array(
	'category'=>'微博相关接口',
	'api'=>array(
			'Timeline/index'=>array(
					'desc' => '拉取微博',
					'field'=>array(
							'uid'=>array('desc'=>'用户ID'),
							'team_id'=>array('desc'=>'Team ID'),
							'office_id'=>array('desc'=>'office id || office (WBOC)'),
							
							'item_no'=>array('desc'=>'item no (B505-57)'),
							
							'vendor_no'=>array('desc'=>'Vendor NO.'),
							
							'start'=>array('desc'=>'开始时间戳'),
							'end'=>array('desc'=>'结束时间戳'),
							
							'date'=>array('desc'=>'添加日期时间戳'),
							
							'kw'=>array('desc'=>'搜索关键字'),
							'max_id'=>array('desc'=>'摘取微博的最大的ID，即取得早于这个id的微博'),
							'since_id'=>array('desc'=>'取得ID大于since_id的微博，即取得晚于这个id的微博'),
							'page_size'=>array('desc'=>'单页返回的记录条数'),
							'page'=>array('desc'=>'返回结果的页码，默认为1'),
							'range'=>array('desc'=>'observation,inspection,news,training'),
							'type'=>array('desc'=>'my时取得自己的微博'),
							
							'inspection_position'=>array('desc'=>'inspection position'),
					),
					'response'=>'{"status":1,"info":"","data":[{"id":"4","uid":"1","post_id":"30","post_type":"inspection","content":"test desc","images":"Upload\/Observation\/2014\/05\/21\/1400637348569246.jpg","comments_count":"0","like_count":"0","sales_amount":"3455","deleted":"0","create_time":"1400637375","region_id":"0","office_id":"34","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":""}},{"id":"3","uid":"1","post_id":"26","post_type":"inspection","content":"test","images":"Upload\/Inspection\/2014\/05\/21\/1400637249678407.png","comments_count":"0","like_count":"0","sales_amount":"","deleted":"0","create_time":"1400637268","region_id":"0","office_id":"1","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":""}},{"id":"2","uid":"1","post_id":"24","post_type":"inspection","content":"554","images":"Upload\/Inspection\/2014\/05\/20\/1400595016595011.jpg","comments_count":"0","like_count":"0","sales_amount":"","deleted":"0","create_time":"1400595044","region_id":"0","office_id":"1","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":null}},{"id":"1","uid":"1","post_id":"22","post_type":"inspection","content":"asdfas","images":"","comments_count":"0","like_count":"0","sales_amount":"254","deleted":"0","create_time":"1400567224","region_id":"0","office_id":"85","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":""}}]}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'uid','desc'=>'发布的用户ID'),
							array('field'=>'post_id','desc'=>'observation,inspection,news,training的id'),
							array('field'=>'post_type','desc'=>'observation,inspection,news,training'),
							array('field'=>'content','desc'=>'微博内容'),
							array('field'=>'images','desc'=>'微博图片，多张图片用英文逗号(,)分隔'),
							array('field'=>'comments_count','desc'=>'评论数'),
							array('field'=>'comments.*','desc'=>'评论'),
							array('field'=>'like_count','desc'=>'赞的数量'),
							array('field'=>'create_time','desc'=>'微博创建时间的时间戳'),
							array('field'=>'user.*','desc'=>'发布微博的用户信息'),
							
							array('field'=>'issue.*','desc'=>'Inspection问题列表'),
							array('field'=>'vendor_name','desc'=>'Vendor Name'),
							array('field'=>'item_no','desc'=>'Item Number'),
							array('field'=>'serial_no','desc'=>'Serial Number'),
					),
			),
			'Timeline/detail'=>array(
					'desc' => '取得单个微博详细信息',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
							'comment_count' => array('desc'=>'单页返回的记录条数，默认为10'),
					),
					'response'=>'{"status":1,"info":"","data":{"id":"1","uid":"1","post_id":"22","post_type":"inspection","content":"asdfas","images":"","comments_count":"0","like_count":"0","sales_amount":"254","deleted":"0","create_time":"1400567224","region_id":"0","office_id":"85","user":{"id":"1","team_id":"0","region_id":"0","office_id":"0","account":"admin","screen_name":"admin","first_name":"admin","last_name":"admin","password":"81dc9bdb52d04dc20036dbd8313ed055","avatar":"","quotes":"","employee_no":"","email":"","country":"","telephone":"","last_login_time":"1400632756","login_count":"0","area":" ","max_timeline_id":"0","update_time":"0","create_time":"0","status":"1"},"comments":[{"id":"0","timeline_id":"1","uid":"1","to_uid":"0","content":"test","create_time":"1400583655","deleted":"0","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":""}}]}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'uid','desc'=>'发布的用户ID'),
							array('field'=>'post_id','desc'=>'observation或inspection的id'),
							array('field'=>'post_type','desc'=>'observation或inspection'),
							array('field'=>'content','desc'=>'微博内容'),
							array('field'=>'images','desc'=>'微博图片，多张图片用英文逗号(,)分隔'),
							array('field'=>'comments_count','desc'=>'评论数'),
							array('field'=>'like_count','desc'=>'赞的数量'),
							array('field'=>'create_time','desc'=>'微博创建时间的时间戳'),
							array('field'=>'user.*','desc'=>'发布微博的用户信息'),
							array('field'=>'comment.*','desc'=>'评论信息'),
							array('field'=>'issue.*','desc'=>'Inspection问题列表'),
							array('field'=>'vendor_name','desc'=>'Vendor Name'),
							array('field'=>'item_no','desc'=>'Item Number'),
							array('field'=>'serial_no','desc'=>'Serial Number'),
					),
			),
			'Timeline/listComment'=>array(
					'desc' => '取得微博的评论内容',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
							'max_id'=>array('desc'=>'评论的最大的ID，即取得早于这个id的评论'),
							'since_id'=>array('desc'=>'取得ID大于since_id的评论，即取得晚于这个id的评论'),
							'page_size'=>array('desc'=>'单页返回的记录条数'),
							'page'=>array('desc'=>'页码，默认为1'),
					),
					'response'=>'{"status":1,"info":"","data":[{"id":"0","timeline_id":"1","uid":"1","to_uid":"0","content":"test","create_time":"1400583655","deleted":"0","user":{"id":"1","first_name":"admin","last_name":"admin","account":"admin","avatar":""}}]}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'uid','desc'=>'评论的用户ID'),
							array('field'=>'to_uid','desc'=>'评论给用户对应的用户的ID，为0则评论微博'),
							array('field'=>'content','desc'=>'评论内容'),
							array('field'=>'create_time','desc'=>'发表评论的时间的时间戳'),
							array('field'=>'user.*','desc'=>'发布评论的用户信息'),
							array('field'=>'to_user.*','desc'=>'收到这条评论的用户信息'),
					),
			),
			'Timeline/comment'=>array(
					'desc' => '评论微博，评论给用户',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
							'to_uid'=>array('desc'=>'评论给谁，不填写默认为0'),
							'content'=>array('desc'=>'评论内容'),
					),
					'type'=>'POST',
					'response'=>'{"status":1,"info":"","data":{"id":1,"timeline_id":1,"comment_count":1}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							//array('field'=>'uid','desc'=>'评论的用户ID'),
							array('field'=>'to_uid','desc'=>'评论给用户对应的用户的ID，为0则评论微博'),
							array('field'=>'content','desc'=>'评论内容'),
							array('field'=>'create_time','desc'=>'发表评论的时间的时间戳'),
							array('field'=>'comment_count','desc'=>'当前微博评论的数量'),
					),
			),
			'Timeline/delComment'=>array(
					'desc' => '删除评论',
					'field'=>array(
							'id'=>array('desc'=>'评论的ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"id":1,"timeline_id":"1","comment_count":-1}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'timeline_id','desc'=>'微博ID'),
							array('field'=>'comment_count','desc'=>'当前微博评论的数量'),
					),
			),
			'Timeline/like'=>array(
					'desc' => '赞',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"timeline_id":2,"id":2,"like_count":1}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'timeline_id','desc'=>'微博ID'),
							array('field'=>'like_count','desc'=>'当前赞的数量'),
					)
			),
			'Timeline/cancelLike'=>array(
					'desc' => '取消赞',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"timeline_id":2,"like_count":0}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'timeline_id','desc'=>'微博ID'),
							array('field'=>'like_count','desc'=>'当前赞的数量'),
					)
			),
			
			'Timeline/delete'=>array(
					'desc' => '删除微博',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"id":1}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
					)
			),
			'Timeline/updateDetail'=>array(
					'desc' => '取得Inspection&Observation的详细信息',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"id":1}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
					)
			),
			'Timeline/update'=>array(
					'desc' => '取得Inspection&Observation的详细信息',
					'field'=>array(
							'timeline_id'=>array('desc'=>'微博的ID'),
							'description'=>array('desc'=>'Observation的Description'),
							'images'=>array('image','desc'=>'Observation的Upload pictures'),
							'promote_scope'=>array('desc'=>'Scope of Promote.有(office,region,no promote)三个值'),
							 
							'vendor_name'=>array('desc'=>'Inspection的Vendor Name'),
							'item_no'=>array('desc'=>'Inspection的Item Number'),
							'serial_no'=>array('desc'=>'Inspection的Serial Number'),
							'vendor_name'=>array('desc'=>'Inspection的Vendor Name'),
							'construction'=>array('desc'=>'Inspection的Construction,3,2,1'),
							'color'=>array('desc'=>'Inspection的Color,3,2,1'),
							'packing'=>array('desc'=>'Inspection的packing,3,2,1'),
							'issue'=>array('desc'=>'Inspection的issues,json格式'),
							
					),
					'add'=>true,
					'response'=>'{"status":1,"info":"","data":{"time_spend":"","description":"","images":"","promote_region":"","promote_office":"","promote_scope":"","uid":1,"team_id":"0","create_time":1400637293,"contact_id":27,"id":28}}',
						
					'type'=>'POST',
					//'response'=>'{"status":1,"info":"","data":{"id":1}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
					)
			),
	),
);

$apis[] = array(
		'category'=>'用户相关',
		'api'=>array(
				'User/update'=>array(
						'desc' => '保存用户资料',
						'type'=>'POST',
						'field' =>array(
								'screen_name'=>array('desc'=>'Screen Name'),
								'quote'=>array('desc'=>'Inspirational Quote'),
								'employee_no'=>array('desc'=>'Employee NO.'),
								'first_name'=>array('desc'=>'First Name'),
								'last_name'=>array('desc'=>'Last Name'),
								'email'=>array('desc'=>'Email'),
								'region_id'=>array('desc'=>'Region'),
								'office_id'=>array('desc'=>'Office'),
								
								'old_password'=>array('desc'=>'Old Password'),
								'password'=>array('desc'=>'New Password'),
								'retype_password'=>array('desc'=>'Retype Password'),
						),
						'add'=>true,
						'response'=>'{"status":1,"info":"","data":{"region_id":"1","office_id":"2","screen_name":"admin","first_name":"admin","last_name":"admin","password":"81dc9bdb52d04dc20036dbd8313ed055","quote":"12","employee_no":"B5525","email":"admin@wanvogfurniture.com"}}',
				),
				'User/uploadAvatar'=>array(
						'desc' => '上传头像',
						'type'=>'POST',
						'field' =>array(
							'avatar'=>array('file','desc'=>'Avatar'),
						),
						'response'=>'{"status":1,"info":"","data":{"id":1,"avatar":"Upload\/Avatar\/2014\/05\/22\/1400735488272074.jpg"}}',
						'res_field'=>array(
								array('field'=>'id','desc'=>'用户UID'),
								array('field'=>'avatar','desc'=>'头像地址'),
						)
				),
				'User/getList'=>array(
						'desc' => '获取所有用户列表',
						'type'=>'POST',
						'response'=>'{"token":"qe61nj02mhusk7jllc2klaoa40","status":1,"info":"","data":[{"id":1,"team_id":"2","region_id":"9","office_id":"3","account":"admin","screen_name":"admininistor","first_name":"admin","last_name":"admin","avatar":"Upload\/Avatar\/2014\/07\/31\/1406789746897270.jpg"},{"id":1,"team_id":"2","region_id":"9","office_id":"3","account":"admin2","screen_name":"admininistor2","first_name":"admin2","last_name":"admin2","avatar":"Upload\/Avatar\/2014\/07\/31\/1406789746897270.jpg"}]}',
						'res_field'=>array(
								array('field'=>'id','desc'=>'用户UID'),
								array('field'=>'team_id','desc'=>'Team ID'),
								array('field'=>'region_id','desc'=>'Region ID'),
								array('field'=>'office_id','desc'=>'Office ID'),
								array('field'=>'account','desc'=>'帐户名'),
								array('field'=>'sreen_name','desc'=>'用户昵称'),
								array('field'=>'first_name','desc'=>'用户First Name'),
								array('field'=>'last_name','desc'=>'用户Last Name'),
								array('field'=>'avatar','desc'=>'头像地址'),
						)
				),
		),
);


$apis[] = array(
		'category'=>'Contact',
		'api'=>array(
				'Contact/index'=>array(
						'desc' => '我的Contact',
						'field'=>array(
								/*'max_id'=>array('desc'=>'摘取微博的最大的ID，即取得早于这个id的微博'),
								'since_id'=>array('desc'=>'取得ID大于since_id的微博，即取得晚于这个id的微博'),*/
								'page_size'=>array('desc'=>'单页返回的记录条数'),
								'page'=>array('desc'=>'返回结果的页码，默认为1'),
						),
						'res_field'=>array(
								array('field'=>'id','desc'=>'ID'),
								array('field'=>'name','desc'=>'Name'),
								/* array('field'=>'gender','desc'=>"有三个值'unknow','female','male'"), */
								array('field'=>'page','desc'=>'当前页'),
								array('field'=>'page_size','desc'=>'每页总数'),
								array('field'=>'page_sum','desc'=>'总页数'),
								array('field'=>'record_sum','desc'=>'总记录数'),
						),
						'response'=>'{"page":1,"page_size":10,"page_sum":1,"record_sum":"2","status":1,"info":"","data":[{"id":"34","owner_uid":"1","name":"test","gender":"0","avatar":"","telephone":"123456","address":"Beijin","priority":"","create_time":"1400664639","preferred_style":"1","preferred_collection":"test collection","deleted":"0"},{"id":"33","owner_uid":"1","name":"sdfsa","gender":"0","avatar":"","telephone":"sadfas","address":"sdsafa","priority":"","create_time":"1400664571","preferred_style":"1","preferred_collection":"dasdfas","deleted":"0"}]}',
				),
				'Contact/insert'=>array(
						'desc'=>'添加 Contact',
						'type'=>'POST',
						'field'=>array(
							'avatar'=>array('image','desc'=>'Contact Avatar'),
							'name'=>array('desc'=>'Name'),
							'description'=>array('desc'=>'Description'),
							'telephone'=>array('desc'=>'Phone'),
						),
						'response'=>'{"status":1,"info":"","data":{"name":"test","telephone":"123456","description":"Beijin","owner_uid":1,"create_time":1400664639,"avatar":"","id":34}}',
				),
				'Contact/update'=>array(
						'desc'=>'编辑 Contact',
						'type'=>'POST',
						'field'=>array(
								'id'=>array('desc'=>'ID'),
								'avatar'=>array('image','desc'=>'Contact Avatar'),
								'name'=>array('desc'=>'Name'),
								'description'=>array('desc'=>'Description'),
								'telephone'=>array('desc'=>'Phone'),
						),
						'add'=>true,
						'response'=>'{"status":1,"info":"","data":{"name":"test","telephone":"123456","address":"Beijin","priority":"Height","preferred_style":"1","preferred_collection":"test collection","owner_uid":1,"create_time":1400664639,"avatar":"","id":34}}',
				),
				
				'Contact/delete'=>array(
						'desc'=>'删除 Contact',
						'field'=>array(
								'id'=>array('desc'=>'ID'),
						),
						'response'=>'',
				),
		),
);

$apis[] = array(
	'category'=>'目标相关接口',
	'api'=>array(
		'Goal/my'=>array(
			'desc' => '取得个人和团队的目标',
			'response'=> '{"status":1,"info":"","data":{"observation":"21","inspection":"333","sales":"234.00","observation_company":"124","inspection_company":"2547","sales_company":"1254.00","observation_team":"2","inspection_team":"3","sales_team":"4","observation_company_team":"25","inspection_company_team":"54","sales_company_team":"544","manage_team":1}}',
		    'res_field'=>array(
					array('field'=>'inspection','desc'=>'我的目标，inspection'),
					array('field'=>'inspection_company','desc'=>'公司给我设的目标，inspection'),
		    		
					array('field'=>'inspection_team','desc'=>'我所在团队的目标，inspection'),
					array('field'=>'inspection_company_team','desc'=>'公司给我所在团队设的目标，inspection'),
 
					array('field'=>'manage_team','desc'=>'是否有team的目标的管理权限，1代表有，0代表没有'),
			)
		),
		'Goal/setting'=>array(
			'desc' => '个人目标设定',
			'type'=>'POST',
			'field'=>array(
				'inspection'=>array('desc'=>'设置我的Inspection'),
				'inspection_team'=>array('desc'=>'设置Team 的Inspection，无权限时此参数无效'),
			),
		),
	),
);

$apis[] = array(
	'category'=>'Schedule',
	'api'=>array(
			'Schedule/index'=>array(
					'desc' => 'Schedule搜索',
					'field' =>array(
						'page_size'=>array('desc'=>'单页返回的记录条数'),
						'page'=>array('desc'=>'返回结果的页码，默认为1'),
						'kw'=>array('desc'=>'搜索Schedule内容关键字'),
					),
			),
			'Schedule/insert'=>array(
					'desc'=>'添加Schedule',
					'type'=>'POST',
					'field' =>array(
						'description'=>array('desc'=>'Description'),
						'start'=>array('desc'=>'Start'),
						'finish'=>array('desc'=>'Finish'),
						'reminder'=>array('desc'=>'Reminder'),
						'contact_id'=>array('desc'=>'Contact'),
					),
					'response'=>'{"status":1,"info":"","data":{"description":"test","start":"2014-01-05 12:22:00","finish":"2014-06-05 12:22:00","reminder":"dadfad","uid":1,"create_time":1400746981,"id":3}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'description','desc'=>'Description'),
							array('field'=>'start','desc'=>'Start'),
							array('field'=>'finish','desc'=>'Finish'),
							array('field'=>'reminder','desc'=>'Reminder'),
							array('field'=>'uid','desc'=>'User ID'),
					)
			),
			
			'Schedule/update'=>array(
					'desc'=>'编辑Schedule',
					'type'=>'POST',
					'field' =>array(
							'id'=>array('desc'=>'ID'),
							'description'=>array('desc'=>'Description'),
							'start'=>array('desc'=>'Start'),
							'finish'=>array('desc'=>'Finish'),
							'reminder'=>array('desc'=>'Reminder'),
							'contact_id'=>array('desc'=>'Contact ID'),
					),
					'response'=>'{"status":1,"info":"","data":{"id":"4","description":"sdfsafas","start":1544444466,"finish":1566666666,"reminder":"6"}}',
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'description','desc'=>'Description'),
							array('field'=>'start','desc'=>'Start'),
							array('field'=>'finish','desc'=>'Finish'),
							array('field'=>'reminder','desc'=>'Reminder'),
							array('field'=>'uid','desc'=>'User ID'),
					)
			),
			'Schedule/delete'=>array(
					'desc'=>'Delete Schedule',
					'field' =>array(
							'id'=>array('desc'=>'Schedule的ID'),
					),
					'response'=>'{"status":1,"info":"","data":"Delete Successfully"}',
			),
			
			
			'Schedule/stat'=>array(
					'desc'=>'Schedule按日期统计',
					'field' =>array(
					    'month'=>array('desc'=>'2014-08'),
					),
			 ),
	),
);


$apis[] = array(
	'category'=>'Notification',
	'api'=>array(
			'Notification/index'=>array(
					'desc' => '取得我的通知',
					'response'=>'{"page":1,"page_size":30,"page_sum":1,"record_sum":"2","status":1,"info":"","data":[{"id":"2","uid":"1","notification_id":"2","read":"0","deleted":"0","to_id":"0","to_name":"all","target":"all","content":"I met a couple in office today, they talked with \r\nme for five minutes and place their order, you \r\nknow what, they are Mr & Mrs Smith\u2026\u2026","create_time":"1326548793"},{"id":"1","uid":"1","notification_id":"1","read":"0","deleted":"0","to_id":"1","to_name":"admin","target":"office","content":"Did he buy the sofa?","create_time":"1325698745"}]}',
					'field' =>array(
							'page_size'=>array('desc'=>'每页数量'),
							'type'=>array('desc'=>'1为评论消息，0为系统消息'),
					),
					
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'content','desc'=>'消息内容'),
							array('field'=>'uid','desc'=>'User ID'),
							array('field'=>'read','desc'=>'0代表未读，1代表已读'),
							array('field'=>'create_time','desc'=>'消息创建时间戳'),
					)
			),
			'Notification/comments'=>array(
					'desc' => '取得给我的评论',
					'response'=>'{"page":1,"page_size":30,"page_sum":1,"record_sum":"2","status":1,"info":"","data":[{"id":"2","uid":"1","notification_id":"2","read":"0","deleted":"0","to_id":"0","to_name":"all","target":"all","content":"I met a couple in office today, they talked with \r\nme for five minutes and place their order, you \r\nknow what, they are Mr & Mrs Smith\u2026\u2026","create_time":"1326548793"},{"id":"1","uid":"1","notification_id":"1","read":"0","deleted":"0","to_id":"1","to_name":"admin","target":"office","content":"Did he buy the sofa?","create_time":"1325698745"}]}',
					'field' =>array(
							'page_size'=>array('desc'=>'每页数量'),
					),
					'res_field'=>array(
							array('field'=>'id','desc'=>'ID'),
							array('field'=>'content','desc'=>'评论内容'),
							array('field'=>'uid','desc'=>'User ID'),
							array('field'=>'create_time','desc'=>'评论创建时间戳'),
					)
			),
	),
);




	
$apis[] = array(
	'category'=>'Challenge',
	'api'=>array(
			'Challenge/index'=>array(
					'desc' => '取得当前的Challenge',
					
					'res_field'=>array(
							/* array('field'=>'page','desc'=>'当前页'),
							array('field'=>'page_size','desc'=>'每页总数'),
							array('field'=>'page_sum','desc'=>'总页数'),
							array('field'=>'record_sum','desc'=>'总记录数'), */
							
							
							array('field'=>'start','desc'=>'挑战开始时间戳'),
							array('field'=>'end','desc'=>'挑战结束时间戳'),
							array('field'=>'range_type','desc'=>'challenge是按周，还是按天的还是按月。有day,week,month其中之一'),
							
							
							array('field'=>'inspection','desc'=>'要挑战的Inspection数'),
							array('field'=>'my_inspection','desc'=>'我的总记录数'),
							
							array('field'=>'top','desc'=>'当前排名前top位的数据'),
							array('field'=>'my','desc'=>'我当前的数量，inspection'),
			        ),
					'field'=>array(
							'scope'=>array('desc'=>'company,region,office,team,user,my'),
							'scope_id'=>array('desc'=>'如:1,2,3'),
                    ),
			),
			'Challenge/scope'=>array(
					'desc' => '取得当前用户的scope列表',
					'res_field'=>array(
						 array('field'=>'user','desc'=>'下属列表，包含自己'),
						 array('field'=>'manager','desc'=>'是否manager,返回true,false'),
					),
					'field'=>array(
						'scope'=>array('desc'=>'region,office,team,user,my'),
						'id'=>array('desc'=>'编辑时用'),
					)
			),
			'Challenge/insert'=>array(
					'desc'=>'添加Challenge',
					'type'=>'POST',
					'field' =>array(
							'scope_id'=>array('desc'=>'ID逗号分隔'),
							'scope'=>array('desc'=>'region,office,team,user,my'),
							'qty'=>array('desc'=>'QTY'),
							'start'=>array('desc'=>'开始时间戳'),
							'end'=>array('desc'=>'结束时间戳'),
					),
			),
			'Challenge/update'=>array(
					'desc'=>'编辑Challenge',
					'type'=>'POST',
					'field' =>array(
							'id'=>array('desc'=>'Challenge ID'),
							'scope_id'=>array('desc'=>'ID逗号分隔'),
							'scope'=>array('desc'=>'region,office,team,user,my'),
							'qty'=>array('desc'=>'QTY'),
							'start'=>array('desc'=>'开始时间戳'),
							'end'=>array('desc'=>'结束时间戳'),
					),
			),
			'Challenge/delete'=>array(
					'desc'=>'删除Challenge',
					'field' =>array(
							'id'=>array('desc'=>'Challenge ID'),
					),
			),
	),
);


$apis[] = array(
	'category'=>'列表数据接口',
	'api'=>array(
		'Data/index'=>array(
			'desc' => '相关数据',
			'field' =>array(
					'field'=>array('desc'=>'defectLocation,defectCode,region,office,team'),
			),
			'response'=>'{"status":1,"info":"","data":{"region":[{"id":"10","name":"tes","create_time":"2222"}],"office":[{"id":"7","name":"tes","region_id":"0","create_time":"0"}],"team":[{"id":"2","name":"tes","region_id":"9","office_id":"3","create_time":"222"}]}}',
		),
		'Data/defectLocation'=>array(
			'desc' => '取得Defect Location列表',
			'response'=>'{"status":1,"info":"","data":{"Loc 1":"Loc 1","Loc 2":"Loc 2","Loc 3":"Loc 3"}}',
		),
		'Data/defectCode'=>array(
			'desc' => '取得Defect Code列表',
			'response'=>'{"status":1,"info":"","data":{"Loc 1":"Loc 1","Loc 2":"Loc 2","Loc 3":"Loc 3"}}',
		),
		'Data/region'=>array(
			'desc' => '取得所有的区域',
			'response'=>'{"status":1,"info":"","data":[{"id":"1","name":"East","create_time":"1400234547"},{"id":"4","name":"test","create_time":"1401160998"},{"id":"2","name":"West","create_time":"1432546987"}]}',
		),
		'Data/office'=>array(
			'desc' => '取得店铺列表',
			'field'=>array(
			    'region_id'=>'区域（region表）ID'		
		    ),
			'response'=>'{"status":1,"info":"","data":[{"id":"7","name":"adfad","region_id":"1","create_time":""},{"id":"3","name":"dasdf","region_id":"1","create_time":""},{"id":"4","name":"dasdf","region_id":"1","create_time":""},{"id":"5","name":"dasdf","region_id":"1","create_time":""},{"id":"6","name":"dasdf","region_id":"1","create_time":""},{"id":"2","name":"dsadfa","region_id":"1","create_time":""},{"id":"1","name":"SCRO","region_id":"1","create_time":"1403256987"}]}',
		),
		'Data/team'=>array(
			'desc' => '取得Team列表',
			'response'=>'{"status":1,"info":"","data":[{"id":"2","name":"test","region_id":"1","office_id":"1","create_time":""},{"id":"1","name":"test Team","region_id":"4","office_id":"1","create_time":"1423569874"}]}',
			'field'=>array(
					'region_id'=>'区域（region表）ID',
					'office_id'=>'店铺（office表的）ID'
			),
		),
		'Data/vendor'=>array(
			'desc' => '取得Vendor列表',
			'response'=>'',
			'res_field'=>array(
					array('field'=>'id','desc'=>'vendor ID'),
					array('field'=>'name','desc'=>'vendor名称'),
					array('field'=>'vendor_no','desc'=>'Vendor No.'),
					
			)
         ),
		
		'Data/managerViewFilter'=>array(
			'desc' => '搜索参数',
			'type'=>'GET',
			'field'=>array(
				'uid'=>array('desc'=>'用户ID')
			),
			'response'=>'',
        ),	
			
	),
);
$apis[] = array(
	'category'=>'搜索接口',
	'api'=>array(
        'Search/index'=>array(
			'desc' => '取得日历相关数据',
			'field'=>array(
				'date'=>array('desc'=>'时间戳'),
				'contact_id'=>array('desc'=>'客户ID'),
			    'kw'=>array('desc'=>'关键字搜索'),
			    'region_id'=>array('desc'=>'地区ID'),
			    'office_id'=>array('desc'=>'Office ID'),
			    'team_id'=>array('desc'=>'Team的ID'),
			    'range'=>array('desc'=>'observation,inspection,news,training'),
			    'type'=>array('desc'=>'等于my时，只取得和我相关的数据'),
			    'page_size'=>array('desc'=>'每页返回数量，默认20'),
			    'page'=>array('desc'=>'页码'),
		    ),
        	'res_field'=>array(
        		 array('field'=>'news','desc'=>'news的数量'),
        		 array('field'=>'training','desc'=>'training的数量'),
        		 array('field'=>'observation','desc'=>'observation的数量'),
        		 array('field'=>'inspection','desc'=>'inspection的数量'),
        		 array('field'=>'schedule','desc'=>'schedule的数量'),
        		 array('field'=>'dataList','desc'=>'混合数据列表'),
			),
			'response'=>'{"status":1,"info":"","data":{"news":0,"training":"1","observation":"2","inspection":0,"schedule":"3","dataList":[{"id":"18","uid":"1","post_id":"34","post_type":"observation","content":"test","images":"","comments_count":"0","like_count":"0","sales_amount":"0.00","deleted":"0","create_time":"1404350262","region_id":"0","office_id":"1","promote_scope":"office","type":"observation"},{"id":"4","uid":"1","description":"test dddd","start":"1544444466","finish":"1566666666","reminder":"5","deleted":"0","create_time":"1404295681","type":"schedule"},{"id":"14","uid":"1","post_id":"31","post_type":"observation","content":"test update","images":"","comments_count":"0","like_count":"0","sales_amount":"0.00","deleted":"0","create_time":"1404263491","region_id":"1","office_id":"0","promote_scope":"region","type":"observation"},{"id":"1","add_uid":"1","date":"2014-07-01","title":"test 1 ","content":"ddddddddddd","images":"Upload\/tmp\/2014\/07\/02\/1404259604285892.jpg,Upload\/tmp\/2014\/07\/02\/1404259605913806.jpg,Upload\/tmp\/2014\/07\/02\/1404259606103320.jpg","type":"training","scope":"region","like_count":"0","comment_count":"0","deleted":"0","update_time":"1404259610","create_time":"1404259610"},{"id":"3","uid":"1","description":"test","start":"1400000000","finish":"1500000000","reminder":"0","deleted":"0","create_time":"1400746981","type":"schedule"},{"id":"2","uid":"1","description":"safda","start":"1400000000","finish":"1500000000","reminder":"0","deleted":"0","create_time":"1400742610","type":"schedule"}]}}',
		),
	),
);

$apis[] = array(
	'category'=>'文件上传',
	'api'=>array(
		'UploadFile/image'=>array(
			'desc' => '上传图片',
			'type'=>'POST',
			'field'=>array(
				'image'=>array('file','desc'=>'图片'),
			),
			'add'=>true,
		),
	),
);


$apis[] = array(
		'category'=>'App版本及日志记录',
		'api'=>array(
				'AppVersion/index'=>array(
					'desc' => '取得App版本描述信息',
					'response'=>'',
		        ),
				'AppVersion/getVersion'=>array(
						'desc' => '取得当前最新的App的版本信息',
						'field'=>array(
							'code'=>array('desc'=>'暂时有IOS或Android'),	
						),
						'res_field'=>array(
								array('field'=>'app_name','desc'=>'App名字'),
								array('field'=>'app_code','desc'=>'App代码，固定IOS或Android'),
								array('field'=>'app_version','desc'=>'App版本'),
								array('field'=>'data_version','desc'=>'数据版本'),
								array('field'=>'program_url','desc'=>'App地址'),
								array('field'=>'description','desc'=>'App描述'),
						),
						'response'=>'{"status":1,"info":"","data":{"id":"1","app_name":"Sales","app_code":"IOS","app_version":"1.0","data_version":"1.0","program_url":"http:\/\/test.test","description":"test","create_time":"1402651390","update_time":"1402651390"}}',
				),
				'AppLog/index'=>array(
						'desc' => '记录App运行过程中的日志',
						'type'=>'POST',
						'field'=>array(
							'token'=>array('desc'=>'用户登录后的token，非必须'),
							'phone_model'=>array('desc'=>'phone model'),
							'sys_version'=>array('desc'=>'app版本'),
							'screen_info'=>array('desc'=>'Screen Info'),
							'detail'=>array('desc'=>'错误详细信息'),
						),
						'response'=>'{"status":1,"info":"","data":{"phone_model":"when shut down","sys_version":"1.1","screen_info":"223*225","detail":"error detail","user_id":1,"username":"admin","create_time":1402910689,"id":1}}',
				),
		),
);
$apis[] = array(
		'category'=>'Location of Inspection',
		'api'=>array(
				'Location/save'=>array(
					'desc' => '保存用户选择的Location，Vendor信息',
					'type'=>'POST',
					'field'=>array(
						'vendor_name'=>array('desc'=>'Vendor名字'),
						'inspection_position'=>array('desc'=>'Inspection of Position'),
					),
					'response'=>'',
		        ),
				
				'Location/get'=>array(
						'desc' => '取得用户的Vendor信息，Position信息',
						'response'=>'{"status":1,"info":"","data":{"phone_model":"when shut down","sys_version":"1.1","screen_info":"223*225","detail":"error detail","user_id":1,"username":"admin","create_time":1402910689,"id":1}}',
				),
		),
);
$apis[] = array(
		'category'=>'系统设置相关',
		'api'=>array(
				'Covers/login'=>array(
					'desc' => '获取登陆界面封面名字',
					'type'=>'GET',
					'field'=>array(
						'start_date'=>array('desc'=>'开始日期（当前日期）,格式2014-09-12'),
						'country_code'=>array('desc'=>'2位的国家代码'),
					),
					'response'=>'',
		        ),
		),
);
	

//新的api接口开始
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
						array('field'=>'area_id','desc'=>'区号/县id'),
						array('field'=>'province_name','desc'=>'省份名称'),
						array('field'=>'city_name','desc'=>'城市名称'),
						array('field'=>'area_name','desc'=>'区号/县名称'),
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
							'province_name'=>array('desc'=>'省份名称'),
							'city_id'=>array('desc'=>'城市id'),
							'city_name'=>array('desc'=>'城市名称'),
							'area_id'=>array('desc'=>'区号/县id'),
							'area_name'=>array('desc'=>'区号/县名称'),
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
							'province_name'=>array('desc'=>'省份名称'),
							'city_id'=>array('desc'=>'城市id'),
							'city_name'=>array('desc'=>'城市名称'),
							'area_id'=>array('desc'=>'区号/县id'),
							'area_name'=>array('desc'=>'区号/县名称'),
							'address'=>array('desc'=>'详细地址'),
					),
					'response'=>'{"status":1,"info":"修改收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表修改收货地址成功，0代表修改收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
			'Member/deleteAddress'=>array(
					'desc' => '修改收货地址',
					'type'=>'GET',
					'field'=>array(
							'address_id'=>array('desc'=>'地址id编号')
					),
					'response'=>'{"status":1,"info":"删除收货地址成功"}',
					'res_field'=>array(
						array('field'=>'status','desc'=>'1代表删除收货地址成功，0代表删除收货地址失败'),
						array('field'=>'info','desc'=>'提示信息')
					)
			),
	 ),
);
	
return $apis;