<?php

/**
 * 配置管理
 *
 * @author kewen
 */
class SettingAction extends CommonAction {
    
    public function index() {
        $configList = M("Config")->select();
        $this->assign("configList", $configList);
        $this->display();
    }   
    
    public function batch() {
        $Config = M("Config");
        foreach ($_POST['config'] as $key => $value) {
            $Config->where("code='{$key}'")->save(array('value'=>$value));
        }
        $this->redirect('Setting/index', '', 0, '页面跳转中...');
    }
    
}

?>
