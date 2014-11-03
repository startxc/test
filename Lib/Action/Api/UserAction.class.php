<?php
class UserAction extends ApiCommonAction
{
	var $_photoPath = 'Upload/Avatar/';
	
	public function update() {
		$user = $this->getUser();
		$userId = $user['id'];
		$User_mod	 =	 D("User");
		$data = $User_mod->create();
		if(!$data) {
			$this->error($User_mod->getError());
		}else{
			$old_pass = $this->_post('old_password','trim');
			$new_pass = $this->_post('password','trim');
			$retype_pass = $this->_post('retype_password','trim');
			if($old_pass && $new_pass && $retype_pass){
				$old_user = $User_mod->where(array('id'=>$userId))->field('password')->find();
				$old_pass_md5 = md5($old_pass);
				if($new_pass!=$retype_pass){
					$this->error('Retype Password Error!');
				}
				if($old_pass_md5!=$old_user['password']){
					$this->error('Old Password Error!');
				}
				$data['password'] = md5($new_pass);
			}else{
				unset($data['password']);
			}
			$result = false;
			if(!empty($data)){
				$result	 =	 $User_mod->where(array('id'=>$userId))->save($data);
			}
			if($result!==false) {
				$this->success($data);
			}else{
				$this->error(L('User').L('Edit').('Failured').'！');
			}
		}
	}
	
	/**
	 * 更新用户图片
	 */
	function uploadAvatar(){
		$user = $this->getUser();
		$userId = $user['id'];
		
		$avatar = A('Api/UploadFile',true)->uploadImage('Avatar');

		$model = M('User');
		$link = array();
		foreach ($avatar as $k=>$file) {
	    	if($file['savename']) {
	    		$file_path = $file['savename'];
	    		$old_user = $model->where(array('id'=>$userId))->field('avatar')->find();
	    		if($model->where(array('id'=>$userId))->setField('avatar', $file_path)){
	    			if($old_user['avatar'] && is_file(APP_DIR.$old_user['avatar'])){
	    				@unlink(APP_DIR.$old_user['avatar']);
	    			}
	    		}
			}
		}
		$this->success(array('id'=>$userId,'avatar'=>$file_path));
	}
	
	/**
	 * get all users
	 */
	public function getList(){
		
		$users = $this->model()->field('id,team_id,region_id,office_id,account,screen_name,first_name,last_name,avatar')
			->where(array('status'=>1,'deleted'=>0))->select();
			
		if($users)
		{
			$this->success($users);
		}
		else
		{
			$this->error('request user list error. please retry.');
		}
	}
	
	function model()
	{
		return $this->_D('User');
	}
}