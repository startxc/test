<?php
/*
 * 后台商品评论管理
 * @access   public
 * @author   Lyon
 * @version  CommentAction.calss.php 2013-09-26
 * 
 */
class CommentAction extends CommonAction {
    /*
    * 评论列表
    */
    public function index() {
        $where = "parent_id = 0";
        // 是否回复      
        if(!empty($_GET['reply'])) {
            $reply = intval($_GET['reply']);
            $where .= " AND is_reply='{$reply}'";
            
        }
        // 是否显示
        if(!empty($_GET['show'])) {
            $show = intval($_GET['show']);
            $where .= " AND is_show='{$show}'";
            
        }
        // 搜索
        if(!empty($_GET['keywords'])) {
            $keywords = trim($_GET['keywords']);
            $where .= " AND member_name like '%{$keywords}%' ";
        }
        $this->assign("reply", intval($_GET['reply']));
        $this->assign("show", intval($_GET['show']));
        
        $model = M('goods_comment');
        $count = $model->where($where)->count('id');          
        
        if($count>0) {
            import("@.ORG.Util.Page");
            if (!empty($_REQUEST ['listRows'])) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = '20';
            }
            $p = new Page($count, $listRows);
            $page = $p->show();

            $list = $model->limit($p->firstRow . ',' . $p->listRows)
                          ->where($where)
                          ->order("create_time DESC")
                          ->field('id,member_name,content,goods_id,is_reply,is_show,type,create_time')
                          ->select();           
        
            $goods = M('goods');
            foreach ($list as $key => $val) {
                $list[$key]['goods_name'] = $goods->where("id = '{$val['goods_id']}'")->getField('goods_name');
            } 
            $this->assign("list", $list);                  
        }
             
        $this->assign("page", $page);      
        $this->display();
    }
    
    //编辑
    public function edit() {
        $member = M('member');
        $id = intval($_GET['id']);
        if (empty($id)) {
            $this->error(L('OPERATION_WRONG'));
        }
        $comment = M('goods_comment');
        $row = $comment->where("id = '$id' or parent_id = $id")->select();
        $goods = M('goods');
        foreach ($row as $key => $val) {
            $row[$key]['goods_name'] = $goods->where("id = '{$val['goods_id']}'")->getField("goods_name");
        }
      
        $this->assign('row',$row);
        $this->assign('goods_name',$goods_name);       
        $this->display();

    }

    public function saveReply() {
        //exit;
        $data = array();
        $data['create_time'] = time();
        $id = intval($_POST['id']);
        $data['content'] = addslashes($_POST['content']);       
        $comment = M('goods_comment');
        $reply = $comment->where("id = '$id'")->getField("is_reply");
        $back = new stdClass();
        if ($reply == 2) {
            $rid = $comment->where("parent_id = '$id'")->order("id DESC")->field('id')->find();; 
            $comment->where("id = '{$rid['id']}'")->save($data);
            $back->status = 1;
            ajax_return($back);
        } else {                        
            $data['type'] = 1;
            $data['parent_id'] = $id;
            $data['member_name'] = "管理员";
            $comment->add($data);
            $comment->where("id = '$id'")->setField("is_reply", 2);
            $back->status = 1;
            ajax_return($back);
        }
    }

    public function setShowStatus() {
        $map = array();
        $map['id'] = intval($_POST['id']);
        $map['is_show'] = intval($_POST['show']);

        $back = new stdClass();
        if (empty($map['id']) || empty($map['is_show'])) {
            $back->status = 0;
            $back->prompt = "参数错误";
            ajax_return($back);
        }

        $table = trim($_POST['model']);
        $res = M($table)->save($map);
        $source = $_POST['source'];
        $works_id = intval($_POST['ref_id']);
        if($table == "comment" and $source == 'works'){
            //1显示评论，2不显示评论
            if($map['is_show'] == 1){
                M('Works')->where("id={$works_id}")->setInc("comment_count");
            }else if($map['is_show'] == 2){
                M('Works')->where("id={$works_id}")->setDec("comment_count");
            }
        }
        if (!$res) {
            $back->status = 0;
            $back->prompt = '参数错误';            
        } else {
            $back->status = 1;
        }

        ajax_return($back);
    }
    // 用户评论
    public function userComment() {
        $model = M('comment');
        $map = array();
        //$map['source'] = 1;

        // 是否显示
        if(!empty($_GET['show'])) {
            $show = intval($_GET['show']);
            $map['is_show'] = $show;
            
        }
        // 搜索
        if(!empty($_GET['keywords'])) {
            $keywords = trim($_GET['keywords']);
            $map['content'] = array("like",'%'.$keywords.'%');
        }
        
        $count = $model->where($map)->count();
        if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count,20);
            $page = $p->show();
            $list = $model->where($map)->limit($p->firstRow.','.$p->listRows)->order('create_time DESC')->select();
            //echo $model->getLastSql();
            //dump($list);
        }

        $this->assign("page",$page);
        $this->assign("list",$list);       
        $this->display();
    }    
}

?>