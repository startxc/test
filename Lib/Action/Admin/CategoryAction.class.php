<?php

/**
 * 商品分类管理
 *
 * @author bruce
 */
class CategoryAction extends CommonAction {

    //分类列表
    public function index() {
        $list = array();
        $list = M("Category")->order("order_index desc")->select();
        $this->assign("list", $list);
        $this->display();
    }

    //添加分类页面
    public function add() {
        $this->display();
    }

    //编辑分类页面
    public function edit() {
        $id = intval($_GET['id']);
        $info = M("Category")->where("id={$id}")->find();
        $this->assign("info", $info);
        $this->display();
    }

    //写入分类数据
    public function insert() {
        !$this->isAjax() && $this->error("非法访问");
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['order_index'] = intval($_POST['order_index']);
        $data['is_show'] = intval($_POST['is_show']);
        $data['image'] = trim($_POST['image']);
        $data['create_time'] = time();
        $category = M("Category");
        $back = new stdClass();
        if ($category->add($data)) {
            $back->status = 1;
            $back->info = "添加成功";
        } else {
            $back->status = 0;
            $back->info = "添加失败";
        }
        ajax_return($back);
    }

    //更新分类数据
    public function update() {
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['is_show'] = intval($_POST['is_show']);
        $data['order_index'] = intval($_POST['order_index']);
        $data['image'] = trim($_POST['image']);
        $data['create_time'] = time();
        $back = new stdClass();
        if(M("Category")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "更新成功";
        }else{
            $back->status = 0;
            $back->info = "更新失败";
        }
        ajax_return($back);
    }

    //删除分类
    public function delete() {
        $id = empty($_GET['id']) ? $this->error('删除失败，传递参数有误') : intval($_GET['id']);
        //全部子类

        $info = M("Category")->where("id={$id}")->find();

        //删除分类图片
        import("@.ORG.Util.UploadFile");
        $imgpath =  UploadFile::getPicturePath("product", $info['image']);
        file_exists($imgpath) && @unlink($imgpath);

        if(M("Category")->where("id={$id}")->delete()){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }

    //更新分类排序
    public function updateSort(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['order_index'] = intval($_POST['order_index']);
        $back = new stdClass();
        if(M("Category")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "更新成功";
        }else{
            $back->status = 0;
            $back->info = "更新失败";
        }
        ajax_return($back);
    }

    //获得分类列表
    public function getCategoryList(){
        $category = M("Category")->where("is_show=1")->order("order_index desc")->select();
        return $category;
    }
    
}

?>
