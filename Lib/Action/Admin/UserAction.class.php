<?php

/*
 * 用户模块文件
 * author: kewen
 */

class UserAction extends CommonAction {

    function _filter(&$map) {
        if(session('authId')!=1) {
            $map['id'] = array('egt', 2);
            $map['username'] = array('like', "%" . $_POST['username'] . "%");
        }
    }

    // 检查帐号
    public function checkUsername() {
        if (!preg_match('/^[a-z]\w{4,}$/i', $_POST['username'])) {
            $this->error('用户名必须是字母，且5位以上！');
        }
        
        if(empty($_REQUEST['nickname'])) {
             $this->error('用户昵称不能为空！');
             return;
        }
        
        $User = M("User");
        // 检测用户名是否冲突
        $name = $_REQUEST['username'];
        $result = $User->getByUsername($name);
        if ($result) {
            $this->error('该用户名已经存在！');
        } 
    }

    // 插入数据
    public function insert() {
        $this->checkUsername();
        // 创建数据对象
        $User = D("User");
        if (!$User->create()) {
            $this->error($User->getError());
        } else {
            // 写入帐号数据
            if ($result = $User->add()) {
                $this->addRole($result);
                $this->success('用户添加成功！');
            } else {
                $this->error('用户添加失败！');
            }
        }
    }

    protected function addRole($userId) {
        //新增用户自动加入相应权限组
        $RoleUser = M("RoleUser");
        $RoleUser->user_id = $userId;
        // 默认加入网站编辑组
        $RoleUser->role_id = 3;
        $RoleUser->add();
    }

    //重置密码
    public function resetPwd() {
        $id = $_POST['id'];
        $password = $_POST['password'];
        if ('' == trim($password)) {
            $this->error('密码不能为空！');
        }
        $User = M('User');
        $User->password = md5($password);
        $User->id = $id;
        $result = $User->save();
        $obj = new stdClass();
        if (false !== $result) {
            $obj->status = 1;
        } else {
            $obj->status = 0;
        }
        ajax_return($obj);
    }

}

?>