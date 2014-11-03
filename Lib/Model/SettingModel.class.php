<?php
/**
 * 公共模型文件
 * @author   Lyon
 * @version  MemberModel.calss.php 2013-09-22
 */

class SettingModel extends CommonModel {   
    /**
     * 获取省份/城市/区域
     * @param  int $id
     * @return array  
     */
    private function getRegion($id) {
        $region = M('region');
        $row = $region->where("parent_id = '$id'")->field('id,name')->select();
        return $row; 
    }
    /**
    * 获取所有的省份/城市/区域
    * @return array  
    */
    public function getAllRegion() {
        $provinceList = $this->getRegion(1);    //获得省份
        foreach ($provinceList as $key => $p) {
            $child = $this->getRegion($p['id']);     //获得城市
            foreach ($child as $k => $c) {
                $child[$k]['child'] = $this->getRegion($c['id']);    //获得区/县
            }
            $provinceList[$key]['child'] = $child;
        }
        return $provinceList;
    }

    public function saveDonation() {
        
    }   
}

?>
