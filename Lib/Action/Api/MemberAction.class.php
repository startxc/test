<?php
/*
*@author: bruce
*@desc: 会员相关接口
*/

class MemberAction extends MobileCommonAction{
	
	//登录
	public function login(){
		!$this->isAjax() && $this->error("非法访问");
		$condition = array(
			"mobile"=>trim($_POST['mobile']),
			"password"=>md5(trim($_POST['password']))
		);
		if($data = M("Member")->where($condition)->find()){
			
			$_SESSION['uid'] = $data['id'];
			$_SESSION['mobile'] = $data['mobile'];
			$_SESSION['nickname'] = $data['nickname'];

			$update_info = array(
				"last_login_ip"=>get_client_ip(),
				"last_login_time"=>time()	
			);
			M("Member")->where("id={$data['id']}")->save($update_info);

			$this->success("登录成功");
		}else{
			$this->error("登录失败");
		}
	}

	//注册
	public function register(){
		!$this->isAjax() && $this->error("非法访问");
		$data = array();
		$data['mobile'] = trim($_POST['mobile']);
		$this->isEmpty($data['mobile'],"手机号码不能为空");
		if(M("Member")->where("mobile='{$data['mobile']}'")->find()){
			$this->error("该手机号码已被注册了");
		}
		$verify = trim($_POST['verify']);
		$this->isEmpty($verify,"验证号不能为空");
		if($data['mobile'].$verify != $_SESSION['verify']){
			$this->error("验证号不对");
		}
		$data['password'] = trim($_POST['password']);
		$this->isEmpty($data['password'],"密码不能为空");
		$confirm_password = trim($_POST['confirm_password']);
		$this->isEmpty($confirm_password,"确认密码不能为空");
		if($data['password'] != $confirm_password){
			$this->error("确认密码与设置密码不一致");
		}
		$data['nickname'] = trim($_POST['nickname']);
		$this->isEmpty($data['nickname'],"昵称不能为空");
		$data['create_time'] = time();
		if($id = M('Member')->add($data)){
			$_SESSION['uid'] = $id;
			$_SESSION['mobile'] = $data['mobile'];
			$_SESSION['nickname'] = $data['nickname'];

			$this->success("注册成功");
		}else{
			$this->error("注册失败");
		}
	}



	//修改登录密码
	public function setPassword(){
		$uid = $_SESSION['uid'];
		$this->isEmpty($uid,"你还没有登录哦");
		$password = trim($_POST['password']);
		$this->isEmpty($password,"原密码不能为空");
		$member_info = M("Member")->where("id={$uid}")->find();
		if($member_info['password'] != md5($password)){
			$this->error("原密码不正确");
		}
		$new_password = trim($_POST['new_password']);
		$this->isEmpty($new_password,"新密码不能为空");
		if($new_password == $password){
			$this->error("新密码与原密码一样");
		}
		$confirm_password = trim($_POST['confirm_password']);
		$this->isEmpty($confirm_password,"确认密码不能为空");
		if($confirm_password != $new_password){
			$this->error("确认密码与新密码不一致");
		}
		$data = array("password"=>md5($new_password));
		if(M("Member")->where("id={$uid}")->save($data)){
			$this->success("修改登录密码成功");
		}else{
			$this->error("修改登录密码失败");
		}
	}

	//修改支付密码
	public function setPayPassword(){
		$uid = $_SESSION['uid'];
		$this->isEmpty($uid,"你还没有登录哦");
		$member_info = M("Member")->where("id={$uid}")->find();
		$orgin_password = trim($member_info['pay_password']);
		if(!empty($orgin_password)){
			$password = trim($_POST['password']);
			$this->isEmpty($password,"原密码不能为空");
			if($member_info['pay_password'] != md5($password)){
				$this->error("原密码不正确");
			}
		}
		$new_password = trim($_POST['new_password']);
		$this->isEmpty($new_password,"新密码不能为空");
		if(!empty($orgin_password)){
			if($new_password == $password){
				$this->error("新密码与原密码一样");
			}		
		}
		if($member_info['password'] == md5($new_password)){
			$this->error("支付密码不能与登录密码一致");
		}
		$confirm_password = trim($_POST['confirm_password']);
		$this->isEmpty($confirm_password,"确认密码不能为空");
		if($confirm_password != $new_password){
			$this->error("确认密码与新密码不一致");
		}
		$data = array("pay_password"=>md5($new_password));
		if(M("Member")->where("id={$uid}")->save($data)){
			$this->success("修改支付密码成功");
		}else{
			$this->error("修改支付密码失败");
		}
	}
    
