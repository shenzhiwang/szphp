<?php
if (!defined("SZPHP_PATH"))
    exit('No direct script access allowed');
//.-----------------------------------------------------------------------------------
// |  Software: [SZPHP framework]
// |   Version: 2013.05
// |      Site: http://www.shenzhiwang.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://www.shenzhiwang.com.All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------


/**
 * 生成编译文件
 * @package SZPHP
 * @supackage core
 * @author hdxj<houdunwangxj@gmail.com>
 */
final class Boot
{
    /**
     * 运行框架
     * 在单入口文件引入框架SZPHP.php文件会自动执行run()方法，所以不用单独执行run方法
     * @access public
     * @return void
     */
    static public function run()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            ini_set('magic_quotes_runtime', 0);
            define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc() ? TRUE : FALSE);
        } else {
            define('MAGIC_QUOTES_GPC', false);
        }

        $root = str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME']));
        define('ROOT_PATH',             $root.'/'); //根目录
        define("DS",                    DIRECTORY_SEPARATOR); //目录分隔符
        define('IS_CGI',                substr(PHP_SAPI, 0, 3) == 'cgi' ? TRUE : FALSE);
        define('IS_WIN',                strstr(PHP_OS, 'WIN') ? TRUE : FALSE);
        define('IS_CLI',                PHP_SAPI == 'cli' ? TRUE : FALSE);
        define("SZPHP_DATA_PATH",       SZPHP_PATH . 'Data/'); //数据目录
        define("SZPHP_LIB_PATH",        SZPHP_PATH . 'Lib/'); //lib目录
        define("SZPHP_CONFIG_PATH",     SZPHP_PATH . 'Config/'); //配置目录
        define("SZPHP_CORE_PATH",       SZPHP_LIB_PATH . 'Core/'); //核心目录
        define("SZPHP_EXTEND_PATH",     SZPHP_PATH . 'Extend/'); //扩展目录
        define("SZPHP_ORG_PATH",        SZPHP_EXTEND_PATH . 'Org/'); //org目录
        define("SZPHP_DRIVER_PATH",     SZPHP_LIB_PATH . 'Driver/'); //驱动目录
        define("SZPHP_EVENT_PATH",      SZPHP_LIB_PATH . 'Event/'); //事件目录
        define("SZPHP_FUNCTION_PATH",   SZPHP_LIB_PATH . 'Function/'); //函数目录
        define("SZPHP_LANGUAGE_PATH",   SZPHP_LIB_PATH . 'Language/'); //语言目录
        define("SZPHP_TPL_PATH",        SZPHP_LIB_PATH . 'Tpl/'); //框架模板目录
        define('IS_GROUP',              defined("GROUP_PATH"));
        defined("STATIC_PATH")          or define("STATIC_PATH",'static/'); //网站静态文件目录
        //深知网 http://www.shenzhiwang.com
        defined('TEMPLATE_PATH')        or define("TEMPLATE_PATH", 'template/');//网站模板目录
        defined("COMMON_PATH")          or define("COMMON_PATH", IS_GROUP ? GROUP_PATH . 'Common/' : APP_PATH); //应用组公共目录
        defined("COMMON_CONFIG_PATH")   or define("COMMON_CONFIG_PATH", IS_GROUP ? COMMON_PATH . 'Config/' : APP_PATH); //应用组公共目录
        defined("COMMON_MODEL_PATH")    or define("COMMON_MODEL_PATH", IS_GROUP ? COMMON_PATH . 'Model/' : APP_PATH); //应用组公共目录
        defined("COMMON_CONTROL_PATH")  or define("COMMON_CONTROL_PATH", IS_GROUP ? COMMON_PATH . 'Control/' : APP_PATH); //应用组公共目录
        defined("COMMON_LANGUAGE_PATH") or define("COMMON_LANGUAGE_PATH", IS_GROUP ? COMMON_PATH . 'Language/' : APP_PATH); //应用组语言包目录
        defined("COMMON_EXTEND_PATH")   or define("COMMON_EXTEND_PATH", IS_GROUP ? COMMON_PATH . 'Extend/' : APP_PATH); //应用组扩展目录
        defined("COMMON_EVENT_PATH")    or define("COMMON_EVENT_PATH", IS_GROUP ? COMMON_PATH . 'Event/' : APP_PATH); //应用组事件目录
        defined("COMMON_TAG_PATH")      or define("COMMON_TAG_PATH", IS_GROUP ? COMMON_PATH . 'Tag/' : APP_PATH); //应用组标签目录
        defined("COMMON_LIB_PATH")      or define("COMMON_LIB_PATH", IS_GROUP ? COMMON_PATH . 'Lib/' : APP_PATH); //应用组扩展包目录
        //加载核心文件
        self::loadCoreFile();
        //加载基本配置
        self::loadConfig();
        //编译核心文件
        self::compile();
        //应用初始化
        SZPHP::init();
        //创建应用目录
        self::mkDirs();
        //运行应用
        App::run();
    }

    /**
     * 加载核心文件
     * @access private
     * @return void
     */
    static private function loadCoreFile()
    {
        $files = array(
            SZPHP_CORE_PATH . 'SZPHP.class.php', //SZPHP顶级类
            SZPHP_CORE_PATH . 'Control.class.php', //SZPHP顶级类
            SZPHP_CORE_PATH . 'SzException.class.php', //异常处理类
            SZPHP_CORE_PATH . 'App.class.php', //SZPHP顶级类
            SZPHP_CORE_PATH . 'Route.class.php', //URL处理类
            SZPHP_CORE_PATH . 'Event.class.php', //事件处理类
            SZPHP_CORE_PATH . 'Log.class.php', //公共函数
            SZPHP_FUNCTION_PATH . 'Functions.php', //应用函数
            SZPHP_FUNCTION_PATH . 'Common.php', //公共函数
            SZPHP_CORE_PATH . 'Debug.class.php', //Debug处理类
        );
        foreach ($files as $v) {
            require($v);
        }
    }

    /**
     * 加载基本配置
     * @access private
     */
    static private function loadConfig()
    {
        //系统配置
        C(require(SZPHP_CONFIG_PATH . 'config.php'));
        //系统事件
        C("CORE_EVENT", require(SZPHP_CONFIG_PATH . 'event.php'));
        //系统语言
        L(require(SZPHP_LANGUAGE_PATH . 'zh.php'));
        //别名
        alias_import(require(SZPHP_CORE_PATH . 'Alias.php'));
    }


    /**
     * 创建项目运行目录
     * @access private
     * @return void
     */
    static public function mkDirs()
    {
        if (IS_GROUP and is_dir(COMMON_PATH)) return;
        if (is_dir(CONTROL_PATH)) return;
        //目录
        $dirs = array(
            CONTROL_PATH,
            CONFIG_PATH,
            LANGUAGE_PATH,
            MODEL_PATH,
            CONFIG_PATH,
            EVENT_PATH,
            TAG_PATH,
            LIB_PATH,
            COMPILE_PATH,
            CACHE_PATH,
            TABLE_PATH,
            LOG_PATH,
            TPL_PATH,
            PUBLIC_PATH,
            TEMP_PATH,
            STATIC_PATH,
            TEMPLATE_PATH//深知网 http://www.shenzhiwang.com
        );
        //应用组模式
        if (IS_GROUP) {
            $dirs = array_merge($dirs, array(
                COMMON_PATH,
                COMMON_CONFIG_PATH,
                COMMON_MODEL_PATH,
                COMMON_CONTROL_PATH,
                COMMON_LANGUAGE_PATH,
                COMMON_EVENT_PATH,
                COMMON_TAG_PATH,
                COMMON_LIB_PATH
            ));
        }
        foreach ($dirs as $d) {
            if (!dir_create($d, 0755)):
                header("Content-type:text/html;charset=utf-8");
                exit("目录{$d}创建失败，请检查权限");
            endif;
        }
        //复制公共模板文件
        is_file(PUBLIC_PATH . "success.html") or copy(SZPHP_TPL_PATH . "success.html", PUBLIC_PATH . "success.html");
        is_file(PUBLIC_PATH . "error.html") or copy(SZPHP_TPL_PATH . "error.html", PUBLIC_PATH . "error.html");
        // +--------------------------------------------------------------------------
        // | 注释：复制应用创建成功的欢迎使用文件
        // +--------------------------------------------------------------------------
        // | 作者: 李洪海 <shenzhiwanglhh@163.com> <http://www.shenzhiwang.com>
        // +--------------------------------------------------------------------------
        // | 时间: 2014.04.28
        // +--------------------------------------------------------------------------
        // | Copyright (c) 2010-2014 http://www.shenzhiwang.com All rights reserved.
        // +--------------------------------------------------------------------------
        // | 尊重原创版权，维护作者权益，共享劳动成果，端正做人姿态。请保留此版权信息。
        // +--------------------------------------------------------------------------
        is_file(PUBLIC_PATH . "welcome.html") or copy(SZPHP_TPL_PATH . "welcome.html", PUBLIC_PATH . "welcome.html");
        is_file(PUBLIC_PATH . "welcome.jpg") or copy(SZPHP_TPL_PATH . "welcome.jpg", PUBLIC_PATH . "welcome.jpg");
        //复制配置文件
        is_file(CONFIG_PATH . "config.php") or copy(SZPHP_TPL_PATH . "config.php", CONFIG_PATH . "config.php");
        is_file(CONFIG_PATH . "event.php") or copy(SZPHP_TPL_PATH . "event.php", CONFIG_PATH . "event.php");
        is_file(CONFIG_PATH . "alias.php") or copy(SZPHP_TPL_PATH . "alias.php", CONFIG_PATH . "alias.php");
        //应用组模式
        if (IS_GROUP) {
            //复制配置文件
            //is_file(COMMON_CONFIG_PATH . "config.php") or copy(SZPHP_TPL_PATH . "config.php", COMMON_CONFIG_PATH . "config.php");
            //深知网 http://www.shenzhiwang.com
            is_file(COMMON_CONFIG_PATH . "conf.php") or copy(SZPHP_TPL_PATH . "conf.php", COMMON_CONFIG_PATH . "conf.php");
            is_file(COMMON_CONFIG_PATH . "event.php") or copy(SZPHP_TPL_PATH . "event.php", COMMON_CONFIG_PATH . "event.php");
            is_file(COMMON_CONFIG_PATH . "alias.php") or copy(SZPHP_TPL_PATH . "alias.php", COMMON_CONFIG_PATH . "alias.php");
            is_file(COMMON_LANGUAGE_PATH . "alias.php") or copy(SZPHP_TPL_PATH . "alias.php", COMMON_LANGUAGE_PATH . "alias.php");
        }
        //创建测试控制器
        is_file(CONTROL_PATH . 'IndexControl.class.php') or file_put_contents(CONTROL_PATH . 'IndexControl.class.php', file_get_contents(SZPHP_TPL_PATH . 'control.php'));
        //创建安全文件
        self::safeFile();
    }

    /**
     * 创建安全文件
     * @access private
     * @return void
     */
    static private function safeFile()
    {
        if (!defined("DIR_SAFE")) return;
        $dirs = array(
            COMMON_PATH,
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LANGUAGE_PATH,
            COMMON_EVENT_PATH,
            COMMON_TAG_PATH,
            COMMON_LIB_PATH,
            APP_PATH,
            CONTROL_PATH,
            CONFIG_PATH,
            LANGUAGE_PATH,
            MODEL_PATH,
            CONFIG_PATH,
            EVENT_PATH,
            TAG_PATH,
            LIB_PATH,
            COMPILE_PATH,
            CACHE_PATH,
            TABLE_PATH,
            LOG_PATH,
            TPL_PATH,
            PUBLIC_PATH,
            TEMP_PATH,
        );
        $file = SZPHP_TPL_PATH . '/index.html';
        foreach ($dirs as $d) {
            is_file($d . '/index.html') || copy($file, $d . '/index.html');
        }
    }

    /**
     * 编译核心文件
     * @access private
     */
    static private function compile()
    {
        if (DEBUG) {
            is_file(TEMP_FILE) and unlink(TEMP_FILE);
            return;
        }
        $compile = '';
        //常量编译
        $_define = get_defined_constants(true);
        foreach ($_define['user'] as $n => $d) {
            if ($d == '\\') $d = "'\\\\'";
            else $d = is_int($d) ? intval($d) : "'{$d}'";
            $compile .= "defined('{$n}') OR define('{$n}',{$d});";
        }
        $files = array(
            SZPHP_CORE_PATH . 'App.class.php', //SZPHP顶级类
            SZPHP_CORE_PATH . 'Control.class.php', //控制器基类
            SZPHP_CORE_PATH . 'Debug.class.php', //Debug处理类
            SZPHP_CORE_PATH . 'Event.class.php', //事件处理类
            SZPHP_CORE_PATH . 'SZPHP.class.php', //SZPHP顶级类
            SZPHP_CORE_PATH . 'SzException.class.php', //异常处理类
            SZPHP_CORE_PATH . 'Log.class.php', //Log日志类
            SZPHP_CORE_PATH . 'Route.class.php', //URL处理类
            SZPHP_FUNCTION_PATH . 'Functions.php', //应用函数
            SZPHP_FUNCTION_PATH . 'Common.php', //公共函数
            SZPHP_DRIVER_PATH . 'Cache/Cache.class.php', //缓存基类
            SZPHP_DRIVER_PATH . 'Cache/CacheFactory.class.php', //缓存工厂类
            SZPHP_DRIVER_PATH . 'Cache/CacheFile.class.php', //文件缓存处理类
            SZPHP_DRIVER_PATH . 'Db/Db.class.php', //数据处理基类
            SZPHP_DRIVER_PATH . 'Db/DbFactory.class.php', //数据工厂类
            SZPHP_DRIVER_PATH . 'Db/DbInterface.class.php', //数据接口类
            SZPHP_DRIVER_PATH . 'Db/DbMysqli.class.php', //Mysqli驱动类
            SZPHP_DRIVER_PATH . 'Model/Model.class.php', //模型基类
            SZPHP_DRIVER_PATH . 'Model/RelationModel.class.php', //关联模型类
            SZPHP_DRIVER_PATH . 'Model/ViewModel.class.php', //视图模型类
            SZPHP_DRIVER_PATH . 'View/ViewHd.class.php', //Hd视图驱动类
            SZPHP_DRIVER_PATH . 'View/View.class.php', //视图库
            SZPHP_DRIVER_PATH . 'View/ViewFactory.class.php', //视图工厂库
            SZPHP_DRIVER_PATH . 'View/ViewCompile.class.php', //模板编译类
            SZPHP_EXTEND_PATH . 'Tool/Dir.class.php', //目录操作类
        );
        foreach ($files as $f) {
            $con = compress(trim(file_get_contents($f)));
            $compile .= substr($con, -2) == '?>' ? trim(substr($con, 5, -2)) : trim(substr($con, 5));
        }
        //SZPHP框加核心配置
        $compile .= 'C(' . var_export(C(), true) . ');';
        //SZPHP框架核心语言包
        $compile .= 'L(' . var_export(L(), true) . ');';
        //别名配置文件
        $compile .= 'alias_import(' . var_export(alias_import(), true) . ');';
        //编译内容
        $compile = '<?php if(!defined(\'DEBUG\'))exit;' . $compile . 'SZPHP::init();App::run();?>';
        //创建Boot编译文件
        if (is_dir(TEMP_PATH) or dir_create(TEMP_PATH) and is_writable(TEMP_PATH))
            return file_put_contents(TEMP_FILE, compress($compile));
        header("Content-type:text/html;charset=utf-8");
        exit("<div style='border:solid 1px #dcdcdc;padding:30px;'>目录创建失败，请修改" . realpath(dirname(TEMP_PATH)) . "目录权限</div>");
    }

}

?>
