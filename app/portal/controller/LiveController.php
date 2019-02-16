<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: chancle <893296307@qq.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\AdminBaseController;
use app\portal\model\LiveModel;

class LiveController extends AdminBaseController
{
    function _initialize() {
		$this->LiveModel = new LiveModel();
    }
    
    /**
    * 获取直播教师列表
    */
   public function teacherlist_bykc(){
		// if($this->request->post('uid')){
			// $uid = $this->request->post('uid');
		// }else{
			// $uid = $this->UserBaseController->userinfo['id'];
		// }
		// $uid =24;
		$kcid = $this->request->post('kcid');
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		$list= $this->LiveModel->liveteacherlist_by_kcid($kcid,$start,$limit);
				// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list['list'];
		echo json_encode ( $message );
		exit ();
	}
	
	/**
    * 获取直播教师列表
    */
   public function livelist_bytid(){
		// if($this->request->post('uid')){
			// $uid = $this->request->post('uid');
		// }else{
			// $uid = $this->UserBaseController->userinfo['id'];
		// }
		// $uid =24;
		$tid = $this->request->post('tid');
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		$list= $this->LiveModel->livelist_bytid($tid,$start,$limit);
				// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list['list'];
		echo json_encode ( $message );
		exit ();
	}
	/**
    * 获取直播详情
    */
   public function liveinfo_byid(){
		// if($this->request->post('uid')){
			// $uid = $this->request->post('uid');
		// }else{
			// $uid = $this->UserBaseController->userinfo['id'];
		// }
		// $uid =24;
		$id = $this->request->post('id');
	
		$list= $this->LiveModel->liveinfo_byid($id);
				// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list['info'];
		echo json_encode ( $message );
		exit ();
	}
	/**
    * 获取直播评价列表
    */
   public function commentlist_bylid(){
		// if($this->request->post('uid')){
			// $uid = $this->request->post('uid');
		// }else{
			// $uid = $this->UserBaseController->userinfo['id'];
		// }
		// $uid =24;
		$lid = $this->request->post('lid');
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		$list= $this->LiveModel->commentlist_bylid($lid,$start,$limit);
				// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list['list'];
		echo json_encode ( $message );
		exit ();
	}

}
