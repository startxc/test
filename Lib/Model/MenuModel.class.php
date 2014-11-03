<?php

/*
 * 系统菜单模型
 * author: kewen
 */

class MenuModel extends Model {

    public $_validate = array(
        array('name', 'require', '菜单名称必须'),
        array('url', 'require', '链接地址必须'),
    );
    
    public $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
    );
    
    function addMenu($obj) {
        $result = $this->execute('insert into '. $this->tablePrefix.'menu (id, name,url,status,child,create_time) values(\'\',\''. $obj->name .'\',\''. $obj->url .'\',\''. $obj->status .'\',\''. $obj->child .'\',\''. time() .'\') ');
        return ($result===false ? false : true);
    }
    
    function setMenu($obj) {
        $result = $this->execute('update '. $this->tablePrefix.'menu set name=\''. $obj->name .'\',url=\''. $obj->url .'\',status=\''. $obj->status .'\',child=\''. $obj->child .'\',update_time=\''. time() .'\' where id=\''. $obj->id .'\'');
        return ($result===false ? false : true);
    }
}

?>
