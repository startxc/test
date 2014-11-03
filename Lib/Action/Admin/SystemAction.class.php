<?php

/*
 * 系统管理
 * author: kewen
 */

class SystemAction extends CommonAction {

    public function article() {
        $list = M("article_category")->order(" porder asc ")->select();
        $this->assign("list", $list);
        $this->display();
    }
    
    public function articleAdd() {
        $model = M("article_category");
        $this->_add($model);
        $this->display();
    }
    
    public function articleEdit() {
        $id = empty($_GET['id']) ? $this->error('编辑失败，传递参数有误') : intval($_GET['id']);
        $info = M("article_category")->where('id=\'' . $id . '\'')->find();
        $this->assign("info", $info);
        $this->display();
    }
    
    public function articleInsert() {
        $model = M("article_category");
        $this->_insert(&$model, __URL__.'/article');
    }
    
    public function articleUpdate() {
        $model = M("article_category");
        $this->_update(&$model, __URL__.'/article');
    }
    
    public function articleDelete() {
        $model = M("article_category");
        $this->_delete(&$model, __URL__ . '/article' );
    }
    
    /* public function index() {
      import("@.ORG.Util.Page");
      $category = D("category");
      $count = $category->count();
      $p = new Page($count, 20);
      $list = $category->order('sort asc')->limit($p->firstRow . ',' . $p->listRows)->select();
      $p->setConfig('header', '个分类');
      $p->setConfig('prev', "<");
      $p->setConfig('next', '>');
      $p->setConfig('first', '<<');
      $p->setConfig('last', '>>');
      $page = $p->show();
      $this->assign("page", $page);
      $this->assign("list", $list);
      $this->display();
      } */
    
    public function inspiration() {
        $list = M("inspiration_category")->order(" porder asc ")->select();
        $this->assign("list", $list);
        $this->display();
    }
    public function inspirationAdd() {
        $model = M("inspiration_category");
        $this->_add($model);
        $this->display();
    }

    public function inspirationEdit() {
        $id = empty($_GET['id']) ? $this->error('编辑失败，传递参数有误') : intval($_GET['id']);
        $info = M("inspiration_category")->where('id=\'' . $id . '\'')->find();
        $this->assign("info", $info);
        $this->display();
    }
    
    public function inspirationInsert() {
        $model = M("inspiration_category");
        $this->_insert(&$model, __URL__.'/inspiration');
    }
    
    public function inspirationUpdate() {
        $model = M("inspiration_category");
        $this->_update(&$model, __URL__.'/inspiration');
    }
    
    public function inspirationDelete() {
        $model = M("inspiration_category");
        $this->_delete(&$model, __URL__ . '/inspiration' );
    }
    
    //原创分类
    public function original() {
        $list = M("original_category")->order(" porder asc ")->select();
        $this->assign("list", $list);
        $this->display();
    }
    
    public function originalAdd() {
        $model = M("original_category");
        $this->_add($model);
        $this->display();
    }
    
    public function originalEdit() {
        $id = empty($_GET['id']) ? $this->error('编辑失败，传递参数有误') : intval($_GET['id']);
        $info = M("original_category")->where('id=\'' . $id . '\'')->find();
        $this->assign("info", $info);
        $this->display();
    }
    
    public function originalInsert() {
        $model = M("original_category");
        $this->_insert(&$model, __URL__.'/original');
    }
    
    public function originalUpdate() {
        $model = M("original_category");
        $this->_update(&$model, __URL__.'/original');
    }
    
    public function originalDelete() {
        $model = M("original_category");
        $this->_delete(&$model, __URL__ . '/original' );
    }
    
    //公用私有的增删改查
    private function _add($model) {
        $pid = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
        $level = !isset($_GET['level']) ? 0 : intval($_GET['level']) + 1;
       
        $pname = $pid==0 ? '' : $model->field("id,name")->where("id='{$pid}'")->find();
        $this->assign("pid", $pid);
        $this->assign("level", $level);
        $this->assign("pname", $pname);
    }
    
    private function _parents($model, $id) {
        return  $model->where("id={$id}")->find();
    }
    
    private function _childs($model, $id, $nodeid) {
        $result = $model->execute('update ' . $model->getTableName() . ' set childs=CONCAT(childs,\'' . $nodeid . ',\') where id=\'' . $id . '\' limit 1');
        return ($result === false ? false : true);
    }
    
