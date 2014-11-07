<?php
class CommonAction extends Action {

	public function _initialize() {
    }

    //检查是否登录
    public function checkLogin(){
    	if(empty($_SESSION['uid'])){
    		$this->error("你还没有登录哦",U("Public/login"));
    		//$this->redirect("Public/login");
    	}
    }

}
?>