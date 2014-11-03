<?php
class IndexAction extends CommonAction {
	// 框架首页
	public function index() {
		$api = CC('Api/document');
		$this->assign('api',$api);
		$this->display();
	}

	public function doc(){
		$api = CC('Api/document');
		$this->assign('api',$api);
		$curr_field = $this->_get('field');
		if(!$curr_field){//默认用于测试
			$curr_field = $_GET['field'] = 'Public/checkLogin';
		}
		$current_api = false;
		$current_key = -1;
		foreach ($api as $k=> $value) {
			foreach ($value['api'] as $key=> $a) {
				if($key==$curr_field){
					$a['name'] = $key;
					$current_api = $a;
					$current_key = $k;
					if(!in_array($key,array('Public/checkLogin','AppVersion/getVersion','AppLog/index'))){
						$current_api['field']['token'] = array('desc'=>'登录时返回的Token,必须');
					}
					break;
				}
			}
		}
		$this->assign('current_api',$current_api);
		$this->assign('current_key',$current_key);
		$this->display();
	}
	
	public function file(){
		$this->display();
	}

}