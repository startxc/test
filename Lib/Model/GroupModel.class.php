<?php

/*
 * 用户组模型文件
 * author: kewen
 */

class GroupModel extends CommonModel {
    
    protected $tableName = "rbac_group";
    
    protected $_validate = array(
        array('name', 'require', '名称必须'),
    );
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );

}

?>
