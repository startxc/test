<?php
/**
 * 查询收藏我作品的
 *
 * @author bruce
 */
class CollectViewModel extends ViewModel{
    
       public $viewFields = array(
           'Collect'=>array('member_id'=>'collected_member_id','create_time','_as'=>'collected'),
           'Member'=>array('username','nickname','avatar','_on'=>'Member.id=collected.member_id'),
           'Works'=>array('id','name','cover_image','_on'=>'Works.id=collected.collect_id')
       );
}

?>
