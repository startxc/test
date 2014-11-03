<?php

/*
 * 系统主菜单文件
 * author: kewen
 */

class MenuAction extends CommonAction {

    public function index() {
        $action = $this->getActionName();
        $list = D($action)->select();
        $this->assign("list", $list);
        Cookie::set('_currentUrl_', __SELF__);
        $this->display();
    }

    public function add() {
        $node = D("Node");
        $list = $node->where('pid=1')->field('id,title,name')->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function edit() {
        $id = intval($_GET['id']);
        if (!empty($id)) {
            $action = $this->getActionName();
            $info = D($action)->where('id=' . $id)->find();
            $child = explode(',', $info['child']);
            $this->assign('info', $info);
            $this->assign('child', $child);
            $node = D("Node");
            $list = $node->where('pid=1')->field('id,title,name')->select();
            $this->assign('list', $list);
            $this->display();
        } else {
            $this->error('传递参数有误');
        }
    }

    function insert() {
        $name = $this->getActionName();
        $model = D($name);
        $obj = new stdClass();
        $obj->name = trim($_POST['name']);
        $obj->url = trim($_POST['url']);
        $obj->child = implode(',', $_POST['menu']);
        $obj->status = intval($_POST['status']);
        if (false === $model->addMenu($obj)) {
            $this->error('新增菜单失败');
        } else {
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('新增菜单成功!');
        }
    }
    
    function update() {
        $name = $this->getActionName();
        $model = D($name);
        $obj = new stdClass();
        $obj->id = intval($_POST['id']);
        $obj->name = trim($_POST['name']);
        $obj->url = trim($_POST['url']);
        $obj->child = implode(',', $_POST['menu']);
        $obj->status = intval($_POST['status']);
        if (false === $model->setMenu($obj)) {
            $this->error('更改菜单失败');
        } else {
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('更改菜单成功!');
        }
    }
}

?>