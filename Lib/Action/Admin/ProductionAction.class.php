<?php

/**
 * 商品产地管理
 *
 * @author bruce
 */
class ProductionAction extends CommonAction {

    //产地列表
    public function index() {
        $list = array();
        $list = M("Production")->order("id desc")->select();
        $this->assign("list", $list);
        $this->display();
    }

    //添加产地页面
    public function add() {
        $this->display();
    }

    //编辑产地页面
    public function edit() {
        $id = intval($_GET['id']);
        $info = M("Production")->where("id={$id}")->find();
        $this->assign("info", $info);
        $this->display();
    }

    //写入产地数据
    public function insert() {
        !$this->isAjax() && $this->error("非法访问");
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['order_index'] = intval($_POST['order_index']);
        $data['status'] = intval($_POST['status']);
        $data['create_time'] = time();
        $Production = M("Production");
        $back = new stdClass();
        if ($Production->add($data)) {
            $back->status = 1;
            $back->info = "添加成功";
        } else {
            $back->status = 0;
            $back->info = "添加失败";
        }
        ajax_return($back);
    }

    //更新产地数据
    public function update() {
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['status'] = intval($_POST['status']);
        $data['order_index'] = intval($_POST['order_index']);
        $data['create_time'] = time();
        $back = new stdClass();
        if(M("Production")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "更新成功";
        }else{
            $back->status = 0;
            $back->info = "更新失败";
        }
        ajax_return($back);
    }

    //删除产地
    public function delete() {
        $id = empty($_GET['id']) ? $this->error('删除失败，传递参数有误') : intval($_GET['id']);

        if(M("Production")->where("id={$id}")->delete()){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    //更新产地排序
    public function updateSort(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['order_index'] = intval($_POST['order_index']);
        $back = new stdClass();
        if(M("Production")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "更新成功";
        }else{
            $back->status = 0;
            $back->info = "更新失败";
        }
        ajax_return($back);
    }
    
}

?>
