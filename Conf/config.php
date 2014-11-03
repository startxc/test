<?php
/*
 * pansee 配置文件
 * author: Leo
 */

//'配置项'=>'配置值'
return array(
    'APP_GROUP_LIST'    => 'Api,Admin',   //项目分组设定
    'DEFAULT_GROUP'     => 'Admin',         //默认分组
    'TMPL_PARSE_STRING' => array('__PUBLIC__'    =>  '/Public'),
    
    //数据库设置
    /*本地数据库
     'DB_TYPE'           => 'mysql',
     'DB_HOST'           => 'localhost',
     'DB_NAME'           => 'lvjie',
     'DB_USER'           => 'root',
     'DB_PWD'            => '',
     'DB_PORT'           => '3306',
     'DB_PREFIX'         => 'lj_',
     'DB_LIKE_FIELDS'    => 'title|remark',*/

    //远程数据库
     'DB_TYPE'           => 'mysql',
     'DB_HOST'           => '58.67.156.142',
     'DB_NAME'           => 'lvjie',
     'DB_USER'           => 'u_lvjie',
     'DB_PWD'            => 'krkRgmUNT7z',
     'DB_PORT'           => '6033',
     'DB_PREFIX'         => 'lj_',
     'DB_LIKE_FIELDS'    => 'title|remark',

    'LANG_AUTO_DETECT'      => FALSE,               //关闭语言的自动检测，如果你是多语言可以开启
    'LANG_SWITCH_ON'        => TRUE,                //开启语言包功能，这个必须开启
    'DEFAULT_LANG'          => 'zh-cn',             //zh-cn文件夹名字 /lang/zh-cn/common.php

    //系统基本设置
    'APP_AUTOLOAD_PATH'     => '@.TagLib',
    'SESSION_AUTO_START'    => true,
    'URL_MODEL'             => 2,                   // 如果你的环境不支持PATHINFO 请设置为3
    //'SHOW_PAGE_TRACE'     => 0,                   //显示调试信息
    'VAR_PAGE'              => 'p',
    'EVERY_PAGE_ROWS'       => 15,
    'EVERY_COMMENT_PAGE'    => 10,
    
    //rbac认证设置
    'USER_AUTH_ON'          => true,
    'USER_AUTH_TYPE'        => 1,                       // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'         => 'authId',                // 用户认证SESSION标记
    'USER_AUTH_MODEL'       => 'User',                  // 默认验证数据表模型
    'ADMIN_AUTH_KEY'        => 'administrator',
    'AUTH_PWD_ENCODER'      => 'md5',                   // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'     => '/Admin/Public/login',   // 默认认证网关
    'NOT_AUTH_MODULE'       => '/Admin/Public',         // 默认无需认证模块
    'REQUIRE_AUTH_MODULE'   => '',                      // 默认需要认证模块
    'NOT_AUTH_ACTION'       => '',                      // 默认无需认证操作
    'REQUIRE_AUTH_ACTION'   => '',                      // 默认需要认证操作
    'GUEST_AUTH_ON'         => false,                   // 是否开启游客授权访问
    'GUEST_AUTH_ID'         => 0,                       // 游客的用户ID
    //'RBAC_ERROR_PAGE'     => 'Public/welcome',        // 无权限提示错误提示页
    'RBAC_ROLE_TABLE'       => 'lj_rbac_role',
    'RBAC_USER_TABLE'       => 'lj_rbac_role_user',
    'RBAC_ACCESS_TABLE'     => 'lj_rbac_access',
    'RBAC_NODE_TABLE'       => 'lj_rbac_node',
	'APP_AUTOLOAD_PATH'=>'ORG.Util',
    
    
    //缓存设置
    //'DATA_CACHE_TYPE' => 'Memcache',
    //'MEMCACHE_HOST'   =>  'tcp://192.168.1.2:11211', 
    'EVERY_COMMENT_PAGE'    => 10,
);

?>