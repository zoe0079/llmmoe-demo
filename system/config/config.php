<?php
/**
 * 这里是一些基本配置，最后两个基本（具体还没看）没有用到，正在考虑他们的存在必要
 */

date_default_timezone_set('Asia/Chongqing');	// 设置时区

define('APP_DIR',           'application/');			// 应用目录
define('SYSTEM_DIR',        'system/');					// 系统目录
define('LIB_DIR', 	        SYSTEM_DIR.'lib/');			// 内库目录
define('VIEW_DIR', 	        APP_DIR.'view/');			// 视图目录
define('CTRL_DIR', 	        APP_DIR.'control/');		// 控制器目录
define('MODULE_DIR',        APP_DIR.'module/');			// 模型目录
define('NAMESPACE_CTRL',    '\\app\\ctrl\\');           // 控制器命名空间
define('NAMESPACE_MODULE',  '\\app\\module\\');         // 模型命名空间
define('NAMESPACE_LIB',     '\\sys\\lib\\');            // 内库命名空间

define('BASE_URL', 	        'http://192.168.254.5/');	// 首页地址
define('UPLOAD_PATH',       BASE_URL.'upload/');        // 上传目录
define('WEB_NAME',		    '甘溪镇人民政府');			// 应用名称