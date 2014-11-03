<?php

/*
 * 产品模型
 * author： kewen
 */

class CategorytModel extends CommonModel {
    
    public function listCategory() {
        return $this->query('select * from ' . $this->tablePrefix . 'category' . ' order by porder asc');
    }

    //添加父类节点
    public function parentNode($id) {
        $info = $this->query('select * from ' . $this->tablePrefix . 'category where id=\'' . $id . '\' limit 1');
        return $info[0];
    }

    //更新全部父类子节点
    public function childNode($id, $nodeid) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set childs=CONCAT(childs,\'' . $nodeid . ',\') where id=\'' . $id . '\' limit 1');
        return ($result === false ? false : true);
    }

    //更新全部子节点的pindex
    public function updateChildNode($oorder, $norder) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set porder=REPLACE(porder,\'' . $oorder . '\', \''. $norder .'\') where porder like \'%' . $oorder . '%\'');
        return ($result === false ? false : true);
    }
    
    //删除父类中的childs
    public function deleteChildsNode($childId) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set childs=REPLACE(childs,\'' . $childId . ',\', \'\') where childs like \'%' . $childId . ',%\'');
        return ($result === false ? false : true);
    }
    
}

?>