    private function _insert($model, $url) {
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['url'] = trim($_POST['url']);
        $data['status'] = intval($_POST['status']);
        $data['level'] = intval($_POST['level']);
        $data['pid'] = intval($_POST['pid']);
        $data['pindex'] = intval($_POST['pindex']);
        $data['description'] = trim($_POST['description']);
        
        empty($data['name']) && $this->error('亲，分类名称不能为空哦');
        !empty($data['url']) && !verify_url($data['url']) && $this->error('亲，您输入的URL格式有误哦');
        
        if (!empty($_FILES['file']['name'])) {
            !$this->_upload() && $this->error('亲，上传图片有误哦');
        }
        $data['image'] = isset($_POST['image']) ? $_POST['image'] : '';

        $parent = array();
        if ($data['level'] > 0) {
            $parent = $this->_parents(&$model, $data['pid']);
            $data['parents'] = empty($parent['parents']) ? $data['pid'] : $parent['parents'] . ',' . $data['pid'];
        }
        $insertId = $model->add($data);
        if ($insertId) {
            // 更新父类子节点
            $sign = true;
            if ($data['level'] > 0) {
                $pnode = explode(',', $data['parents']);
                foreach ($pnode as $pid) {
                    $sign = $this->_childs(&$model, $pid, $insertId);
                }
            }
            // 更新排列顺序
            $parent['sorder'] = empty($parent['sorder']) ? 0 : $parent['sorder'];
            $parent['porder'] = empty($parent['porder']) ? 0 : $parent['porder'];
            $after = array();
            $tips = true;
            $after['sorder'] = $parent['sorder'] . ',' . $insertId;
            $after['porder'] = $parent['porder'] . ',' . str_pad($data['pindex'], 5, 0, STR_PAD_LEFT) . ',' . $insertId;
            $tips = $model->where('id=\'' . $insertId . '\'')->save($after);
            ($sign && $tips) ? $this->success('添加分类成功', $url) : $this->error('添加分类失败');
        } else {
            $this->error('添加分类失败');
        }
    }
    
    private function _upload() {
        import("@.ORG.Util.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = APP_PATH . '/Public/Uploads/Category/';
        $upload->thumb = flase;
        $upload->saveRule = uniqid;
        
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            $uploadList = $upload->getUploadFileInfo();
            $_POST['image'] = $uploadList[0]['savename'];
            return $uploadList[0]['savename'];
        }
        return false;
    }
    
    private function _update($model, $url) {
        $id = empty($_POST['id']) ? $this->error('更新失败，传递参数有误') : intval($_POST['id']);
        $data = array();
        $data['name'] = trim($_POST['name']);
        $data['url'] = trim($_POST['url']);
        $data['status'] = intval($_POST['status']);
        $data['pindex'] = intval($_POST['pindex']);
        $data['description'] = trim($_POST['description']);
        empty($data['name']) && $this->error('亲，分类名称不能为空哦');
        !empty($data['url']) && !verify_url($data['url']) && $this->error('亲，您输入的URL格式有误哦');
        if (!empty($_FILES['file']['name'])) {
            !$this->_upload() && $this->error('亲，上传图片有误哦');
        }    
        
        //上传新的图片
        if(isset($_POST['image'])) {
            $imgfile = APP_PATH . '/Public/Uploads/Category/' . $_POST['img'];
            file_exists($imgfile) && @unlink($imgfile);
            $data['image'] = isset($_POST['image']) ? $_POST['image'] : $_POST['img'];
        }
        
        //查找父类
        $oorder = trim($_POST['porder']);
        if(intval($_POST['level'])==0) {
            $data['porder'] = '0,' . str_pad($data['pindex'], 5, 0, STR_PAD_LEFT) . ',' . $id;
        } else {
            $parentIndex = substr($oorder, 0, strrpos($oorder, ',') - 5);
            $data['porder'] = $parentIndex . str_pad($data['pindex'], 5, 0, STR_PAD_LEFT) . ',' . $id;
        }
        //$model = D($this->getActionName());
        $isOk = $model->where('id=\''. $id .'\'')->save($data);
        
        //更新子类排列
        if($isOk && !empty($_POST['childs'])) {
            $this->_updateChildNode($model, $oorder, $data['porder']);
        }
        
        $isOk ? $this->success('更新成功', $url) : $this->error('更新错误');
    }
    
    private function _delete($model, $url) {
        $id = empty($_GET['id']) ? $this->error('删除失败，传递参数有误') : intval($_GET['id']);
        //全部子类
        $info = $model->where('id=\''. $id .'\'')->find();
        $nodes = empty($info) ? $id : $info['childs'].$id;
        
        //删除子类图片
        $childs = $model->where('id IN('. $nodes .')')->select();
        foreach ($childs as $key => $value) {
            if(!empty($value['image'])) {
                $imgpath = APP_PATH . '/Public/Uploads/Category/' . $value['image'];
                file_exists($imgpath) && @unlink($imgpath);
            }
        }
        
        //删除父类childs字段
        $tips = $this->_deleteChilds(&$model, $id);
        !$tips && $this->error('更新父类子节点失败');
        
        $sign = $model->where('id IN(' . $nodes . ')')->delete();
        $sign ? $this->success('删除操作成功', $url) : $this->error('删除操作失败');
    }
    
    private function _deleteChilds($model, $childId) {
        $result = $model->execute('update ' . $model->getTableName() . ' set childs=REPLACE(childs,\'' . $childId . ',\', \'\') where childs like \'%' . $childId . ',%\'');
        return ($result === false ? false : true);
    }
    
    private function _updateChildNode($model, $oorder, $norder) {
        $result = $model->execute('update ' . $model->getTableName() . ' set porder=REPLACE(porder,\'' . $oorder . '\', \''. $norder .'\') where porder like \'%' . $oorder . '%\'');
        return ($result === false ? false : true);
    }

}

?>
