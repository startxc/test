<?php

// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: common.php 2601 2012-01-15 04:59:14Z liu21st $

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty($time)) {
        return '';
    }
    $format = str_replace('#', ':', $format);
    return date($format, $time);
}

//缓存文件
function cmssavecache($name = '', $fields = '') {
    $Model = D($name);
    $list = $Model->select();
    $data = array();
    foreach ($list as $key => $val) {
        if (empty($fields)) {
            $data [$val [$Model->getPk()]] = $val;
        } else {
            // 获取需要的字段
            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }
            if (count($fields) == 1) {
                $data [$val [$Model->getPk()]] = $val [$fields [0]];
            } else {
                foreach ($fields as $field) {
                    $data [$val [$Model->getPk()]] [] = $val [$field];
                }
            }
        }
    }
    $savefile = cmsgetcache($name);
    // 所有参数统一为大写
    $content = "<?php\nreturn " . var_export(array_change_key_case($data, CASE_UPPER), true) . ";\n?>";
    file_put_contents($savefile, $content);
}

function cmsgetcache($name = '') {
    return DATA_PATH . '~' . strtolower($name) . '.php';
}

function getStatus($status, $imageShow = true) {
    switch ($status) {
        case 0 :
            $showText = '禁用';
            $showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
        case 2 :
            $showText = '待审';
            $showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
            break;
        case - 1 :
            $showText = '删除';
            $showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
            break;
        case 1 :
        default :
            $showText = '正常';
            $showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';
    }
    return ($imageShow === true) ? $showImg : $showText;
}

function getDefaultStyle($style) {
    if (empty($style)) {
        return 'blue';
    } else {
        return $style;
    }
}

function IP($ip = '', $file = 'UTFWry.dat') {
    $_ip = array();
    if (isset($_ip [$ip])) {
        return $_ip [$ip];
    } else {
        import("ORG.Net.IpLocation");
        $iplocation = new IpLocation($file);
        $location = $iplocation->getlocation($ip);
        $_ip [$ip] = $location ['country'] . $location ['area'];
    }
    return $_ip [$ip];
}

function getNodeName($id) {
    if (Session::is_set('nodeNameList')) {
        $name = Session::get('nodeNameList');
        return $name [$id];
    }
    $Group = D("Node");
    $list = $Group->getField('id,name');
    $name = $list [$id];
    Session::set('nodeNameList', $list);
    return $name;
}

function get_pawn($pawn) {
    if ($pawn == 0)
        return "<span style='color:green'>没有</span>";
    else
        return "<span style='color:red'>有</span>";
}

function get_patent($patent) {
    if ($patent == 0)
        return "<span style='color:green'>没有</span>";
    else
        return "<span style='color:red'>有</span>";
}

function getNodeGroupName($id) {
    if (empty($id)) {
        return '未分组';
    }
    if (isset($_SESSION ['nodeGroupList'])) {
        return $_SESSION ['nodeGroupList'] [$id];
    }
    $Group = D("Group");
    $list = $Group->getField('id,title');
    $_SESSION ['nodeGroupList'] = $list;
    $name = $list [$id];
    return $name;
}

function getCardStatus($status) {
    switch ($status) {
        case 0 :
            $show = '未启用';
            break;
        case 1 :
            $show = '已启用';
            break;
        case 2 :
            $show = '使用中';
            break;
        case 3 :
            $show = '已禁用';
            break;
        case 4 :
            $show = '已作废';
            break;
    }
    return $show;
}

function showStatus($status, $id) {
    switch ($status) {
        case 0 :
            $info = '<a href="javascript:resume(' . $id . ')">恢复</a>';
            break;
        case 2 :
            $info = '<a href="javascript:pass(' . $id . ')">批准</a>';
            break;
        case 1 :
            $info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';
            break;
        case - 1 :
            $info = '<a href="javascript:recycle(' . $id . ')">还原</a>';
            break;
    }
    return $info;
}

