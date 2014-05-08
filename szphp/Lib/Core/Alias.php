<?php
if (!defined("SZPHP_PATH")) exit('No direct script access allowed');
// .-----------------------------------------------------------------------------------
// |  Software: [SZPHP framework]
// |   Version: 2013.01
// |      Site: http://www.shenzhiwang.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://shenzhiwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------

/**
 * 系统核心类库包 自动加载优先
 * @package     Core
 * @author      深知网向军 <houdunwangxj@gmail.com>
 */
return array(
    "ip" => SZPHP_EXTEND_PATH . 'Org/Ip/Ip.class.php', //IP处理类
    "mail" => SZPHP_EXTEND_PATH . 'Org/Mail/Mail.class.php', //IP处理类
    "UEDITOR_UPLOAD" => SZPHP_EXTEND_PATH . 'Org/Ueditor/php/ueditor_upload.php', //ueditor
    "KEDITOR_UPLOAD" => SZPHP_EXTEND_PATH . 'Org/Keditor/php/upload_json.php', //keditor
    "HD_UPLOADIFY" => SZPHP_EXTEND_PATH . 'Org/Uploadify/sz_uploadify.php', //uploadify上传
    "HD_UPLOADIFY_DEL" => SZPHP_EXTEND_PATH . 'Org/Uploadify/sz_uploadify.php', //uploadify删除
    "editorCatcherUrl" => SZPHP_EXTEND_PATH . 'Org/Editor/Ueditor/php/ueditorCatcherUrl.php', //ueditor,
);
?>
