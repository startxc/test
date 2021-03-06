<?php

/**
 * @author bruce
 * @desc 商品管理
 */
class GoodsAction extends CommonAction {

    public function index() {
        //分类
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category",$category);

        $condition = array();
        $condition['is_deleted'] = 0;       
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
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category", $category); 

        //取得产地
        $production = A("Admin/Production")->getProductionList();
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

        //分类列表
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category", $category);  

        $goods_image = M("Goods_image")->where("goods_id={$goods['id']}")->select();
        $this->assign("goods_image",$goods_image);

        //产地列表
        $production = A("Admin/Production")->getProductionList();
        $this->assign("production", $production);

        //已选产地
        $goods_production = M("Goods_production")->where("goods_id={$id}")->select();
        $production_id = array();
        foreach($goods_production as $value){
            array_push($production_id,$value['production_id']);
        }
        $selected_production = M("Production")->where(array("id"=>array("in",$production_id)))->select();
        $this->assign("selected_production",$selected_production);

        $this->display();
    }

    public function insert() {
        !$this->isAjax() && $this->error("非法访问");
        $data = array();
        $time = time();

        $images = explode(",",trim($_POST['images'],","));
        $data['category_id'] = intval($_POST['cid']);
        $data['name'] = trim($_POST['name']);
        $data['price'] = $_POST['price'];
        $data['spec'] = intval($_POST['spec']);
        $data['spec_unit'] = trim($_POST['spec_unit']);
        $data['image'] = $images[0];
        $data['description'] = trim($_POST['desc']);
        $data['order_index'] = intval($_POST['order']);
        $data['create_time'] = $time;
        $data['is_show'] = intval($_POST['is_show']);
        $data['is_recommend'] = intval($_POST['is_recommend']);
        $data['is_group'] = intval($_POST['is_group']);

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
        $selected_production_id = explode(",",trim($_POST['selected_production_id'],","));
        $goods_production = array();
        foreach($selected_production_id as $key=>$value){
            $goods_production[$key]['goods_id'] = $id;
            $goods_production[$key]['production_id'] = $value;
            $goods_production[$key]['create_time'] = $time;
        }
        if(!M("Goods_production")->addAll($goods_production)){
            $back->status = 0;
            $back->info = "添加商品产地失败";
            $goods->rollback();
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
        $data['name'] = trim($_POST['name']);
        $data['price'] = $_POST['price'];
        $data['spec'] = intval($_POST['spec']);
        $data['spec_unit'] = trim($_POST['spec_unit']);
        $data['image'] = $images[0];
        $data['description'] = trim($_POST['desc']);
        $data['order_index'] = intval($_POST['order']);
        $data['is_show'] = intval($_POST['is_show']);
        $data['is_recommend'] = intval($_POST['is_recommend']);
        $data['is_group'] = intval($_POST['is_group']);
        $data['update_time'] = time();
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

        M("Goods_production")->where("goods_id={$id}")->delete();
        $selected_production_id = explode(",",trim($_POST['selected_production_id'],","));
        $goods_production = array();
        foreach($selected_production_id as $key=>$value){
            $goods_production[$key]['goods_id'] = $id;
            $goods_production[$key]['production_id'] = $value;
            $goods_production[$key]['create_time'] = $time;
        }
        if(!M("Goods_production")->addAll($goods_production)){
            $back->status = 0;
            $back->info = "修改商品产地失败";
            $goods->rollback();
            ajax_return($back); 
        }

        M("Goods_image")->where("goods_id={$id}")->delete();
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
        $sign = M("Goods")->where("id='{$id}'")->data(array('is_deleted' => 1))->save();
        if ($sign) {
            $this->success("添加到回收站成功!");
        } else {
            $this->error("添加到回收站失败!");
        }
    }


    public function recover() {
        //分类
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category",$category);

        $condition = array();
        $condition['is_deleted'] = 1;       
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
        $sign = M("Goods")->where("id='{$id}'")->data(array('is_deleted' => 0))->save();
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
        //$condition['is_show'] = 1; 
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

    //查看每一期的伙拼
    public function group_phase(){
        $group_id = intval($_GET['id']);
        $group = M("Group")->where("id={$group_id}")->find();
        if(empty($group)){
            $this->error("伙拼信息不存在");
            exit();
        }
        $group_phase = M("Group_phase")->where("group_id={$group_id}")->select();
        $this->assign("group",$group);
        $this->assign("group_phase",$group_phase);
        $this->display();
    }

    //发起新的一期伙拼
    public function addGroupPhase(){
        $group_id = intval($_GET['id']);
        $group = M("Group")->where("id={$group_id}")->find();
        if(empty($group)){
            $this->error("伙拼不存在");
            exit();
        }
        if($group['end_time']>time()){
            $this->error("最新一期的伙拼时间还未结束");
            exit();
        }
        $time = time();
        $group_phase = M("Group_phase");
        $group_phase->startTrans();
        $group_phase->where("id={$group['group_phase_id']}")->save(array("is_finish"=>1,"finish_time"=>$time));
        $start_time = strtotime(date("Y-m-d")." 00:00:00");
        $end_time = strtotime(date("Y-m-d",strtotime("+3 days"))." 23:59:59");
        $group_phase_data = array(
            "group_id"=>$group['id'],
            "price"=>$group['price'],
            "min_price"=>$group['min_price'],
            "min_price_spec"=>$group['min_price_spec'],
            "real_price"=>$group['price'],
            "moq_spec"=>$group['moq_spec'],
            "start_time"=>$start_time,
            "end_time"=>$end_time,
            "create_time"=>$time,
            "update_time"=>$time
        );
        if(!$group_phase_id = $group_phase->add($group_phase_data)){
            $group_phase->rollback();
            $this->error("发起新的伙拼失败");
            exit();
        }
        $group_data = array(
            "real_price"=>$group['price'],
            "sale_count"=>0,
            "sale_spec"=>0,
            "start_time"=>$start_time,
            "end_time"=>$end_time,
            "create_time"=>$time,
            "update_time"=>$time,
            "group_phase_id"=>$group_phase_id
        );
        if(!M("Group")->where("id={$group['id']}")->save($group_data)){
            $group_phase->rollback();
            $this->error("发起新的伙拼失败");
            exit();
        }
        $group_phase->commit();
        $this->success("发起新的伙拼成功");
    }

    //编辑伙拼信息
    public function groupEdit(){
        $id = intval($_GET['id']);
        $group = M("Group")->where("id={$id}")->find();
        if(empty($group)){
            $this->error("要编辑的伙拼信息不存在");
        }
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category", $category); 

        $production = A("Admin/Production")->getProductionList();
        $this->assign("production", $production);

        $this->assign("info",$group);
        $this->display();
    }

    //修改伙拼信息
    public function groupUpdate(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $time = time();
        $data = array();
        $data['price'] = $_POST['price'];
        $data['name'] = trim($_POST['name']);
        $data['category_id'] = intval($_POST['category_id']);
        $data['production_id'] = intval($_POST['production_id']);
        $data['image'] = trim($_POST['image']);
        $data['moq_spec'] = intval($_POST['moq_spec']);
        $data['min_price'] = $_POST['min_price'];
        $data['min_price_spec'] = $_POST['min_price_spec'];
        $data['start_time'] = strtotime("{$_POST['start_time']} 00:00:00");
        $data['end_time'] = strtotime("{$_POST['end_time']} 23:59:59");
        $data['is_show'] = intval($_POST['is_show']);
        $data['is_recommend'] = intval($_POST['is_recommend']);
        $data['update_time'] = $time;

        $back = new stdClass();
        $group = M("Group");
        $group->startTrans();
        if(!$group->where("id={$id}")->save($data)){
            $back->status = 0;
            $back->info = "修改失败";
             ajax_return($back);
        }
        $group_phase_id = $group->where("id={$id}")->getField("group_phase_id");
        $group_phase_data = array(
            "price"=>$data['price'],
            "min_price"=>$data['min_price'],
            "min_price_spec"=>$data['min_price_spec'],
            "moq_spec"=>$data['moq_spec'],
            "start_time"=>$data['start_time'],
            "end_time"=>$data['end_time'],
            "update_time"=>$time
        );
        if(!M("Group_phase")->where("id={$group_phase_id}")->save($group_phase_data)){
            $back->status = 0;
            $back->info = "修改失败";
            $group->rollback();
            ajax_return($back);
        }
        $back->status = 1;
        $back->info = "修改成功";
        $group->commit();
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
        $category = A("Admin/Category")->getCategoryList();
        $this->assign("category", $category); 

        $production = A("Admin/Production")->getProductionList();
        $this->assign("production", $production);

        $this->assign("groupApply",$groupApply);
        $this->display();
    }

    //伙拼审核通过
    public function passGroupApply(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $time = time();
        $goods_id = intval($_POST['goods_id']);
        $back = new stdClass();
        if(M("Group")->where("goods_id={$goods_id}")->find()){
            $back->status = 0;
            $back->info = "已经存在该商品的伙拼信息啦";
            ajax_return($back);
        }
        $spec_unit = M("Goods")->where("id={$goods_id}")->getField("spec_unit");
        $group_data = array(
            "goods_id"=>$goods_id,
            "category_id"=>intval($_POST['category_id']),
            "production_id"=>intval($_POST['production_id']),
            "name"=>trim($_POST['name']),
            "price"=>$_POST['price'],
            "min_price"=>$_POST['min_price'],
            "min_price_spec"=>$_POST['min_price_spec'],
            "real_price"=>$_POST['price'],
            "image"=>trim($_POST['image']),
            "description"=>trim($_POST['desc']),
            "moq_spec"=>intval($_POST['moq_spec']),
            "spec_unit"=>$spec_unit,
            "start_time"=>strtotime("{$_POST['start_time']} 00:00:00"),
            "end_time"=>strtotime("{$_POST['end_time']} 23:59:59"),
            "create_time"=>$time,
            "update_time"=>$time
        );
        $group = M("Group");
        $group->startTrans();
        if(!$group_id = $group->add($group_data)){
            $back->status = 0;
            $back->info = "操作失败";
            ajax_return($back);
        }
        $group_phase_data = array(
            "group_id"=>$group_id,
            "price"=>$_POST['price'],
            "min_price"=>$_POST['min_price'],
            "min_price_spec"=>$_POST['min_price_spec'],
            "real_price"=>$_POST['price'],
            "moq_spec"=>intval($_POST['moq_spec']),
            "start_time"=>strtotime("{$_POST['start_time']} 00:00:00"),
            "end_time"=>strtotime("{$_POST['end_time']} 23:59:59"),
            "create_time"=>$time,
            "update_time"=>$time
        );
        if(!$group_phase_id = M("Group_phase")->add($group_phase_data)){
            $back->status = 0;
            $back->info = "操作失败";
            $group->rollback();
            ajax_return($back);
        }
        if(!M("Group")->where("id={$group_id}")->setField("group_phase_id",$group_phase_id)){
            $back->status = 0;
            $back->info = "操作失败";
            $group->rollback();
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

    //一周菜谱
    public function weekmenu(){
        $weekmenu = M("Weekmenu")->select();
        $weekmenuList = array(
            1=>array(),
            2=>array(),
            3=>array(),
            4=>array(),
            5=>array(),
            6=>array(),
            7=>array()
        );
        foreach($weekmenu as $value){
            switch($value['week']){
                case 1:$weekmenuList[1][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 2:$weekmenuList[2][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 3:$weekmenuList[3][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 4:$weekmenuList[4][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 5:$weekmenuList[5][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 6:$weekmenuList[6][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
                case 7:$weekmenuList[7][] = array("id"=>$value['id'],"goods_id"=>$value['goods_id'],"goods_name"=>$value['goods_name']);break;
            }
        }
        $count = M("Goods")->where("is_show=1 && is_deleted=0")->count();
        $this->assign("totalPage",ceil($count/10));
        $this->assign("weekmenuList",$weekmenuList);
        $this->display();
    }

    //添加商品到菜谱
    public function addGoodsToMenu(){
        !$this->isAjax() && $this->error("非法访问");
        $goods_id = intval($_POST['goods_id']);
        $week = intval($_POST['week']);
        $back = new stdClass();
        if(M("Weekmenu")->where("goods_id={$goods_id} && week={$week}")->find()){
            $back->status = 0;
            $back->info = "该商品已经添加过了";
            ajax_return($back);
        }
        if(!$goods = M("Goods")->where("id={$goods_id}")->find()){
            $back->status = 0;
            $back->info = "要添加的商品不存在";
            ajax_return($back);
        }
        $data = array(
            "goods_id"=>$goods_id,
            "goods_name"=>$goods['name'],
            "goods_image"=>$goods['image'],
            "week"=>$week,
            "create_time"=>time()
        );
        if($id = M("Weekmenu")->add($data)){
            $back->status = 1;
            $back->id = $id;
            $back->goods_name = $data['goods_name'];
            $back->goods_id = $data['goods_id'];
            ajax_return($back);
        }else{
            $back->status = 0;
            $back->info = "添加失败";
            ajax_return($back);
        }
    }

    //从菜谱中删除商品
    public function deleteGoodsFromMenu(){
        !$this->isAjax() && $this->error("非法访问");
        $id = intval($_POST['id']);
        $back = new stdClass();
        if(M("Weekmenu")->where("id={$id}")->delete()){
            $back->status = 1;
            $back->info = "删除成功";
        }else{
            $back->status = 0;
            $back->info = "删除失败";
        }
        ajax_return($back);
    }
}

?>
