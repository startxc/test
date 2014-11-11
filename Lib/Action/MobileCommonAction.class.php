<?php
class MobileCommonAction extends Action{

	

	//ajax返回信息
	public function ajaxRespon($data, $status = 1, $info = '',$extend = array()) {
		if(!$this->isMobile()){
			$result  =  is_array($extend)?$extend:array();
			$result['status']  =  $status;
			$result['info'] =  $info;
			//去除null值，防止json在ios中出错
			function walkfunc(&$item, &$key){
				if(is_null($item))$item = '';
			}
			array_walk_recursive($data, 'walkfunc');
			$result['data'] = $data;
		    
		    // 返回JSON数据格式到客户端 包含状态信息
			header("Content-Type:text/html; charset=utf-8");
			exit(json_encode($result));
		}
	}

 	//成功返回信息
	public function success($info='',$data=array()){
		$this->ajaxRespon($data,1,$info);
	}

	public function error($info='',$data=array()){
		$this->ajaxRespon($data,0,$info);
	}

	//判断是否mobile的请求
	protected function isMobile(){
		return GROUP_NAME=='Mobile';
	}

	//判断数据是否为空
	public function isEmpty($data,$info="不能为空"){
		if(empty($data)){
			$this->error($info);
		}
	}
	
}