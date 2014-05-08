<?php
// .-----------------------------------------------------------------------------------
// |  Software: [SZPHP framework]
// |   Version: 2013.12
// |      Site: http://www.shenzhiwang.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://www.shenzhiwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------
// |   编辑: 深知网 李洪海 <http://www.shenzhiwang.com>
// '-----------------------------------------------------------------------------------
/**
 * SZPHP框架入口文件
 * 在应用入口引入SZPHP.php即可运行框架
 * @package SZPHP
 * @supackage core
 * @author hdxj <houdunwangxj@gmail.com>
 */

//框架版本
define('SZPHP_VERSION', '2014-04-20');
//检查调试模式是否定义，若未定义，则默认关闭调试模式
defined("DEBUG")        or define("DEBUG", FALSE);
//判断是否为应用组
if (!defined('GROUP_PATH'))
    defined('APP_PATH') or define('APP_PATH', './');
//临时文件目录、文件名及文件是否定义，若其值为NULL，则定义该值
defined('TEMP_PATH')    or define('TEMP_PATH', (defined('APP_PATH') ? APP_PATH : GROUP_PATH) . 'Temp/');
defined("TEMP_NAME")    or define("TEMP_NAME",'~boot.php');
defined('TEMP_FILE')    or define('TEMP_FILE',TEMP_PATH.TEMP_NAME);
//加载核心编译文件
if (!DEBUG and is_file(TEMP_FILE)) {
    require TEMP_FILE;
} else {
    //编译文件
    define('SZPHP_PATH', str_replace('\\','/',dirname(__FILE__)) . '/');
    require SZPHP_PATH . 'Lib/Core/Boot.class.php';
    Boot::run();
}

?>