<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

class PublicAction extends ApiCommonAction{
    // 用户登出
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
	    	$from = $_SERVER['HTTP_REFERER'];
	    	
	    	/* $login_mod = M('Login');
	    	$login_mod->where(array('token'=>session_id()))->delete(); */
	    	
	    	session_destroy();
			$link = !empty($from) ? $from : ( __APP__. (!empty($_REQUEST['r']) ? "/".$_REQUEST['r'] : ""));
            $this->assign("jumpUrl",  $link);
            $this->success(L('Logout_Successfully'));
        }else {
            $this->error(L('Logout_Done'));
        }
    }

	// 登录检测
	public function checkLogin() 
	{
		if(empty($_POST['mobile'])) {
			$this->error(L('Mobile_Error'), 0);
		}elseif (empty($_POST['password']) ){
			$this->error(L('Password_Must'), 0);
		}

        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['mobile']	= $_POST['mobile'];
        
        if(false && ($_SESSION['verify'] != md5($_POST['verify'])) ) {
			$this->error(L('Verify').L('Error'));
		}
		
		import ( 'ORG.RBAC' );
        
        $authInfo = RBAC::authenticate($map, 'Member');
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo OR (int)$authInfo['status'] == 0) {
        	$this->error(L('Account_Unavailable'), 0);
        } else {
        	if(preg_match("/^\d+$/", $authInfo['id'])) $authInfo['id'] = intval($authInfo['id']);
        	
        	if($authInfo['password'] != md5($_POST['password'])) {
            	$this->error(L('Password_Error'), 0);
            }
            
            $this->_setSession($authInfo);
            
            $this->_setLoggedInfo($authInfo);

			// 缓存访问权限
            RBAC::saveAccessList();

			unset($authInfo['password']);
			
			
			if($authInfo['region_id']){
				$region = M('Region')->field('name')->where(array('id'=>$authInfo['region_id']))->find();
				$authInfo['region_name'] = $region['name'];
			}
			if($authInfo['office_id']){
				$region = M('Office')->field('name')->where(array('id'=>$authInfo['office_id']))->find();
				$authInfo['office_name'] = $region['name'];
			}
			
			$authInfo['team'] = M('Team')->where(array('id'=>$authInfo['team_id']))->find();
			if($authInfo['team'] && (empty($authInfo['team']['avatar']) || !is_file(APP_DIR.$authInfo['team']['avatar']))){
				$authInfo['team']['avatar'] = 'Upload/Avatar/defaultTeam.png';
			}
			
			$authInfo['team']['name'] = $authInfo['office_name'].substr($authInfo['team']['name'],2);
			
			if(empty($authInfo['avatar']) || !is_file(APP_DIR.$authInfo['avatar'])){
				$authInfo['avatar'] = 'Upload/Avatar/default.png';
			}
			$token = session_id();
			$login_time = time();
			$data = array(
					'login_time'=>$login_time,
					'visit_time'=>$login_time,
					'entry_system'=>$this->_post('entry_system'),
					'device_info'=>$this->_post('device_info')
			);
			
			// check app version
			$version = floatval(substr(trim($data['entry_system']), 1,3));
			if($version < 2.3){
				$this->error('Please update your app version to V2.3, thanks.');
			}
			
			$cond = array(
					'uid'=>$authInfo['id'],
					'token'=>$token,
			);
			$login_mod = M('Login');
			$login = $login_mod->where($cond)->field('id')->find();
			if($login){
				$login_mod->where(array('id'=>$login['id']))->save($data);
			}else{
				$login_mod->add(array_merge($data,$cond));
			}
			
			//Location of Inspection
			$selected_position = M('UserLocation')->field('vendor_no,vendor_name,inspection_position')->where(array('uid'=>$authInfo['id']))->find();
			if(!empty($selected_position)){
				$selected_position['inspection_position'] = A('Api/Inspection',true)->getInspectionPositionName($selected_position['inspection_position']);
				$authInfo = array_merge($authInfo,$selected_position);
			}else{
				$authInfo['vendor_no'] = $authInfo['vendor_name'] = $authInfo['inspection_position'] = '';
			}
			$authInfo['vendors'] = $this->getVendors();
			$authInfo['positions'] = array_values(CC('Api/config.inspection_position'));
			$this->success($authInfo,array('token'=>$token));
			return;
		}
	}
	
	private function _setSession($authInfo)
	{
		$_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
		$_SESSION['email']	=	$authInfo['email'];
		$_SESSION['loginUserName']		=	$authInfo['nickname'];
		$_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
		$_SESSION['login_count']	=	$authInfo['login_count'];
		$_SESSION['user'] = $authInfo;
			
		if($authInfo['account']=='admin') {
			$_SESSION[C('ADMIN_AUTH_KEY')]		=	true;
		}
			
	}
	
	private function _setLoggedInfo($authInfo)
	{
		//保存登录信息
		$User	=	M('User');
		$ip		=	get_client_ip();
		$time	=	time();
		$data = array();
		$data['id']	=	$authInfo['id'];
		$data['last_login_time']	=	$time;
		$data['login_count']	=	array('exp','login_count+1');
		$data['last_login_ip']	=	$ip;
		$User->save($data);
			
	}
	
	
	public function getVendors(){
		$checklist_vendor_mod =  D('CheckList/Vendor');
		$checklist_user = $checklist_vendor_mod->table('mf_user')->where(array('account'=>$_SESSION['user']['account']))->field('id')->find();
		
		
		$vendor_ids = array();
		$user_levels = CC('Api/contact.user_level');
		if($user_levels[$_SESSION['user']['account']]){
			$level = $user_levels[$_SESSION['user']['account']];
			/* $users = $level['users'];
			if($users){
				$next_level_users = $checklist_vendor_mod->table('mf_user u')->where(array('account'=>array('in',$users)))->field('u.id,u.photo as avatar,u.nickname as name,"" as description,u.telephone')->select();
				$contacts = $next_level_users?array_merge($contacts,$next_level_users):$contacts;
			} */
			$qa_teams = $level['qa_team'];
			if($qa_teams){
				$vendors = $checklist_vendor_mod->table('mf_vendor')->where(array('qa_office_team'=>array('in',$qa_teams),'status'=>'1'))->field('vendor_no,name as vendor_name')->select();
			}
		}else{
			$vendors = $checklist_vendor_mod->table('mf_vendor_qc q')->join('mf_vendor v on q.vendor_id=v.id')->field('v.vendor_no,v.name as vendor_name')->where(array('q.user_id'=>$checklist_user['id'],'q.status'=>'0','v.status'=>'1'))->select();
		}
		
		if(empty($vendors)){
			//检查是否在QA Team Member中
			$qa_teams_select = $checklist_vendor_mod->table('mf_qa_team_member')->where(array('name_qtm'=>$_SESSION['user']['account']))->field('distinct qa_team_qtm')->select();
			$qa_teams = array();
			foreach ($qa_teams_select as $qa){
				$qa_teams[] = $qa['qa_team_qtm'];
			}
				
			$vendors = $checklist_vendor_mod->table('mf_vendor')->field('vendor_no,name as vendor_name')->where(array('qa_office_team'=>array('in',$qa_teams),'status'=>'1'))->select();
		}
		
		if($_SESSION['user']['account']=='Kevin Kang'){
			$vendors[] = array('vendor_no'=>'900541','vendor_name'=>'Galaxy');
		}
		
		return empty($vendors)?array():$vendors;
	}
}