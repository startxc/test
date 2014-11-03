<?php
/**
 * 用户模型文件
 * @author   Lyon
 * @version  CodeModel.calss.php 2013-09-22
 */

class CodeModel extends CommonModel {   
    public $code;
    public function __construct() {
        parent::__construct();
        $this->code = M('code');
    }
    //type 1:邀请码(1开头，最小五位)
    public function getCode($type) {
        if (empty($type)) {
            return false;
        }
        $row = $this->code->where("code_type = '$type'")->find();
        $newNum = $row['current_num'] + 1;
        //$codeNum = str_pad($newNum, $row['length'],"0",STR_PAD_LEFT);
        $codeStr = $row['prefix'].$newNum;
        $this->updateCode($type,$newNum);
        return $codeStr;
    }

    public function updateCode($type,$current_num) {
        $map = array();
        $map['current_num'] = $current_num;
        $map['update_time'] = time();
        $this->code->where("code_type = '$type'")->save($map);
    }          
}

?>