	//修改手机号码
	public function setMobile(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$mobile = $_SESSION['mobile'];
		$data['mobile'] = trim($_POST['mobile']);
		$this->isEmpty($data['mobile'],"手机号码不能为空");
		if($data['mobile'] == $mobile){
			$this->error("新手机号码与原手机号码一样");
		}
		if(M("Member")->where("mobile='{$data['mobile']}'")->find()){
			$this->error("该手机号码已被注册了");
		}
		$verify = trim($_POST['verify']);
		$this->isEmpty($verify,"验证号不能为空");
		if($data['mobile'].$verify != $_SESSION['verify']){
			$this->error("验证号不对");
		}
		if(M("Member")->where("id={$uid}")->save($data)){
			$_SESSION['mobile'] = $data['mobile'];
			$this->success("修改手机号码成功");
		}else{
			$this->error("修改手机号码失败");
		}
	}

	//获取会员地址列表
	public function getAddress(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$address = M("Member_address")->where("member_id={$uid}")->order("is_default desc,create_time desc")->select();
		$this->ajaxRespon($address);
		return $address;

	}

	//增加收货地址
	public function addAddress(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$data = array();
		$data['member_id'] = $uid;
		$data['consignee'] = trim($_POST['consignee']);
		$this->isEmpty($data['consignee'],"收货人不能为空");
		$data['mobile'] = trim($_POST['mobile']);
		$this->isEmpty($data['mobile'],"手机号码不能为空");
		$data['province_id'] = $_POST['province_id'];
		$this->isEmpty($data['province_id'],"省份不能为空");
		$data['province_name'] = trim($_POST['province_name']);
		$this->isEmpty($data['province_name'],"省份不能为空");
		$data['city_id'] = $_POST['city_id'];
		$this->isEmpty($data['city_id'],"城市不能为空");
		$data['city_name'] = trim($_POST['city_name']);
		$this->isEmpty($data['city_name'],"城市不能为空");
		$data['area_id'] = $_POST['area_id'];
		$this->isEmpty($data['area_id'],"区号/县不能为空");		
		$data['area_name'] = trim($_POST['area_name']);
		$this->isEmpty($data['area_name'],"区号/县不能为空");
		$data['address'] = trim($_POST['address']);
		$this->isEmpty($data['address'],"详细地址不能为空");
		if(!M("Member_address")->where("member_id={$uid}")->find()){
			$data['is_default'] = 1;
		}else{
			$data['is_default'] = 0;
		}
		$time = time();
		$data['create_time'] = $time;
		$data['update_time'] = $time;
		if(M("Member_address")->add($data)){
			if($data['is_default'] == 1){ 
				$member_data = array(
					"province_id"=>$data['province_id'],
					"city_id"=>$data['city_id'],
					"area_id"=>$data['area_id'],
					"province_name"=>$data['province_name'],
					"city_name"=>$data['city_name'],
					"area_name"=>$data['area_name'],
					"address"=>$data['address']
				);
				M("Member")->where("id={$uid}")->save($member_data);
			}
			$this->success("添加收货地址成功");
		}else{
			$this->error("添加收货地址失败");
		}

	}

	//修改收货地址
	public function setAddress(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$address_id = $_POST['address_id'];
		$data['consignee'] = trim($_POST['consignee']);
		$this->isEmpty($data['consignee'],"收货人不能为空");
		$data['mobile'] = trim($_POST['mobile']);
		$this->isEmpty($data['mobile'],"手机号码不能为空");
		$data['province_id'] = $_POST['province_id'];
		$this->isEmpty($data['province_id'],"省份不能为空");
		$data['province_name'] = trim($_POST['province_name']);
		$this->isEmpty($data['province_name'],"省份不能为空");
		$data['city_id'] = $_POST['city_id'];
		$this->isEmpty($data['city_id'],"城市不能为空");
		$data['city_name'] = trim($_POST['city_name']);
		$this->isEmpty($data['city_name'],"城市不能为空");
		$data['area_id'] = $_POST['area_id'];
		$this->isEmpty($data['area_id'],"区号/县不能为空");		
		$data['area_name'] = trim($_POST['area_name']);
		$this->isEmpty($data['area_name'],"区号/县不能为空");
		$data['address'] = trim($_POST['address']);
		$this->isEmpty($data['address'],"详细地址不能为空");
		$data['update_time'] = time();
		if(M("Member_address")->where("id={$address_id} && member_id={$uid}")->save($data)){
			if(M("Member_address")->where("id={$address_id} && is_default=1")->find()){
				$member_data = array(
					"province_id"=>$data['province_id'],
					"city_id"=>$data['city_id'],
					"area_id"=>$data['area_id'],
					"province_name"=>$data['province_name'],
					"city_name"=>$data['city_name'],
					"area_name"=>$data['area_name'],
					"address"=>$data['address']
				);
				M("Member")->where("id={$uid}")->save($member_data);
			}
			$this->success("修改收货地址成功");
		}else{
			$this->error("修改收货地址失败");
		}
	}

