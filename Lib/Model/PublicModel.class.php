<?php
/**
 * 公共模型文件
 * @author   Lyon
 * @version  PublicModel.calss.php 2013-09-11
 */

class PublicModel extends CommonModel { 
    /**
     * 验证邮箱
     * @param string $str
     * @return boolean
     */
    public function verifyEmail($email) {
        return preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email);
    }
    /**
     * 验证手机号码
     * @param string $str
     * @return boolean
     */
    public static function verifyMobile($str)
    {
        return preg_match("/^[0]?(18[8|9]|13[0-9]{1}|15[0-9]{1}+)(\d{8})$/", $str);
    }

    /**
     * 验证用户名（字符、中文、"_"、"-" 构成，不能纯数字）
     * @param string $str
     * @return bool
     */

    public function verifyUsername($username) {    
        return preg_match('/^[\w\-\x{4e00}-\x{9fa5}]+[a-z_\-\x{4e00}-\x{9fa5}]+$/iu', $username)
        && strlen($username) >= 6 && mb_strlen($username, 'UTF-8') <= 20;
    }

    /**
     * 发送验证邮件
     * @param $email   接收邮箱
     * @param $keyword 模板变量
     * @param $code    模板名
     * @return bool
     */
    public function sentEmail($uid,$email,$keyWord = array(),$code) {    
        $data = array();        
        $data['member_id'] = $uid;
        $data['email_name'] = $email;
        $data['create_time'] = time();
        $data['tpl_params'] = serialize($keyWord);
        $data['tpl_code'] = $code;
        $model = M('email_list');
        $res = $model->add($data);
        
        return $res;
    }
    /**
     * 上传身份证，经营场所图片
     * @param string $str
     * @return bool
     */
    public function uploadImage() {
        import("@.ORG.Util.UploadFile");
        $savePath = $this->_createSalerDir();
        $upload = new UploadFile();
        $upload->maxSize = 2097152;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,pdf');
        $upload->savePath = $savePath;
        $upload->thumb = flase;
        $upload->imagePrefix = date("Ymd");
        $upload->saveRule = uniqid();
        if ($upload->upload()) {           
            $uploadList = $upload->getUploadFileInfo();                        
            //保存至附件库
            $data = array();
            $image = array();            
            $data['type'] = 1;
            $data['create_time'] = time();
            $data['name'] = $uploadList[0]['savename'];
            $image['id'] = M("member_attachment")->add($data);
            //返回给客户端
            $image['imgsrc'] = picture($uploadList[0]['savename'],'','WholesalerAuth');           
            $image['savename'] = $uploadList[0]['savename'];
            return $image;
        } else {          
            return false;
        } 
    }

    public function _createSalerDir() {
        $dir0 = str_replace(APP_NAME . "/", "upload/WholesalerAuth/", ROOT_PATH);
        $dir1 = $dir0 . date("Ym") . "/";
        $dir2 = $dir1 . date("d") . "/";
        !file_exists($dir0) && mkdir($dir0);
        !file_exists($dir1) && mkdir($dir1);
        !file_exists($dir2) && mkdir($dir2);
        return $dir2;
    }       
}

?>
