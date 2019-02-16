<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace cmf\controller;

class UserBaseController extends HomeBaseController
{
	public $userinfo;
    public function _initialize()
    {
        parent::_initialize();
        //chancle原来的登陆方法
        //$this->checkUserLogin_old();
        //chancle 重新定义的方法
        $this->userinfo = $this->checkUserLogin();
        
    }


}