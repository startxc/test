<?php
class ApiCommonAction extends CommonAction{
	protected $_group_name = 'Api';
	protected function getUser(){
		return $_SESSION['user'];
	}
	
	protected function getQueryUser(){
		$user_id = $this->_request('uid','intval',0);
		$user = array();
		if( ! $user_id){
			$user = $this->getUser();
		}
		else 
		{
			$user = M('User')->where(array('id'=>$user_id))->find();
		}
			
		return $user;
	}
	
	protected function getUserId(){
		$user = $this->getUser();
		return $user['id'];
	}
	
	public function _initialize(){
		//暂时通过token来设置php session id
		$token = $this->_request('token');
		if($token){
			$_COOKIE['PHPSESSID'] = $token;
		}
		session_start();
		
		if(isset($_REQUEST['offset'])){
			$_SESSION['timezoneOffset'] = $this->_request('offset','intval');
		}/* else{
			if(!isset($_SESSION['timezoneOffset'])){
				unset($_SESSION['timezoneOffset']);
			}
		} */
	    
		if(isset($_SESSION['timezoneOffset'])){
			//设置成0时区
			date_default_timezone_set('UTC');
		}
		
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && 
			!in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE'))) 
			&& (property_exists($this, '_not_auth_action') && !in_array(ACTION_NAME, $this->_not_auth_action))
		   ) {
			import ( 'ORG.RBAC' );
			$groupName = empty($this->_group_name) ? APP_NAME : $this->_group_name;
			if (! RBAC::AccessDecision ($groupName) ) {
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					Log::write(var_export($_GET,true).var_export($_POST,true).var_export($_COOKIE,true));
					$this->error('Login Timeout!');
				}
				// 没有权限 抛出错误
				$module = defined('P_MODULE_NAME')?  P_MODULE_NAME   :   MODULE_NAME;
				$this->error ( L ( '_VALID_ACCESS_' ).':'.$groupName.'_'.$module.'_'.ACTION_NAME );
				Log::write(L ( '_VALID_ACCESS_' ).':'.$groupName.'_'.$module.'_'.ACTION_NAME);
				Log::write(var_export($_REQUEST,true).var_export($_COOKIE,true).var_export($_SESSION,true));
			}
		}
		$user_id = $this->getUserId();
		if($user_id){
			M('Login')->where(array(
					'token' => $_COOKIE['PHPSESSID'],
					'uid'=>$user_id,
			))->save(array(
			    'visit_time'=>time(),
			    'call_api_cnt'=>array('exp','`call_api_cnt`+1'),
			));
		}
	}
	
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
	
	/**
	 * Api success函数
	 * @see Action::success()
	 */
	public function success($data,$extend=array(),$info=''){
		$this->ajaxRespon($data,1,$info,$extend);
	}
	
	/**
	 * Error
	 * @see Action::error()
	 */
	public function error($error='',$extend=array()){
		$this->ajaxRespon(array(),0,$error,$extend);
	}
	
	/**
	 * api接口添加数据公用类
	 * @see CommonAction::insert()
	 */
	function insert($name='') {
		empty($name) AND $name=$this->getActionName();
		$model = $this->_D($name);
		$data = $model->create ();
		if (false === $data) {
			$this->error ($model->getError ());
		}
		//保存当前数据对象
		$id = $model->add ();
		if ($id!==false) { //保存成功
			$data['id'] = $id;
			$this->success ($data);
		} else {
			//失败提示
			$this->error (L('Add_Failured'));
		}
	}
	
	public function delete() {
		$name=$this->getActionName();
		$model = $this->_D($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->setField ( 'deleted', '1' );
				if ($list!==false) {
					$this->success (L('Delete_Successfully') );
				} else {
					$this->error (L('Delete_Failured'));
				}
			} else {
				$this->error ( L('Illegal').L('Operate') );
			}
		}
	}
	
	protected function getClientDateTime($time,$formate=null){
		return $formate? toDate($time,$formate):toDate($time);
	}
	
	protected function getUserById($uid){
		static $users = array();
		if(empty($users[$uid])){
			$u = M('User')->where(array('id'=>$uid))->field('first_name,last_name,screen_name,account,avatar,quote')->find();
			if(empty($u['avatar']) || !is_file(APP_DIR.$u['avatar'])){
				$u['avatar'] = 'Upload/Avatar/default.png';
			}
			$users[$uid] = $u;
		}
		return $users[$uid]?$users[$uid]:array();
	}
	
	/**
	 * 通过 HTML调用时返回true
	 * 
	 * @return boolean
	 */
	protected function isMobile(){
		return GROUP_NAME=='Mobile';
	}
}