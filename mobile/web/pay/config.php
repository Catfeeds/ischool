<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('mobile' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Admin',
    'MODULE_DENY_LIST'   => array('Common', 'User'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'N)&xfKQZkrE3vFb.|VC=0U2j,q`h^g5/7G(~>@4-', //默认数据加密KEY

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => true,
    'PAGE_TRACE_SAVE'=>true,

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => 'safe', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'ischool', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'hnzf123456',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'wp_', // 数据库表前缀
    'DB_NAME_CK'   => 'ischool', // 数据库名
    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),
    //域名
    //'URL_PATH'=>'http://www.ischoolnet.cn/ischool',
    'URL_PATH'  => 'http://mobile.jxqwt.cn',
    //发布tornado项目的服务器地址，用于即时请假
    'URL_IP'    => 'http://122.114.51.145:888',
    //项目根目录
    'PRO_PATH'  => '/',
    //上传目录
    'UPLOAD_PATH' => '.',
    //公众号相关信息
    'APP_NAME' => '正梵智慧校园',
    //主页模块显示名称
    'HomePage' => '正梵智慧校园',
    //平安卡模块显示名称
    'YiCardName' => '平安通知',
    //家校通模块显示名称
    'TongZhiName'=> '家校沟通',
    //支付提醒期限,单位天
    'PayNotify' =>5,
    //管理员openid用于启动提醒
    'ManagerOpenid' =>'oUMeDwEvQistOP5DywTiHdTBdpBs',
    //正梵通信的
    'APPID'     => 'wx8c6755d40004036d',
    'APPSECRET' => '22f68f4da5b36641ed492c596406b75f',
    'TOKEN'     => 'weiphp',
    'ENCODINGAESKEY' => 'ZzCC1uT1kOltu2WkywgkwwCHG8JDI3zD5ZCKRhGHwJQ',
    'GK_TOKEN'  => 'gh_e25d98dd302e',
    //学期学年的各项套餐和单项套餐价格 1234,123,234,23,12,24,34,13,14,134,124
    'PUBLIC_TC'    =>array ('half' =>array('pa'=>'18','jx'=>'18','qq'=>'30','ck'=>'18','one'=>'70','two'=>'60','three'=>'60','four'=>'35','five'=>'30','six'=>'35','seven'=>'40','eight'=>'40','nine'=>'30','ten'=>'60','eleven'=>'50'),'year' =>array('pa'=>'30','jx'=>'30','qq'=>'50','ck'=>'30','one'=>'120','two'=>'100','three'=>'100','four'=>'60','five'=>'50','six'=>'60','seven'=>'70','eight'=>'70','nine'=>'50','ten'=>'100','eleven'=>'80'))
);
