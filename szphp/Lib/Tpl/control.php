<?php
// +--------------------------------------------------------------------------
// | 深知网 SZPHP框架 项目
// +--------------------------------------------------------------------------
// | 作者: 李洪海 <shenzhiwanglhh@163.com> <http://www.shenzhiwang.com>
// +--------------------------------------------------------------------------
// | Copyright (c) 2010-2014 http://www.shenzhiwang.com All rights reserved.
// +--------------------------------------------------------------------------
// | 尊重原创版权，维护作者权益，共享劳动成果，端正做人姿态。请保留此版权信息。
// +--------------------------------------------------------------------------
//测试控制器类
class IndexControl extends Control{
    function index(){
        $this->display(PUBLIC_PATH . 'welcome.html');
    }
}
?>