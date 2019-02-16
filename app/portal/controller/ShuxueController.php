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
use cmf\controller\UserBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\CepingModel;
use app\admin\model\ShuxueModel;
use app\portal\model\HuiyuanModel;

class ShuxueController extends AdminBaseController
{
    function _initialize() {
		$this->CepingModel = new CepingModel();
		$this->ShuxueModel = new ShuxueModel();
		$this->HuiyuanModel = new HuiyuanModel();
		$this->UserBaseController = new UserBaseController();
    }
    
    /**
    * 获取年级列表
    */
    public function get_njlist(){
    	$list = $this->SchoolModel->getNianjis();
		echo return_json($list);
		exit ();
    }

	
	/**
     * 视频列表
     */
	public function get_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24;
		$user = $this->HuiyuanModel->user_by_id($uid);
		$list= $this->ShuxueModel->get_list($uid,$user['grade'],1);
		
		$liebiao_sx= $this->CepingModel->slide_itemlist('数学首页效果');
		
		$gradeinfo= Db::name('nianji')->where(array('id'=>$user['grade']))->find();
		$liebiao_sx[0]['grade'] = $gradeinfo['n_name'];
		// var_dump($list);die;
		$data ['info'] = $liebiao_sx[0];
		$data ['list'] = $list;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	
	/**
     * 视频详情
     */
	public function get_info(){
		$id = $this->request->post('id');
		$info= $this->ShuxueModel->get_info($id);
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
    

}
