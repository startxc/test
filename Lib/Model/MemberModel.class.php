<?php
/**
 * 用户模型文件
 * @author   Lyon
 * @version  MemberModel.calss.php 2013-09-22
 */

class MemberModel extends CommonModel {   
    /**
    * 获取所有的省份/城市/区域
    * @return array  
    */  
    public function getAllRegions() {
        $province = array();
        $province = S("h_all_regions");
        if (empty($province)) {
            $region = M('region');
            $all = $region->field("id,name,type,parent_id")->select();           
            foreach ($all as $k => $v) {
                if ($v['type'] == 1) {
                    $province[] = $v;
                    unset($all[$k]);
                }
            }
            foreach ($province as $ke => $va) {
                foreach ($all as $_ke => $_va) {
                    if ($_va['parent_id'] == $va['id']) {
                        $province[$ke]['child'][] = $_va;
                        unset($all[$_ke]);
                    }
                }
            }
            foreach ($province as $key => $val) {
                foreach ($val['child'] as $_key => $_val) {
                    foreach ($all as $i => $j) {
                        if ($j['parent_id'] == $_val['id']) {
                            $province[$key]['child'][$_key]['child'][] = $j;
                            unset($all[$i]);
                        }
                    }
                }
            }
            S("h_all_regions", $province, C('DATA_CACHE_TIME'));    
        }                
        return $province;
    }          
}

?>
