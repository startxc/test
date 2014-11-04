<?php

/**
 * @author bruce
 * @desc 商品管理
 */
class GoodsAction extends CommonAction {

    public function index() {
        //分类
        $category = M("Category")->order("order_index desc")->select();
        $this->assign("category",$category);

        $condition = array();
        $condition['deleted'] = 0;       
        $cid = intval($_GET['cid']);
        if($cid != 0){
            $condition['category_id'] = $cid;
            $this->assign("cid",$cid);
        }
        $keywords = trim($_GET['keywords']);
        if(!empty($keywords)){
            $condition['name'] = array("like","%{$keywords}%");
        }

        //商品列表
        $Goods = M("Goods");
        $count = $Goods->where($condition)->count('id');
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $listRows = 20;
            $p = new Page($count, $listRows);
            $page = $p->show();
            $goodsList = $Goods->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();
        }

        $this->assign("page", $page);
        $this->assign("list", $goodsList);
        $this->display();
    }

    //添加商品页面
    public function add() {
        //取得分类        
        $category = M("Category")->field("id,name")->order("order_index desc")->select();
        $this->assign("category", $category); 

        //取得产地
        $production = M("Production")->field("id,name")->order("order_index desc")->select();
        $this->assign("production", $production);   
        $this->display();
    }
    

    public function edit() {
        $id = intval($_GET['id']);

        $goods = M("Goods")->where("id={$id}")->find();
        if(empty($goods)){
            $this->error("要修改的商品不存在");
            exit;
        }
        $this->assign("goods",$goods);

        $category = M("Category")->order("order_index desc")->select();
        $this->assign("category", $category);  

        $goods_image = M("Goods_image")->where("goods_id={$goods['id']}")->select();
        $this->assign("goods_image",$goods_image);

        //取得产地
        $production = M("Production")->field("id,name")->order("order_index desc")->select();
        $this->assign("production", $production);

        $this->display();
    }

    public function insert() {
        !$this->isAjax() && $this->error("非法访问");
        $data = array();
        $time = time();

        $images = explode(",",trim($_POST['images'],","));
        $data['category_id'] = intval($_POST['cid']);
        $data['production_id'] = intval($_POST['pid']);
        $data['name'] = trim($_POST['name']);
        $data['price'] = $_POST['price'];
        $data['spec'] = intval($_POST['spec']);
        $data['image'] = $images[0];
        $data['description'] = trim($_POST['desc']);
        $data['order_index'] = intval($_POST['order']);
        $data['create_time'] = $time;
        $data['status'] = intval($_POST['status']);

        $back = new stdClass();
        $goods = M("Goods");
        $goods->startTrans();

        if($goods->where("name='{$data['name']}'")->find()){
            $back->status = 1;
            $back->info = "商品名称已经存在";
            ajax_return($back);
        }

        $id = $goods->add($data);
        if(!$id){
            $back->status = 0;
            $back->info = "添加商品失败";
            ajax_return($back);
        }

        $goods_image = array();
        foreach($images as $key=>$value){
            $goods_image[$key]['goods_id'] = $id;
            $goods_image[$key]['image'] = $value;
            $goods_image[$key]['create_time'] = $time;
        }
        if(!M("Goods_image")->addAll($goods_image)){
            $back->status = 0;
            $back->info = "添加商品图片失败";
            $goods->rollback();
            ajax_return($back);  
        }

        $back->status = 2;
        $back->info = "添加商品成功";
        $goods->commit();
        ajax_return($back);
    }

    public function update() {
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $old_name = trim($_POST['old_name']);

        $data = array();
        $time = time();

        $images = explode(",",trim($_POST['images'],","));
        $data['category_id'] = intval($_POST['cid']);
        $data['production_id'] = intval($_POST['pid']);
        $data['name'] = trim($_POST['name']);
        $data['price'] = $_POST['price'];
        $data['spec'] = intval($_POST['spec']);
        $data['image'] = $images[0];
        $data['description'] = trim($_POST['desc']);
        $data['order_index'] = intval($_POST['order']);
        $data['status'] = intval($_POST['status']);

        $back = new stdClass();
        $goods = M("Goods");
        $goods->startTrans();

        if($old_name != $data['name']){ 
            if($goods->where("name='{$data['name']}'")->find()){
                $back->status = 1;
                $back->info = "商品名称已经存在";
                ajax_return($back);
            }
        }

        $sign = $goods->where("id={$id}")->save($data);
        if(!$sign){
            $back->status = 0;
            $back->info = "修改商品失败";
            ajax_return($back);
        }
        if(!M("Goods_image")->where("goods_id={$id}")->delete()){
            $back->status = 0;
            $back->info = "修改商品图片失败";
        }
        $goods_image = array();
        foreach($images as $key=>$value){
            $goods_image[$key]['goods_id'] = $id;
            $goods_image[$key]['image'] = $value;
            $goods_image[$key]['create_time'] = $time;
        }
        if(!M("Goods_image")->addAll($goods_image)){
            $back->status = 0;
            $back->info = "修改商品图片失败";
            $goods->rollback();
            ajax_return($back);  
        }

        $back->status = 2;
        $back->info = "修改商品成功";
        $goods->commit();
        ajax_return($back);
    }

    public function recycle() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->error("非法操作!");
        }
        $sign = M("Goods")->where("id='{$id}'")->data(array('deleted' => 1))->save();
        if ($sign) {
            $this->success("添加到回收站成功!");
        } else {
            $this->error("添加到回收站失败!");
        }
    }


    public function recover() {
        //分类
        $category = M("Category")->order("order_index desc")->select();
        $this->assign("category",$category);

        $condition = array();
        $condition['deleted'] = 1;       
        $cid = intval($_GET['cid']);
        if($cid != 0){
            $condition['category_id'] = $cid;
            $this->assign("cid",$cid);
        }
        $keywords = trim($_GET['keywords']);
        if(!empty($keywords)){
            $condition['name'] = array("like","%{$keywords}%");
        }

        //商品列表
        $Goods = M("Goods");
        $count = $Goods->where($condition)->count('id');
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $listRows = 20;
            $p = new Page($count, $listRows);
            $page = $p->show();
            $goodsList = $Goods->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();
        }

        $this->assign("page", $page);
        $this->assign("list", $goodsList);
        $this->display();
    }

    public function revert() {
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->error("非法操作!");
        }
        $sign = M("Goods")->where("id='{$id}'")->data(array('deleted' => 0))->save();
        if ($sign) {
            $this->success("还原成功!");
        } else {
            $this->error("还原失败!");
        }
    }


    //更新分类排序
    public function updateSort(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['order_index'] = intval($_POST['order_index']);
        $back = new stdClass();
        if(M("Goods")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "更新成功";
        }else{
            $back->status = 0;
            $back->info = "更新失败";
        }
        ajax_return($back);
    }  


    //伙拼管理
    public function group(){
        $condition = array();
        $condition['status'] = 1; 
        $keywords = trim($_GET['keywords']);
        if(!empty($keywords)){
            $condition['name'] = array("like","%{$keywords}%");
        }

        //伙拼列表
        $group = M("Group");
        $count = $group->where($condition)->count('id');
        $list = array();
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $listRows = 20;
            $p = new Page($count, $listRows);
            $page = $p->show();
            $list = $group->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();
        }


        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display();
    }  

    //编辑伙拼信息
    public function groupEdit(){
        $id = intval($_GET['id']);
        $group = M("Group")->where("id={$id}")->find();
        if(empty($group)){
            $this->error("要编辑的伙拼信息不存在");
        }
        $category = M("Category")->order("order_index desc")->select();
        $this->assign("category", $category); 

        $production = M("Production")->field("id,name")->order("order_index desc")->select();
        $this->assign("production", $production);

        $this->assign("info",$group);
        $this->display();
    }

    //修改伙拼信息
    public function groupUpdate(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['category_id'] = intval($_POST['category_id']);
        $data['production_id'] = intval($_POST['production_id']);
        $data['image'] = trim($_POST['image']);
        $data['order_moq'] = intval($_POST['order_moq']);
        $data['start_time'] = strtotime("{$_POST['start_time']} 00:00:00");
        $data['end_time'] = strtotime("{$_POST['end_time']} 23:59:59");
        $data['status'] = intval($_POST['status']);

        $back = new stdClass();
        if(M("Group")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "修改成功";
        }else{
            $back->status = 0;
            $back->info = "修改失败";
        }
        $back->sql = M("Group")->getLastSql();
        ajax_return($back);
    }

    //伙拼申请管理
    public function groupApply(){
        $condition = array();      
        $condition['status'] = 1;
        $keywords = trim($_GET['keywords']);
        if(!empty($keywords)){
            $condition['name'] = array("like","%{$keywords}%");
        }

        //伙拼申请列表
        $groupApply = M("Group_apply");
        $count = $groupApply->where($condition)->count('id');
        $list = array();
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $listRows = 20;
            $p = new Page($count, $listRows);
            $page = $p->show();
            $list = $groupApply->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();
        }

        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display();
    }

    //伙拼审核管理审核页面
    public function groupApplyCheck(){
        $id = intval($_GET['id']);
        $groupApply = M("Group_apply")->where("id={$id}")->find();
        if(empty($groupApply)){
            $this->error("要审核的信息不存在");
            exit;
        }
        $category = M("Category")->order("order_index desc")->select();
        $this->assign("category", $category); 

        $production = M("Production")->field("id,name")->order("order_index desc")->select();
        $this->assign("production", $production);

        $this->assign("groupApply",$groupApply);
        $this->display();
    }

    //伙拼审核通过
    public function passGroupApply(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $time = time();
        $group_data = array(
            "goods_id"=>intval($_POST['goods_id']),
            "category_id"=>intval($_POST['category_id']),
            "production_id"=>intval($_POST['production_id']),
            "name"=>trim($_POST['name']),
            "image"=>trim($_POST['image']),
            "order_moq"=>intval($_POST['order_moq']),
            "start_time"=>strtotime("{$_POST['start_time']} 00:00:00"),
            "end_time"=>strtotime("{$_POST['end_time']} 23:59:59"),
            "create_time"=>$time
        );
        $group = M("Group");
        $group->startTrans();
        $back = new stdClass();
        if(!$group_id = $group->add($group_data)){
            $back->status = 0;
            $back->info = "操作失败";
            ajax_return($back);
        }
        $group_apply_data = array(
            "group_id"=>$group_id,
            "status"=>3
        );
        if(!M("Group_apply")->where("id={$id}")->save($group_apply_data)){
            $back->status = 0;
            $back->info = "操作失败";
            $group->rollback();
            ajax_return($back);
        }
        $back->status = 1;
        $back->info = "操作成功";
        $group->commit();
        ajax_return($back);

    }

    //伙伴审核不通过
    public function notPassGroupApply(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $back = new stdClass();
        $data = array("status"=>2);
        if(M("Group_apply")->where("id={$id}")->save($data)){
            $back->status = 1;
            $back->info = "操作成功";
        }else{
            $back->status = 0;
            $back->info = "操作失败";
        }
        ajax_return($back);
    }
}

?>
