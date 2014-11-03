<?php
/**
 * 作品模型文件
 * @author bruce
 */
class WorksModel extends CommonModel{
    //作品评论信息
    public function getCommentInfo($id){
        $condition = array(
                         'ref_id'=>$id,
                         'parent_id'=>0,
                         'is_show'=>1,
                         'source'=>'works',
                         'type'=>0
        );
        $count = M('Comment')->where($condition)->count();
        $work_comment = array();
        if (!empty($_REQUEST ['listRows'])) {
            $listRows = $_REQUEST ['listRows'];
        } else {
            $listRows = 10;
        }
        if($count>0){
            import("@.ORG.Util.Page");
            $p = new Page($count, $listRows);
            //$page = $p->show();
            $work_comment = M('Comment')->field('id,member_id,member_name,title,content,avatar,create_time')
                                        ->where($condition)
                                        ->order('create_time asc')
                                        ->limit($p->firstRow . ',' . $p->listRows)
                                        ->select();
            foreach($work_comment as $val){
                   $pid .= $val['id'].',';
            }
            $pid = rtrim($pid,',');
            $work_comment = $this->getSubComment($work_comment,$pid);
        }
        
        $current_page = intval($_GET['p']);
        $current_page = empty($current_page)?1:$current_page;
        $total_page = ceil($count/$listRows);
        if($total_page>1 && $current_page > $total_page) $current_page = $total_page;
        if($current_page == 1){
            $prev_page = 1;
            $next_page = $current_page + 1;
        }elseif($current_page == $total_page){
            $prev_page = $current_page - 1;
            $next_page = $current_page;
        }else{
            $prev_page = $current_page - 1;
            $next_page = $current_page + 1;
        }        
        $comment_info = array(
          'work_comment'=>$work_comment, 
          'prev_page'=>$prev_page,
          'next_page'=>$next_page
        );
        return $comment_info;
    }
    
    //作品子评论信息
    public function getSubComment($data,$pid){
        $comment = array();
        $condition = array(
                         'parent_id'=>array('in',$pid),
                         'is_show'=>1,
                         'source'=>'works',
                         'type'=>0
        );
        $child = M('Comment')->where($condition)
                             ->order("create_time asc")
                             ->field('id,parent_id,member_id,content,member_name,reply_to_name,avatar,create_time')
                             ->select();
        $condition = array('parent_id'=>array('in',$pid));
        $comment_attachment = M('Comment_attachment')->where($condition)->select();
        foreach ($data as $key => $val) {
                $comment[$key]['reply'] = $val;
                if (!empty($child)) {
                        foreach($child as $k => $v) {
                                //$cid .= $v['member_id'].",";
                                $v['attachment'] = M('Comment_attachment')->where("comment_id=".$v['id'])->select();
                                if ($val['id'] == $v['parent_id']) {
                                        $comment[$key]['child'][] = $v;
                                        unset($child[$k]);				
                                } 						
                        }					
                }
                if(!empty($comment_attachment)){
                    foreach($comment_attachment as $k=>$v){
                        if($val['id'] == $v['comment_id']){
                            $comment[$key]['attachment'][] = $v;
                        }
                    }
                }
        }			
        return $comment;
    }
}

?>
