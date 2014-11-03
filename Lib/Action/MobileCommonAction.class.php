<?php
class MobileCommonAction extends CommonAction{
	protected $_group_name = 'Mobile';
	protected function getUser(){
		return $_SESSION['user'];
	}
	
	protected function getUserId(){
		$user = $this->getUser();
		return $user['id'];
	}
	
	public function _initialize(){
		
		#Log::write(var_export($_SERVER,true).var_export($_COOKIE,true));
		$token = $this->_request('token');
		if($token){
			#setcookie('PHPSESSID',$token,time()-86400);
			$_COOKIE['PHPSESSID'] = $token;
			setcookie('PHPSESSID',$token,time()+86400,'/',$_SERVER['HTTP_HOST']);
		}
		
		parent::_initialize();
		
		$this->assign(CC('Mobile/config.tpl_val'));
	}
	
	
	public function getApiAction($action_name){
		return A('Api/'.$action_name);
	}
	
	
	public function getUserById($uid=0){
		static $users = array();
		$uid = $uid?$uid:$_SESSION['user']['id'];
		if(empty($users[$uid])){
			$u = M('User')->where(array('id'=>$uid))->field('id,first_name,last_name,screen_name,account,avatar,quote')->find();
			if(empty($u['avatar']) || !is_file(APP_DIR.$u['avatar'])){
				$u['avatar'] = 'Upload/Avatar/default.png';
			}
			$users[$uid] = $u;
		}
		return $users[$uid]?$users[$uid]:array();
	}
	
	public function getUserDetail($uid=0){
		static $users = array();
		$uid = $uid?$uid:$_SESSION['user']['id'];
		if(empty($users[$uid])){
			$user = M('User')->where(array('id'=>$this->getUserId()))->find();
			if(empty($user['avatar']) || !is_file(APP_DIR.$user['avatar'])){
				$user['avatar'] = 'Upload/Avatar/default.png';
			}
			$users[$uid] = $user;
		}
		return $users[$uid]?$users[$uid]:array();
	}
	
	
	
	
	public function getTeamById($team_id=0){
		static $teams = array();
		$team_id = $team_id?$team_id:$_SESSION['user']['team_id'];
		if(empty($teams[$team_id])){
			$team = M('Team')->where(array('t.id'=>$team_id))->alias('t')->join('quality_office o on t.office_id=o.id')->field('t.id,t.name,t.avatar,o.name as office_name')->find();
			if(empty($team['avatar']) || !is_file(APP_DIR.$team['avatar'])){
				$team['avatar'] = 'Upload/Avatar/defaultTeam.png';
			}
			$teams[$team_id] = $team;
		}
		return $teams[$team_id]?$teams[$team_id]:array();
	}

	//ajax返回信息
	public function ajaxRespon($data, $status = 1, $info = '',$extend = array()) {
		if(!$this->isMobile()){
			$result  =  is_array($extend)?$extend:array();
			$result['status']  =  $status;
			$result['info'] =  $info;
			//去除null值，防止json在ios中出错
			array_walk_recursive($data, function(&$item, &$key){
				if(is_null($item))$item = '';
			});
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