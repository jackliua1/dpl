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
use app\admin\model\YuwenModel;
use app\admin\model\PoetryModel;
use app\portal\model\HuiyuanModel;

class YuwenController extends AdminBaseController
{
    function _initialize() {
		$this->CepingModel = new CepingModel();
		$this->YuwenModel = new YuwenModel();
		$this->HuiyuanModel = new HuiyuanModel();
		$this->PoetryModel = new PoetryModel();
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
		$list= $this->YuwenModel->get_list($uid,$user['grade'],1);
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	/**
     * 视频详情
     */
	public function get_info(){
		$id = $this->request->post('id');
		$info= $this->YuwenModel->get_info($id);
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
    
	
	
	//写作列表
	public function write_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24;
		$user = $this->HuiyuanModel->user_by_id($uid);
		
		$list= $this->YuwenModel->write_list($user['grade']);
		// var_dump($user);var_dump($list);die;
		// $list= $this->YuwenModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	
	//写作提交
	public function submit_write(){
		//增加访问量 古诗词品鉴
		$this->CepingModel->slide_addnum(11);
		// $sid = $this->request->post('sid');
		// $url = $this->request->post('url');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		
		$filename = $_FILES['file']['name'];
		// var_dump($filename);var_dump($_FILES);die;
		foreach($filename as $key => $vo){
			$file[$key]['file']['name'] = $_FILES['file']['name'][$key];
			$file[$key]['file']['tmp_name'] = $_FILES['file']['tmp_name'][$key];
		}
		
		$imgurl = [];
		foreach($file as $key => $vo){
			$url[$key] = $this->YuwenModel->imgfile($vo,$key+1,'yw_xiezuo');
			if($url[$key]){
				$imgurl[]= $url[$key];
			}
			
		}
		
		$url = json_encode($imgurl);
		$info = $this->YuwenModel->write_jilu_add($uid,$url);
		// var_dump($write);var_dump($jilu);die;

		if($info){
			$message ['jl_id'] = $info;
			$message ['code'] = 1;
			$message ['msg'] = '提交成功';
			$message ['imgurl'] = $imgurl;
			echo json_encode ( $message );
			exit ();
		}else{
			$message ['code'] = 0;
			$message ['msg'] = '提交失败';
			echo json_encode ( $message );
			exit ();
		}
	}
	//我的写作列表
	public function my_write_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		// $uid =24;
		// $user = $this->HuiyuanModel->user_by_id($uid);
		
		$list= $this->YuwenModel->write_jilu_select($uid,$start,$limit);
		// var_dump($user);var_dump($list);die;
		// $list= $this->YuwenModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	//我的写作详情
	public function my_write_info(){
		$id = $this->request->post('id');
		
		$info= $this->YuwenModel->my_write_info($id);
		// var_dump($user);var_dump($list);die;
		// $list= $this->YuwenModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	//古诗词首页
	public function poetry_index(){
		//增加访问量 古诗词品鉴
		$this->CepingModel->slide_addnum(9);
		//幻灯片
		$slidelist= $this->CepingModel->slide_itemlist('古诗词鉴赏幻灯片');
		
		
		//古诗类型列表
		$classlist= $this->PoetryModel->get_class_list();
		
		//古诗列表
		$hotlist= $this->PoetryModel->get_hot_list(5);
		// var_dump($user);var_dump($list);die;
		// $list= $this->YuwenModel->write_silently_list($uid);
		$data['slidelist'] = $slidelist;
		$data['classlist'] = $classlist;
		$data['hotlist'] = $hotlist;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	//分类古诗列表
	public function get_list_by_classid(){
		$classid = $this->request->post('classid');
		
		
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		
		
		//古诗类型列表
		$list= $this->PoetryModel->get_list_by_classid($classid,$start,$limit);
		
		//古诗类型详情
		$info= $this->PoetryModel->get_classinfo($classid);
		
		$data = $info;
		$data['list'] = $list;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	//古诗词详情
	public function poetry_info(){
		$id = $this->request->post('id');
		//古诗类型列表
		$info= $this->PoetryModel->get_info($id);
		
		
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	//古诗词详情
	public function poetry_search(){
		$name = $this->request->post('name');
		
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		//古诗类型列表
		$info= $this->PoetryModel->poetry_search($name,$start,$limit);
		
		
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	//美文分类
	public function topic_class_list(){
		//古诗类型列表
		$classlist= $this->YuwenModel->get_topic_class_list();

		$message ['code'] = 1;
		$message ['data'] = $classlist;
		echo json_encode ( $message );
		exit ();
	}
	
	//美文首页
	public function topic_index(){
		$classid = $this->request->post('classid');

		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		
		if($classid==1){
			//增加访问量 名著名篇听读
			$this->CepingModel->slide_addnum(8);
		}
		
		
		
		//古诗类型列表
		$classlist= $this->YuwenModel->get_topic_class_list();
		//古诗列表
		$topiclist= $this->YuwenModel->get_topiclist_byclass($classid,$start,$limit);
		// var_dump($user);var_dump($list);die;
		// $list= $this->YuwenModel->write_silently_list($uid);
		if(empty($topiclist)){
			$topiclist = [];
		}
		
		$data['classlist'] = $classlist;
		$data['topiclist'] = $topiclist;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	//美文详情
	public function topic_info(){
		$id = $this->request->post('id');
		//古诗类型列表
		$save= $this->YuwenModel->topic_num_add($id);
		//古诗类型列表
		$info= $this->YuwenModel->get_topic_info($id);
		
		
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	//阅读理解列表
	public function reading_index(){
		//增加访问量 古诗词品鉴
		$this->CepingModel->slide_addnum(10);
		
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		$num = intval((time() - strtotime("2018-12-5"))/1000);//基础数值
		// var_dump($this->UserBaseController->userinfo);die;
		$liebiao_yw= $this->CepingModel->slide_itemlist('语文首页效果');
		$info['imgurl'] = $liebiao_yw[2]['image'];
		$info['title'] = $liebiao_yw[2]['title'];
		$info['description'] = $liebiao_yw[2]['description'];
		$info['nianji'] = $this->UserBaseController->userinfo['n_name'];
		$info['studynum'] =$num+$liebiao_yw[2]['num'];
		
		
		//阅读列表
		$list= $this->YuwenModel->get_reading_list($start,$limit);
		
		$data['info'] = $info;
		$data['list'] = $list;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	//阅读详情
	public function reading_info(){
		$id = $this->request->post('id');
		$info= $this->YuwenModel->get_reading_info($id);
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}

}
