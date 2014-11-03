<?php
/**
 * 广告管理
 * @author kewen
 */
class AdvAction extends CommonAction {
    
    public function index() {
        $pid = intval($_GET['pid']);
        $w = "";
        if(empty($pid)) {
            $w = "1=1";
        }else {
            $w = "position_id='{$pid}'";
        }
        $list = M("Adv")->where($w)->order("id desc")->select();
        $this->assign("list", $list);
        $this->assign("pid", $pid);
        $this->display();
    }
    
    public function add() {
        $pid = intval($_GET['pid']);
        $this->assign("pid", $pid);
        $position = M("Adv_position")->select();
        $this->assign("position", $position);
        $this->display();
    }
    
    public function edit() {
        $id = intval($_GET['id']);
        $position = M("Adv_position")->select();
        $this->assign("position", $position);
        $info = M("Adv")->where("id='{$id}'")->find();
        $this->assign("id", $id);
        $this->assign("info", $info);
        $this->display();
    }
    
    public function  insert() {
        !$this->isAjax() && $this->redirect("Index/index");
        $data = array();
        $data['position_id'] = intval($_POST['position']);
        $data['name'] = trim($_POST['name']);
        $data['link'] = trim($_POST['link']);
        $data['sort'] = intval($_POST['sort']);
        $data['enabled'] = intval($_POST['enabled']);
        $data['description'] = trim($_POST['description']);
        $data['image'] = trim($_POST['imgname']);

        $sign = M("Adv")->data($data)->add();
        $back = new stdClass();
        if($sign) {
            $back->status = 1;
        }else {
            $back->status = 0;
        }
        ajax_return($back);
    }
    
    public function  update() {
        !$this->isAjax() && $this->redirect("Index/index");
        $id = intval($_POST['id']);
        $data = array();
        $data['position_id'] = intval($_POST['position']);
        $data['name'] = trim($_POST['name']);
        $data['link'] = trim($_POST['link']);
        $data['sort'] = intval($_POST['sort']);
        $data['enabled'] = intval($_POST['enabled']);
        $data['description'] = trim($_POST['description']);
        $data['image'] = trim($_POST['imgname']);       
        $back = new stdClass();
        $sign = M("Adv")->where("id='{$id}'")->data($data)->save();
        if($sign) {
            $back->status = 1;
        }else {
            $back->status = 0;
        }
        ajax_return($back);
    }
    
    public function delete() {
        $id = intval($_GET['id']);
        if(empty($id)) {
            $this->error("非法操作!");
        }
        $info = M("Adv")->where("id='{$id}'")->find();
        $sign = M("Adv")->where("id='{$id}'")->delete();
        if($sign) {
            //删除
            import("@.ORG.Util.UploadFile");
            $img = UploadFile::getPicturePath("adv", $info['image']);
            if(file_exists($img)) {
                @unlink($img);
            }
            $this->success("删除成功!");
        } else {
            $this->error("删除失败!");
        }
    }
    
    public function sort() {
        if (empty($_POST['sort'])) {
            $this->error("操作参数有误！");
        }
        $data = array();
        foreach ($_POST['sort'] as $key => $value) {
            $data['sort'] = $value;
            M("Adv")->where("id='" . intval($key) . "'")->save($data);
        }
        $this->success('操作完成!');
    }
}
?>
