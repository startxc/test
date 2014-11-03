<?php
/**
 * 广告管理
 * @author kewen
 */
class AdvPositionAction extends CommonAction {
    
    public function index() {
        $list = M('AdvPosition')->select();
        $this->assign("list", $list);
        $this->display();
    }
    
    public function add() {
        $this->display();
    }
    
    public function edit() {
        $id = intval($_GET['id']);
        $info = M("Adv_position")->where("id='{$id}'")->find();
        $this->assign("id", $id);
        $this->assign("info", $info);
        $this->display();
    }
    
    public function  insert() {
        $data = array();
        $data['code'] = trim($_POST['code']);
        $data['name'] = trim($_POST['name']);
        $data['width'] = intval($_POST['width']);
        $data['height'] = intval($_POST['height']);
        $data['description'] = trim($_POST['description']);
        
        //查找是否存在代号
        $AdvPosition = M("Adv_position");
        $tip = $AdvPosition->where("code='{$data['code']}'")->find();
        if($tip) {
            $this->error("该位置代号已经存在了!"); exit;
        }
        
        $sign = $AdvPosition->data($data)->add();
        if($sign) {
            $this->success("亲,添加成功哦!", '/Admin/AdvPosition/index');
        }else {
            $this->success("亲,添加失败哦!");
        }
    }
    
    public function update() {
        $id = intval($_POST['id']);
        if(empty($id)) {
            $this->error("非法操作哦!");
        }
        $data = array();
        $data['code'] = trim($_POST['code']);
        $data['name'] = trim($_POST['name']);
        $data['width'] = intval($_POST['width']);
        $data['height'] = intval($_POST['height']);
        $data['description'] = trim($_POST['description']);
        
        $AdvPosition = M("Adv_position");
        $tip = $AdvPosition->where("code='{$data['code']}' and id!='{$id}'")->find();
        if($tip) {
            $this->error("该位置代号已经存在了!"); exit;
        }
        $sign = $AdvPosition->where("id='{$id}'")->data($data)->save();
        if($sign) {
            $this->success("亲,更新成功哦!", '/Admin/AdvPosition/index');
        }else {
            $this->success("亲,更新失败哦!");
        }
    }
    
    public function delete() {
        $id = intval($_GET['id']);
        if(empty($id)) {
            $this->error("非法操作哦!");
        }
        $sign = M("Adv_position")->where("id='{$id}'")->delete();
        if($sign) {
            $this->success("删除成功哦!");
        }else {
            $this->success("删除失败哦!");
        }
    }
}
?>