<?php
/*
*@author:bruce
*@desc:公共操作
*/
class PublicAction extends CommonAction{

	//登录页面
	public function login(){
		$this->display();
	}

	//注册页面
	public function register(){
		$this->display();
	}

	//忘记密码页面
	public function foregetpassword(){
		$this->display();
	}

	//重设密码页面
	public function resetpassword(){
		if($this->isAjax()){
			$back = new stdClass();
			$mobile = trim($_POST['mobile']);
			if(empty($mobile)){
				$back->status = 0;
				$back->info = "手机号码不能为空";
				ajax_return($back);
			}
			if(!M("Member")->where("mobile={$mobile}")->find()){
				$back->status = 0;
				$back->info = "该手机号还没注册";
				ajax_return($back);
			}
			$verify = trim($_POST['verify']);
			if(empty($verify)){
				$back->status = 0;
				$back->info = "验证号不能为空";
				ajax_return($back);
			}
			if($mobile.$verify != $_SESSION['verify']){
				$back->status = 0;
				$back->info = "验证号不对";
				ajax_return($back);
			}

			$back->status = 1;
			$_SESSION['resetpassword'] = 1;
			$_SESSION['resetmobile'] = $mobile;
				ajax_return($back);
		}else{
			if($_SESSION['resetpassword'] == 1){
				$this->display();
			}else{
				$this->redirect("Public/login");
			}
		}
	}

	//重设新密码
	public function resetNewpassword(){
		!$this->isAjax() && $this->error("非法访问");
		$mobile = $_SESSION['resetmobile'];
		if(empty($mobile)){
			$this->error("非法访问");
			exit();
		}
		$back = new stdClass();
		$password = trim($_POST['password']);
		if(empty($password)){
			$back->status = 0;
			$back->info = "新密码不能为空";
			ajax_return($back);
		}
		$confirm_password = trim($_POST['confirm_password']);
		if(empty($confirm_password)){
			$back->status = 0;
			$back->info = "确认密码不能为空";
			ajax_return($back);
		}
		if($confirm_password != $password){
			$back->status = 0;
			$back->info = "确认密码与新密码不一致";
			ajax_return($back);
		}
		if(M("Member")->where("mobile={$mobile}")->setField("password",md5($password))){
			$_SESSION['resetpassword'] = null;
			$_SESSION['resetmobile'] = null;
			$back->status = 1;
			ajax_return($back);
		}else{
			$back->status = 0;
			$back->info = "重设新密码失败";
			ajax_return($back);
		}
	}


}
?>