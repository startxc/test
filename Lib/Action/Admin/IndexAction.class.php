<?php

/*
 * 主框架入口
 * author: kewen
 */

class IndexAction extends CommonAction {
    
    // 主框架
    public function index() {
        header("Content-Type:text/html; charset=utf-8");
        
        //导航菜单
        $listCacheMenu = array();
        //$listCacheMenu = S("list_cache_menu");
        if (!$listCacheMenu) {
            $listMenu = D("Menu")->field('id,name,url,child')->where('status=1')->order('sort asc')->select();
            $listModule = D("Node")->field('id,name,title,pid')->where('status=1')->where('pid=1')->order('sort desc')->select();
            foreach ($listMenu as $key => $value) {
                $child = explode(',', $value['child']);
                $tmp = array();
                foreach ($listModule as $k => $v) {
                    if (in_array($v['id'], $child)) {
                        $tmp[] = $v;
                    }
                }
                $listMenu[$key]['sub'] = $tmp;
            }
            S('list_cache_menu', $listMenu);
            $listCacheMenu = $listMenu;
            unset($listModule);
        }
        //dump($listCacheMenu);
        //快捷菜单
        $uid = session("authId");
        $quick = array();
        $quick = M('quick')->where("uid={$uid}")->select();
        
        $this->assign('quick', $quick);
        $this->assign('listLen', count($listCacheMenu));
        $this->assign('listMenu', $listCacheMenu);
        $this->display();
    }
    
    //快捷菜单
    public function quickInsert() {
        $url = $name = 0;
        $url = empty($_GET['url']) ? 0 : trim($_GET['url']);
        $name = empty($_GET['name']) ? 0 : trim($_GET['name']);
        $obj = new stdClass();
        if($url===0 || $name===0) {
            $obj->status = 0;
            $obj->prompt = '传递参数有误';
            echo '('. json_encode($obj) .')'; exit;
        }
        $data = array();
        $data['url']  = $url;
        $data['name'] = $name;
        $data['uid']  = session("authId");
        $sign = M('quick')->data($data)->add();
        if($sign) {
            $obj->status = 1;
            $obj->id = $sign;
            $obj->prompt = '添加成功';
        }  else {
            $obj->status = 0;
            $obj->prompt = '添加失败';
        }
        echo '('. json_encode($obj) .')'; exit;
    }
    
    //删除菜单
    public function quickDelete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $obj = new stdClass();
        if($id===0) {
            $obj->status = 0;
            $obj->prompt = '删除失败';
        } else {
            $sign = M('quick')->where("id={$id}")->delete();
            if($sign) {
                $obj->status = 1;
                $obj->prompt = '删除成功';
            }else {
                $obj->status = 0;
                $obj->prompt = '删除失败';
            }
        }
        echo '('. json_encode($obj) .')'; exit;
    }
    
    //系统信息
    public function main() {
        $system = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'ThinkPHP版本' => THINK_VERSION ,
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            //'剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        $this->assign('system', $system);
        $this->display();
    }
    
    }