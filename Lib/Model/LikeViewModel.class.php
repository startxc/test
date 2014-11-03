<?php
/**
 * 查询喜欢我作品的
 *
 * @author bruce
 */
class LikeViewModel extends ViewModel{
    
       public $viewFields = array(
           'Like'=>array('member_id'=>'liked_member_id','create_time','_as'=>'liked'),
           'Member'=>array('username','nickname','avatar','_on'=>'Member.id=liked.member_id'),
           'Works'=>array('id','name','cover_image','_on'=>'Works.id=liked.like_id')
       );
}

?>