	//删除收货地址
	public function deleteAddress(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$address_id = $_POST['address_id'];
		$member_address = M("Member_address");
		$member_address->startTrans();
		if($member_address->where("id={$address_id} && member_id={$uid} && is_default=1")->find()){
			$member_address_data = $member_address->where("member_id={$uid} && is_default=0")->order("update_time desc")->find();
			if(!empty($member_address_data)){
				if(!$member_address->where("id={$member_address_data['id']}")->setField("is_default",1)){
					$member_address->rollback();
					$this->error("删除收货地址失败");
				}
				$member_data = array(
					"province_id"=>$member_address_data['province_id'],
					"city_id"=>$member_address_data['city_id'],
					"area_id"=>$member_address_data['area_id'],
					"province_name"=>$member_address_data['province_name'],
					"city_name"=>$member_address_data['city_name'],
					"area_name"=>$member_address_data['area_name'],
					"address"=>$member_address_data['address']
				);
				if(!M("Member")->where("id={$uid}")->save($member_data)){
					$member_address->rollback();
					$this->error("删除收货地址失败");
				}
			}
		}
		if($member_address->where("id={$address_id} && member_id={$uid}")->delete()){
			$member_address->commit();
			$this->success("删除收货地址成功");
		}else{
			$member_address->rollback();
			$this->error("删除收货地址失败");
		}
	}

	//设置默认收货地址
	public function setDefaultAddress(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$address_id = $_POST['address_id'];
		$member_address = M("Member_address");
		$member_address->startTrans();
		if(!$member_address->where("member_id={$uid} && is_default=1")->setField("is_default",0)){
			$member_address->rollback();
			$this->error("设置默认收货地址失败");
		}
		if(!$member_address->where("id={$address_id} && member_id={$uid}")->setField("is_default",1)){
			$member_address->rollback();
			$this->error("设置默认收货地址失败");
		}
		$member_address_data = $member_address->where("id={$address_id}")->find();
		$member_data = array(
			"province_id"=>$member_address_data['province_id'],
			"city_id"=>$member_address_data['city_id'],
			"area_id"=>$member_address_data['area_id'],
			"province_name"=>$member_address_data['province_name'],
			"city_name"=>$member_address_data['city_name'],
			"area_name"=>$member_address_data['area_name'],
			"address"=>$member_address_data['address']
		);
		if(!M("Member")->where("id={$uid}")->save($member_data)){
			$member_address->rollback();
			$this->error("设置默认收货地址失败");
		}	
		$member_address->commit();
		$this->success("设置默认收货地址成功");	
	}

	//绑定会员
	public function setBindMember(){
		$uid = $_SESSION['uid'];
		if(empty($uid)){
			$this->error("你还没有登录哦");
		}
		$data['bind_member'] = trim($_POST['bind_member']);
		if(empty($data['bind_member'])){
			$this->error("会员卡号不能为空哦");
		}
		if(M("Member")->where("id={$uid}")->save($data)){
			$this->success("绑定会员成功");
		}else{
			$this->error("绑定会员失败");
		}
	}

	//退出帐号
	public function loginOut(){
		$_SESSION['uid'] = null;
		$_SESSION['mobile'] = null;
		$_SESSION['nickname'] = null;
		$this->success("退出成功");
	}

	//获取验证号，临时测试用，上线版本用短信接口获取
	public function getVerify(){
		$mobile = trim($_GET['mobile']);
		$verify = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$_SESSION['verify'] = $mobile.$verify;
		echo $verify;
	}	
}
?>