/**
  +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
  +----------------------------------------------------------
 * @param string $fmode 文件名
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
    return rand_string($length, $mode);
}

function getGroupName($id) {
    if ($id == 0) {
        return '无上级组';
    }
    if ($list = F('groupName')) {
        return $list [$id];
    }
    $dao = D("Role");
    $list = $dao->select(array('field' => 'id,name'));
    foreach ($list as $vo) {
        $nameList [$vo ['id']] = $vo ['name'];
    }
    $name = $nameList [$id];
    F('groupName', $nameList);
    return $name;
}

function sort_by($array, $keyname = null, $sortby = 'asc') {
    $myarray = $inarray = array();
    # First store the keyvalues in a seperate array
    foreach ($array as $i => $befree) {
        $myarray [$i] = $array [$i] [$keyname];
    }
    # Sort the new array by
    switch ($sortby) {
        case 'asc' :
            # Sort an array and maintain index association...
            asort($myarray);
            break;
        case 'desc' :
        case 'arsort' :
            # Sort an array in reverse order and maintain index association
            arsort($myarray);
            break;
        case 'natcasesor' :
            # Sort an array using a case insensitive "natural order" algorithm
            natcasesort($myarray);
            break;
    }
    # Rebuild the old array
    foreach ($myarray as $key => $befree) {
        $inarray [] = $array [$key];
    }
    return $inarray;
}

/**
  +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
  +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
    $str = '';
    switch ($type) {
        case 0 :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1 :
            $chars = str_repeat('0123456789', 3);
            break;
        case 2 :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3 :
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) { //位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}

function pwdHash($password, $type = 'md5') {
    return hash($type, $password);
}

//验证url
function verify_url($url) {
    return preg_match("/^[a-zA-z]+:\/\/[^\s]*$/", $url);
}

//验证email
function verify_email($email) {
    return preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email);
}

//返回等级
function return_level($level) {
    return str_repeat('|--- ', $level + 1);
}

//ajax返回函数
function ajax_return($obj) {
    echo "(" . json_encode($obj) . ")"; exit;
}

//判断价格格式
function parse_price($price) {
    $p = "/^\d+\.[0-9]{2}$/";
    $s = "/^\d+$/";
    return (preg_match($p, $price) || preg_match($s, $price));
}

//返回区域名
function getAreaName($areaid) {
    $region = M('region');
    $row = $region->where("id = '$areaid'")->field('name')->find();
    return $row['name'];
}

//获取文件后缀名
function getExtensionName($filePath) {
    $num = strrpos($filePath, '.');
    $len = strlen($filePath);
    $extension = substr($filePath, $num + 1, $len - $num);
    return $extension;
}

//格式化商品ID
function parse_goods_id($arr) {
    return $arr['goods_id'];
}

//商品图片
function picture($pic, $width='', $type='product') {
    $type = empty($type) ? 'product' : $type;
    // 取年月文件夹
    $dir0 = substr($pic, 0, 6);
    // 取日期文件夹 
    $dir1 = substr($pic, 6, 2);
    // 分割文件名
    $name = explode(".", $pic);
    $pref = (empty($width) || (strtolower(substr($pic,-3)) == "gif")) ? '' : '.' . $width;
    $dir2 = $dir0 . "/" . $dir1 . "/" . $name[0] . $pref .'.'. $name[1];
    // 是否取缩略图
    
    $directory = (empty($width) || (strtolower(substr($pic,-3)) == "gif"))? $type :'thumbs/'.$type;
    $host = "http://imglv.pansi.com/{$directory}/";
    
    return $host . $dir2;
}

//获得本地商品图片地址
function local_picture($pic, $width='', $type='product') {
    $type = empty($type) ? 'product' : $type;
    // 取年月文件夹
    $dir0 = substr($pic, 0, 6);
    // 取日期文件夹 
    $dir1 = substr($pic, 6, 2);
    // 分割文件名
    $name = explode(".", $pic);
    $pref = (empty($width) || (strtolower(substr($pic,-3)) == "gif")) ? '' : '.' . $width;
    $dir2 = $dir0 . "/" . $dir1 . "/" . $name[0] . $pref .'.'. $name[1];
    // 是否取缩略图
    $directory = (empty($width) || (strtolower(substr($pic,-3)) == "gif"))? $type :'thumbs/'.$type;
    $host =  "../upload/{$directory}/";
    return $host . $dir2;
}

//头像
function avatar($pic){
    $host = "http://imglv.pansi.com/member/";
    if(empty($pic)) return $host."201401/01/20140101default_face.png"; 
    $dir0 = substr($pic, 0, 6);
    $dir1 = substr($pic, 6, 2);
    $dir2 = $dir0 . "/" . $dir1 . "/";
    
    return $host.$dir2. "b_" . $pic;
}

//缓存的键列表
function SK($key, $val, $expire_time,$cache_key = "ps_datakey")
{

    $datakey = S($cache_key);
    if(empty($key)) {
        return false;
    }else {
        if(empty($expire_time)) $expire_time = C('DATA_CACHE_TIME');
        S($key, $val, $expire_time);
        $datakey = empty($datakey) ? array() : $datakey;
        array_push($datakey, $key);
        $new = array_unique($datakey);
        S($cache_key, $new);
    }
}

//商品分类
function category() {
    $category = array();
    $category = S('home_category');
    if (empty($category)) {
        $category = M("Category")->field("id,pid,name,parents,childs,number,level,keywords,description")->order("porder asc")->select();
        SK('h_category_list_0', $category, C('DATA_CACHE_TIME')); 
    }
    return $category;
}

//系统设置
function system_config($code) {
    $config = S('h_system_config_all');
    if(empty($config)) {
        $config = array();
        $temp = M("Config")->field("id,code,value")->select();
        foreach ($temp as $value) {
            $config[$value['code']] = $value['value'];
        }
        unset($temp);
        SK('h_system_config_all', $config);
    }
    return (empty($config[$code]) ? $config : $config[$code]);
}

//返回PVS
function reback_pvs($now) {
    return implode("_", $now);
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false) {
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
        else
                return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}


//计算剩余时间
function diffTime($start_time,$end_time){
    $time = time();
    if($start_time>$time){
        return "还未开始";
    }else if($time>$end_time){
        return "已结束";
    }else{
        return ceil(($end_time-$time)/3600/24)."天";
    }
}

/*
*@desc:发送短信
*@返回信息：returnstatus：Success(成功)，Faild(失败)
*           message:提示信息
*/
function send_message($mobile,$content){
    $param = array(
        'action'=>'send',
        'userid'=>204,
        'account'=>'uoa',
        'password'=>'123123',
        'mobile'=>$mobile,
        'content'=>urlencode($content),
        'sendTime'=>'',
        'extno'=>''
    );
    $query = http_build_query($param);
    $ch = curl_init("http://106.3.37.122:8088/sms.aspx?".$query) ;  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回    
    $output = curl_exec($ch) ; 
    return simplexml_load_string($output); 
}


/**
 * 读取分层的配置参数
 * @param string $path 参数路径(如QIS/vars.Offices读取Conf/QIS/vars.php下键值为Offices变量值
 */
function CC($path)
{
    if(strpos($path,'/') != false)
    {
        $tmp = explode('.', $path);
        $include_path = APP_PATH.'Conf/'.$tmp[0].'.php';
        $vals = include $include_path;
        
        if(count($tmp) == 1)
        {
            return $vals;
        }
        
        $val = false;
        for($i=1;$i<count($tmp);$i++)
        {
            $key = $tmp[$i];
            if(isset($vals[$key]))
            {
                $val = $vals[$key];
            }
            else 
            {
                $val = false;
            }
        }
        
        return $val;
    }
    else 
    {
        return C($path);
    }
}
?>