<?php
/*
 * @author   Bruce
 * @desc 后台会员管理
 */
class MemberAction extends CommonAction {

    public function index() {       
        $map = array("status"=>1);  
        $member_type = $_REQUEST['member_type'];
        if(!empty($member_type)) {
           $map['member_type'] = $member_type;
        }       
        $keyword = trim($_REQUEST['keywords']);
        if(!empty($keyword)) {
            $map['mobile'] = array('like', '%'.$keyword.'%');
            $map['nickname'] = array('like', '%'.$keyword.'%');
            $map['_logic'] = 'or';
        }
        //会员列表
        $member = M('member');
        $count = $member->where($map)->count('id'); 
        if($count>0) {
            import("@.ORG.Util.Page");
            $listRows = '20';
            $p = new Page($count, $listRows);
            $page = $p->show();
            if (!empty($map)) {
                $list = $member->where($map)->limit($p->firstRow . ',' . $p->listRows)->order("create_time DESC")->select();                
            } else {
                $list = $member->limit($p->firstRow . ',' . $p->listRows)->order("create_time DESC")->select();
            }
        } 
        
        $this->assign("member_type", $member_type);
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display();
    }
    

    
    //添加
    public function add() {
        if ($_POST['sub'] == "add") {
            $info = $_POST['info'];
            $member = M('Member');    
            $member->startTrans();       
            $result = $member->where("mobile = '{$info['mobile']}'")->field('id')->find();         
            if ($result) {
                $this->error("该手机号码已存在");
                return false;
            }
            $info['province_name'] = M("Region")->where("id={$info['province_id']}")->getField("name");
            $info['city_name'] = M("Region")->where("id={$info['city_id']}")->getField("name");
            $info['area_name'] = M("Region")->where("id={$info['area_id']}")->getField("name");
            $info['create_time'] = time();
            $info['password'] = md5('123456');

        
            $id = $member->add($info);
            if (!$id) {                
                $this->error("添加失败");
                return false;
            }
            $address_data = array(
                "member_id"=>$id,
                "consignee"=>$info['nickname'],
                "province_id"=>$info['province_id'],
                "city_id"=>$info['city_id'],
                "area_id"=>$info['area_id'],
                "province_name"=>$info['province_name'],
                "city_name"=>$info['city_name'],
                "area_name"=>$info['area_name'],
                "address"=>$info['address'],
                "mobile"=>$info['mobile'],
                "is_default"=>1,
                "create_time"=>time()
            );
            if(M("Member_address")->add($address_data)){
                $member->commit();
                $this->success("添加成功",__URL__);
                return false;
            }else{
                $this->error("添加失败");
                return false;
            }
        } else {            
            $model = D('Member');           
            $provinceList = $model->getAllRegions();  
            $this->assign("regionjson",json_encode($provinceList));
            $this->display();
        }
    }

    //编辑
    public function edit() {
        $member = M('member');
        if ( $_POST['sub'] == "edit") {
            $info = $_POST['info'];
            if($info['old_mobile'] != $info['mobile']){
                $result = $member->where("mobile = '{$info['mobile']}'")->field('id')->find();         
                if ($result) {
                    $this->error("该手机号码已存在");
                    return false;
                }
            } 
            $info['province_name'] = M("Region")->where("id={$info['province_id']}")->getField("name");
            $info['city_name'] = M("Region")->where("id={$info['city_id']}")->getField("name");
            $info['area_name'] = M("Region")->where("id={$info['area_id']}")->getField("name");        
            $info['update_time'] = time();
            $memSave = $member->where("id = '{$info['id']}'")->save($info);
            if ($memSave === false) {
                $this->error("修改失败");  
                return false;  
            }
            $address_data = array(
                "province_id"=>$info['province_id'],
                "city_id"=>$info['city_id'],
                "area_id"=>$info['area_id'],
                "province_name"=>$info['province_name'],
                "city_name"=>$info['city_name'],
                "area_name"=>$info['area_name'],
                "address"=>$info['address'],
                "mobile"=>$info['mobile'],
            );
            if(M("Member_address")->where("member_id={$info['id']} && is_default=1")->save($address_data)){
                $this->success("修改成功"); 
                return false; 
            }else{
                $this->error("修改失败");  
                return false;  
            }          
        } else {           
            $model = D('Member');           
            $provinceList = $model->getAllRegions();  
            $this->assign("regionjson",json_encode($provinceList));
            $id = intval($_GET['id']);
            if (empty($id)) {
                $this->error(L('OPERATION_WRONG'));
                return false;
            }
            $info = $member->where("id = '$id'")->find();
            $this->assign('info',$info);
            $this->display();
        }
    }    



    //回收站
    public function recycle() {
        $id = intval($_GET['id']);
        if(empty($id)) {
            $this->error(L('OPERATION_WRONG'));
        }
        $sign = M("Member")->where("id='{$id}'")->data(array('status'=>2))->save();
        if($sign) {
            $this->success("添加到回收站成功!");
        }else {
            $this->error("添加到回收站失败!");
        }
    }
    // 会员回收站
    public function recoverMember() {
        $map = array("status"=>2);  
        $member_type = $_REQUEST['member_type'];
        if(!empty($member_type)) {
           $map['member_type'] = $member_type;
        }       
        $keyword = trim($_REQUEST['keywords']);
        if(!empty($keyword)) {
            $map['mobile'] = array('like', '%'.$keyword.'%');
            $map['nickname'] = array('like', '%'.$keyword.'%');
            $map['_logic'] = 'or';
        }
        //会员列表
        $member = M('member');
        $count = $member->where($map)->count('id'); 
        if($count>0) {
            import("@.ORG.Util.Page");
            if (!empty($_REQUEST ['listRows'])) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = '20';
            }
            $p = new Page($count, $listRows);
            $page = $p->show();
            if (!empty($map)) {
                $list = $member->where($map)->limit($p->firstRow . ',' . $p->listRows)->order("create_time DESC")->select();                
            } else {
                $list = $member->limit($p->firstRow . ',' . $p->listRows)->order("create_time DESC")->select();
            }
        } 
        
        $this->assign("member_type", $member_type);
        $this->assign("page", $page);
        $this->assign("list", $list);              
        $this->display('recover');     
    }

    // 还原
    public function recover() {
        $id = intval($_GET['id']);
        if(empty($id)) {
            $this->error(L("操作错误"));
        }
        $sign = M("member")->where("id='{$id}'")->data(array('status'=>1))->save();
        if($sign) {
            $this->success("还原成功!");
        }else {
            $this->error("还原失败!");
        } 
    }

    // 删除
    public function delete() {
        $id = intval($_GET['id']);
        if(empty($id)) {
            $this->error("操作错误");
        }
        // 其他表的信息
        $sign = M("member")->where("id='{$id}'")->data(array('status'=>-1))->save();
        if($sign) {
            $this->success("删除成功!");
        }else {
            $this->error("删除失败!");
        }
    }
    
}
?>