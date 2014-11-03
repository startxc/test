<?php
/**
 * @description:图片上传、图片裁剪接口
 * @author bruce
 */
class ImageAction extends CommonAction{
    //加上这个方法，可以不用调用父类的_initialize方法
    public function _initialize(){}
    /*
     * @desc:上传图片
     * @param: token验证的token值
     *         prefix:token的前缀，用来验证token，防止跨站提交
     *         sessionid：php的session_id()，防止session丢失的问题
     *         dir:要上传的目标目录
     * @author:bruce
     */
    public function uploadImage(){
        $back = new stdClass();
        $dir = trim($_POST['dir']);
        $dir = empty($dir)?"works":$dir;
        import("@.ORG.Util.UploadFile");
        $savePath = $this->_createDir($dir);
        $upload = new UploadFile();
        $upload->maxSize = 2097152;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = $savePath;
        $upload->thumb = flase;
        $upload->imagePrefix = date("Ymd");
        $upload->saveRule = uniqid();
        if ($upload->upload()) {           
            $info = $upload->getUploadFileInfo();
            $imgsize = getimagesize(local_picture($info[0]['savename'],'',$dir));
            $back->width = $imgsize[0];
            $back->height = $imgsize[1];
            $back->src = picture($info[0]['savename'],'',$dir);
            $back->name = $info[0]['savename'];
            $back->status = 1;                      
        } else {          
            $back->info = $upload->getErrorMsg();
            $back->status  = 0; 
        }          
        ajax_return($back);           
    }
    
    /*
     * @desc 裁剪图片
     */
    public function cutImage(){
        !$this->isAjax() && $this->redirect("Index/index");
        $url_arr = parse_url(trim($_POST['picpath']));
        $path_arr = explode("/",trim($url_arr['path'],"/"));
        $picpath = array_pop($path_arr);
        $dir = array_shift($path_arr);
        $dst_dir = empty($_POST['dst_dir'])?"works":$_POST['dst_dir'];
        $x = intval($_POST['x']);
        $y = intval($_POST['y']);
        $org_w = intval($_POST['org_w']);
        $org_h = intval($_POST['org_h']);
        $dst_w = intval($_POST['dst_w']);
        $dst_h = intval($_POST['dst_h']);

        $back = new stdClass();
        
        $imgpath = "";
        if (!empty($picpath)) {
            $imgpath = $this->_parseFaceDir($picpath,$dir) . $picpath;
        }
        if (!file_exists($imgpath)) {
            $back->status = 0;
            $back->info = "要裁剪的图片不存在";
            ajax_return($back);
        }       
        //检查后缀名
        $ext = getExtensionName($imgpath);
        $ext = strtolower($ext);
        if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png') {
            @unlink($imgpath);
            $back->status = 0;
            $back->info = "要裁剪的图片格式不对";
            ajax_return($back);
        }        
        
        if ($org_w == 0 || $org_h == 0) {
            $back->status = 0;
            $back->info = "裁剪图片的宽度或者高度不能为0";
            ajax_return($back);
        }
        if ($dst_w == 0 || $dst_h == 0) {
            $back->status = 0;
            $back->info = "裁剪后图片的宽度或者高度不能为0";
            ajax_return($back);
        }



        //目标图片地址
        $prefix = $this->_createDir($dst_dir);
        $picpath = date("Ymd") .uniqid().".".$ext;
        $dstfile = $prefix . $picpath; 

        //生成新的
        import("@.ORG.Util.Image");        
        Image::dothumb($imgpath, $dstfile, $dst_w, $dst_h, 0, 0, $x, $y, $org_w, $org_h);

        if(!file_exists($dstfile)){
            $back->status = 0;
            $back->info = "裁剪失败";
            ajax_return($back);
        }
        $back->src = picture($picpath,"",$dst_dir);
        $back->name = $picpath;
        $back->status = 2;
        ajax_return($back);         
    }
    
    //构建上传路径 
    private function _createDir($dir='works') {
        $dir0 = str_replace(APP_NAME . "/", "upload/{$dir}/", ROOT_PATH);
        $dir1 = $dir0 . date("Ym") . "/";
        $dir2 = $dir1 . date("d") . "/";
        !file_exists($dir0) && mkdir($dir0);
        !file_exists($dir1) && mkdir($dir1);
        !file_exists($dir2) && mkdir($dir2);
        return $dir2;
    }  
    
    //解析图片路径
    private function _parseFaceDir($pic,$dir='works') {
        $dir0 = str_replace(APP_NAME . "/", "upload/{$dir}/", ROOT_PATH);
        $dir1 = substr($pic, 0, 6);
        $dir2 = substr($pic, 6, 2);
        return $dir0 . $dir1 . '/' . $dir2 . '/';
    }        
}

?>
