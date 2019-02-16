<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\AdminBaseController;
use cmf\controller\UserBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;
use app\admin\model\CepingModel;
use app\portal\model\HuiyuanModel;
use app\portal\model\TransapiModel;
use app\admin\model\YuwenModel;
use alisms\SendSms;

class PortController extends AdminBaseController
{
	
	function _initialize() {
		
		$this->CepingModel = new CepingModel();
		$this->HuiyuanModel = new HuiyuanModel();
		$this->TransapiModel = new TransapiModel();
		$this->YuwenModel = new YuwenModel();
		$this->UserBaseController = new UserBaseController();
    }
	/**
     * 试卷列表
     */
	// public function userinfo(){
		// $uid = $this->request->post('uid');
		// $user = $this->HuiyuanModel->user_by_id($uid);
		// $list= $this->CepingModel->my_selectTest($uid,$user['grade']);
		// $message ['code'] = 1;
		// $message ['data'] = $list;
		// echo json_encode ( $message );
		// exit ();
	// }
	/**
     * 基础信息 type 
	 * 版本控制 version_control 
	 * 支付宝信息 alipay_control 
	 * 微信信息 wechat_control 
	 
     */
	public function setting(){
		$type = $this->request->post('type');
		$info=Db::name('dplsetting')->where(array('type'=>$type ))->find();
		
		$content = json_decode($info['content'],true);
		$message ['code'] = 1;
		$message ['data'] = $content;
		echo json_encode ( $message );
		exit ();
	}
	
	//更新消息
	public function dpl_setting(){

		$version = $this->request->param('version');//版本号
		
		$setting=Db::name('dplsetting')->where(array('type'=>'version_control'))->find();
		$content = json_decode($setting['content'],true);
		$content['url'] = cmf_get_image_url($content['url']);
		// var_dump($version);var_dump($_GET);die;
		// $setting = array('version'=>'1.1','content'=>'1.修复已知BUG','url'=>'http://dpl.zoyomei.com/upload/admin/app-debug.apk');
		
		if($content['version']!=$version){
			$message ['code'] = 1;
			$message ['data'] = $content;
			echo json_encode ( $message );
			exit ();
		}else{
			$message ['code'] = 0;
			$message ['data'] = $content;
			echo json_encode ( $message );
			exit ();
		}
	}
	/**
     * 幻灯片列表
     */
	public function slide_itemlist(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24;
		// $user = $this->HuiyuanModel->user_by_id($uid);
		// var_dump($user);die;
		$list= $this->CepingModel->slide_itemlist('dpl首页顶部广告');
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	//英语模拟训练类型
	public function english_class(){
		//增加访问量 江苏中考听力口语模拟训练
		// $this->CepingModel->slide_addnum(4);
		
		//幻灯片
		$slidelist= $this->CepingModel->slide_itemlist('英语模拟训练类型');
		
		$data['slidelist'] = $slidelist;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	//英语模拟训练类型
	public function welcome(){
		//幻灯片
		$slidelist= $this->CepingModel->slide_itemlist('欢迎页广告');
		
		$data['info'] = $slidelist;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	/**
     * 试卷列表
     */
	public function shijuanlist(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24;
		$user = $this->HuiyuanModel->user_by_id($uid);
		$list= $this->CepingModel->my_selectTest($uid,$user['grade'],1);
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['info'] = $list['info'];
		$message ['data'] = $list['list'];
		echo json_encode ( $message );
		exit ();
	}
	/**
     * l老师试卷列表
     */
	public function teacher_shijuanlist(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24;
		$user = $this->HuiyuanModel->user_by_id($uid);
		// var_dump($user);die;
		$list= $this->CepingModel->my_selectTest($uid,$user['grade'],2);
		
		
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	/**
     * 专项训练
     */
	public function zhuanxiang_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $uid =24; 
		$list[0]['name'] = '2019江苏初中听力口语自动化考试纲要1A';
		$list[0]['url'] = cmf_get_image_url('zhuanxiang/2019jszk1A.mp3');
		$list[1]['name'] = '2019江苏初中听力口语自动化考试纲要1B';
		$list[1]['url'] = cmf_get_image_url('zhuanxiang/2019jszk1B.mp3');
		$list[2]['name'] = '2019江苏初中听力口语自动化考试纲要2A';
		$list[2]['url'] = cmf_get_image_url('zhuanxiang/2019jszk2A.mp3');
		$list[3]['name'] = '2019江苏初中听力口语自动化考试纲要2B';
		$list[3]['url'] = cmf_get_image_url('zhuanxiang/2019jszk2B.mp3');
		$list[4]['name'] = '中考英语自动化考试听力过关1A';
		$list[4]['url'] = cmf_get_image_url('zhuanxiang/jszk1A.mp3');
		$list[5]['name'] = '中考英语自动化考试听力过关1B';
		$list[5]['url'] = cmf_get_image_url('zhuanxiang/jszk1B.mp3');
		$list[6]['name'] = '中考英语自动化考试听力过关2A';
		$list[6]['url'] = cmf_get_image_url('zhuanxiang/jszk2A.mp3');
		$list[7]['name'] = '中考英语自动化考试听力过关2B';
		$list[7]['url'] = cmf_get_image_url('zhuanxiang/jszk2B.mp3');
		
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	/**
     * 试卷详情
     */
	public function shijuaninfo(){
		$id = $this->request->post('id');
		/**测试数据**/
		// $id = 1;
		/**测试数据end**/
		
		$info= $this->CepingModel->selectTestinfo($id);
		// var_dump($info);die;
		// $message ['code'] = 1;
		// $message ['data'] = $info;
		echo json_encode ( $info );
		exit ();
	}
	/**
     * 随机试卷详情
     */
	public function shijuaninfo_suiji(){
		$type = $this->request->post('type');
		/**测试数据**/
		// $id = 1;
		/**测试数据end**/
		
		$info= $this->CepingModel->selectTestinfo_suiji($type);
		// var_dump($info);die;
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	/**
     * 试卷题目列表
     */
	public function timuinfo(){
		$id = $this->request->post('id');
		$type = $this->request->post('type');
		$test_id = $this->request->post('test_id');
		$test_type = $this->request->post('test_type');
		/**测试数据**/
		// $id = 68;
		// $type = 1;
		/**测试数据end**/

		$info= $this->CepingModel->findTimuinfo($id,$type,$test_id ,$test_type );
		// var_dump($info);die;
		
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	
	/**
     * 提交
     */
	public function tijiaoShijuan(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$test_type = $this->request->post('test_type');
		$test_id = $this->request->post('test_id');
		$flag = $this->request->post('flag');
		// if($flag==1){
			$shuju = $_POST['shuju'];
		// }else{
			// $shuju = $this->request->post('shuju');
		// }
		// $shuju = $this->request->post('shuju');
		
		
		/**测试数据**/
		// $uid = 1;
		// $test_id = 1;
		// $shuju0 = array('108'=>'A','111'=>'A','110'=>'A','112'=>'A','114'=>'A','115'=>'A','126'=>'http://portal/20181018/1dfd0155fd8f2b469baf7be156a9d159.mp3','140'=>'http://portal/20181018/1dfd0155fd8f2b469baf7be156a9d159.mp3','141'=>'A','142'=>'A',);
		/**测试数据end**/
		
		if($flag==1){
			// $data = explode(",", $shuju);
			$datalist = json_decode($shuju);
			// file_put_contents("test23232.txt", $shuju.'\n', FILE_APPEND);
			foreach($datalist as $k => $vo){
				$vo2 = (array)$vo;
				$newkey = $vo2['id'];
				$newarray[$newkey] = $vo2['an'];
			}
			$shuju = $newarray;
		}
		// var_dump($shuju);die;
		if(empty($shuju)){
			$message ['code'] = 0;
			$message ['msg'] = '未发现数据';
		}else{
			$shuju = json_encode($shuju);
			$score= $this->CepingModel->tijiaoShijuan($uid,$test_id,$shuju,$test_type);
			// var_dump($info);die;
			
			$message ['code'] = 1;
			$message ['data'] = $score;
			
		}
		echo json_encode ( $message );
		exit ();
		
	}
	
	
    //上传语音
    public function urlfile(){
		
		$ios = $this->request->post('ios');
		
		
		
		
		$url= $this->CepingModel->urlfile($_FILES);
		$message ['code'] = 1;
		$message ['url'] = $url;
		echo json_encode ( $message );
		exit ();

	}
	
	//获取答题记录
	public function getdatijilu(){
		$id = $this->request->post('id');
		$data= $this->CepingModel->getdatijilu($id);
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();

	}
	//获取单条答题记录
	public function getdatijilu_one(){
		$id = $this->request->post('id');
		$data= $this->CepingModel->getdatijilu_one($id);
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();

	}
	//获取我的错题记录
	public function getmydatijilu(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;
		$data= $this->CepingModel->getmydatijilu($uid,$start,$limit);
		$message ['page'] = $page;
		$message ['limit'] = $limit;
		$message ['start'] = $start;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();

	}
	//我的错题状态删除
	public function my_error_timu_update(){
		// $wid = $this->request->post('wid');
		$id = $this->request->post('id');
		
		$widlist = explode(',',$id);
		foreach($widlist as $vo){
			$word= $this->CepingModel->my_error_timu_update($vo);
		}
		if( $word ==0  ){
			$message ['code'] = 0;
			$message ['data'] = 0;
		}else{
			$message ['code'] = 1;
			$message ['data'] = $word;
		}
		
		echo json_encode ( $message );
		exit ();
	}
	public function sendsmsceshi(){
		// var_dump($_GET);die;
		// $getmobile = $_GET['mobile'];
		$postmobile = $_POST['mobile'];
		// $message ['getmobile'] = $getmobile;
		$message ['postmobile'] = $postmobile;
		echo json_encode ( $message );
		exit ();
	}
	//发送短信
	public function sendsms(){
		$mobile = $this->request->post('mobile');
		// $mobile = $_POST['mobile'];
		// var_dump($mobile );die;
		
		// if($mobile=='13861681460'){
			// $postshuju = $this->request->post();
			// $message ['code'] = 0;
			// $message ['getold'] = json_encode($_GET);
			// $message ['requestold'] = json_encode($_REQUEST);
			// $message ['postold'] = json_encode($_POST);
			// $message ['postnew'] = json_encode($postshuju);
			// $message ['msg'] = '查看数据';
			// echo json_encode ( $message );
			// exit ();
		// }
		
		// $mobile = 15189386706;
		$num = rand ( 100000, 999999 );
		
		$this->CepingModel->sendinserf($mobile,$num);
		//获取对象，如果上面没有引入命名空间，可以这样实例化：$sms = new \alisms\SendSms()
        $sms = new SendSms();
		$sms->accessKeyId = 'LTAI5HCFXnHkUJsg';
        $sms->accessKeySecret = 'AfTAPbglQ5Ag5bTQelh3A0W5Sr6Ri4';
        $sms->signName = 'DPL';
        $sms->templateCode = 'SMS_151625120';
		
		
		$code = mt_rand();
        $templateParam = array("code"=>$num);
        $info = $sms->send($mobile,$templateParam);
		// $info=json_decode ( $m );
		$message ['num'] = '';
		// var_dump($info);die;
		if($info['Code']=='OK'){
			$message ['code'] = 1;
			$message ['msg'] = '发送成功';
			$message ['num'] = $num;
		}else{
			$message ['code'] = 0;
			if($info['Code']=='isv.BUSINESS_LIMIT_CONTROL'){
				$message ['msg'] = '业务限流，该手机号发送过于频繁';
			}elseif($info['Code']=='isv.AMOUNT_NOT_ENOUGH'){
				$message ['msg'] = '平台短信';
			}else{
				$message ['msg'] = '发送失败';
			}
		}
		echo json_encode ( $message );
		exit ();
	}
	
	//登录
	public function applogin(){
		$mobile = $this->request->post('mobile');
		$code = $this->request->post('code');
		// $mobile = 15189386706;
		// var_dump($mobile);var_dump($code);die;
		// $code = 537915;
		$verify= $this->CepingModel->sendVerify($mobile,$code);
		// var_dump($verify);die;
		if($verify==1){
			$huiyuan = $this->HuiyuanModel->user_by_tel($mobile);
			if($huiyuan){
				$message ['code'] = 1;
				$message ['uid'] = $huiyuan['id'];
				$message ['msg'] = '登录成功';
			}else{
				$message ['code'] = 0;
				$message ['msg_type'] = 2;
				$message ['msg'] = '用户不存在';
			}
			
		}else{
			$message ['code'] = 0;
			$message ['msg_type'] = 1;
			$message ['msg'] = '验证码错误';
		}
		
		// $info= $this->CepingModel->sendsms(1,$mobile,1,$num);
		// $message ['code'] = 1;
		// $message ['info'] = $info;
		echo json_encode ( $message );
		exit ();
	}

	//默写列表
	public function write_silently_list(){
		//增加访问量 词汇轻松学
		$this->CepingModel->slide_addnum(5);
		
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		
		// $uid =24;
		// $user= $this->CepingModel->write_silently_list($uid);
		$user = $this->HuiyuanModel->user_by_id($uid);
		// var_dump($user);die;
		$list= $this->CepingModel->write_silently_list($user['grade']);
		$arr = array();
		foreach($list as $key => $vo){
			$vo['time']=date('Y-m-d',strtotime($vo['time']));
			$arr[] = $vo;
		}
		
		// $liebiao_sx= $this->CepingModel->slide_itemlist('英语首页效果');
		
		$gradeinfo= Db::name('nianji')->where(array('id'=>$user['grade']))->find();
		// $liebiao_sx[0]['grade'] = $gradeinfo['n_name'];
		// var_dump($user);var_dump($list);die;
		$studynum = intval((time() - strtotime("2018-12-5"))/1000);//基础数值

		$liebiao_sx= $this->CepingModel->slide_item_info(5);
		$liebiao_sx['grade'] = $gradeinfo['n_name'];
		$liebiao_sx['num'] = $studynum+$liebiao_sx['num'];
		$message ['code'] = 1;
		$message ['data'] = $arr;
		$message ['info'] = $liebiao_sx;
		echo json_encode ( $message );
		exit ();
	}
	
	 //上传默写图片
    public function imgfile(){
		$url= $this->CepingModel->imgfile($_FILES,'moxie');
		$message ['code'] = 1;
		$message ['url'] = $url;
		echo json_encode ( $message );
		exit ();
	}
	
	//默写提交图片
	public function submit_write_silently(){
		$sid = $this->request->post('sid');
		// $url = $this->request->post('url');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$url= $this->CepingModel->imgfile($_FILES,'moxie');
		// var_dump($url);die;
		// $uid =24;
		// $sid = 1;
		// $url ='./upload/luyin/666.png';
		
		$write =  $this->CepingModel->write_silently_info($sid);
		$muban =  $this->CepingModel->object_to_array(json_decode($write['answer']));
		
		$jilu =  $this->CepingModel-> write_silently_jilu_find($sid,$uid);
		
		// if(empty($jilu)){
				// }else{
			// $moxie = $this->CepingModel->object_to_array(json_decode($jilu['answer']));
		// }
		// var_dump($write);var_dump($muban);var_dump($jilu);die;
		
		$answer =  $this->CepingModel->readimg($url);
			// var_dump($answer);die;
		if($answer['code']==1){
			$moxie =  $answer['moxie'];
			$right = 0;
			for($i=0;$i<count($muban);$i++){
				$muban[$i]['rep'] = 'Unidentified';
				$muban[$i]['percent'] = 0;
				for($j=0;$j<count($moxie);$j++){
					similar_text($muban[$i]['que'],$moxie[$j]['que'], $percent1);
					
					if($percent1>95){
						$muban[$i]['rep'] = $moxie[$j]['anw'];
						similar_text($muban[$i]['anw'],$moxie[$j]['anw'], $percent2);
						$muban[$i]['percent'] =  $percent2;
						if($percent2==100){
							$right++;
						}
					}
				}
			}
			$this->CepingModel->write_silently_jilu_add($sid,$uid,json_encode($moxie),json_encode($muban));
			// var_dump($write);var_dump($moxie);var_dump($muban);die;
			// $data = $muban;
			
			if($muban){
				$score['r'] = $right;
				$score['c'] = count($muban);
				$score['score'] = ($right/count($muban))*100;
				$message ['score'] = $score;
			}
			
			
			$data = $muban;
			$message ['code'] = 1;
			$message ['data'] = $data;
			$message ['moxie'] = $moxie;
			
			echo json_encode ( $message );
			exit ();
		}else{
			$message ['code'] = 0;
			$message ['msg'] = $answer['msg'];
			echo json_encode ( $message );
			exit ();
		}
	}
	//我的默写记录列表
	public function my_write_silently_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $sid = 37;
		$list= $this->CepingModel->write_silently_jilu_select($uid);

		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	//我的默写记录详情
	public function my_write_silently_info(){
		$id = $this->request->post('id');
	
		// $sid = 37;
		$info= $this->CepingModel->my_write_silently_info($id);

		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	//Unit单词列表
	public function wordlist_by_sid(){
		$sid = $this->request->post('sid');
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		
		$start = ($page - 1) * $limit;
		// $sid = 2;
		$list= $this->CepingModel->wordlist_by_sid($sid,$start,$limit);
		// var_dump($list);die;
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	//单词详情
	public function word_by_sid(){
		$sid = $this->request->post('sid');
		
		// $sid = $this->request->param('sid');
		// $sid = 37;
		$word= $this->CepingModel->word_by_sid($sid);
		$word['url'] = $this->CepingModel->read_word($word['word']);
		
		if($word['url']=='语音合成失败！'){
			$word['url'] = '';
		}
		// $info= $this->TransapiModel->translate($word['word']);
		// var_dump($info);die;
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	//单词测试列表
	public function wordstudy_list(){
		$sid = $this->request->post('sid');
		// $sid = 37;
		$word= $this->CepingModel->wordstudy_list($sid);

		// $message ['code'] = 1;
		// $message ['data'] = $word;
		echo json_encode ( $word );
		exit ();
	}
	
	//单词测试提交
	public function wordstudy_submit(){
		$csid = $this->request->post('csid');
		$id = $this->request->post('id');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$answer = $this->request->post('answer');
		// $sid = 37;
		$word= $this->CepingModel->wordstudy_submit($csid,$uid,$id,$answer);

		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	//单词详情
	public function wordstudy_by_sid(){
		$sid = $this->request->post('sid');
		// $sid = 37;
		$word= $this->CepingModel->word_by_sid($sid);
		$word['url'] = $this->CepingModel->read_word($word['word']);
		// $info= $this->TransapiModel->translate($word['word']);
		// var_dump($info);die;
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	//单词测试结果
	public function wordstudy_result(){
		$csid = $this->request->post('csid');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$usetime = $this->request->post('time');
		// $sid = 37;
		$message= $this->CepingModel->wordstudy_result($csid,$uid,$usetime);

		// $message ['code'] = 1;
		// $message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	//添加到我的错词本
	public function to_my_error_word(){
		$wid = $this->request->post('wid');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		
		$widlist = explode(',',$wid);
		foreach($widlist as $vo){
			$word= $this->CepingModel->to_my_error_word($vo,$uid);
		}
		$message ['code'] = 1;
		$message ['data'] = 1;
		echo json_encode ( $message );
		exit ();
	}
	//我的错词本
	public function my_error_word_list(){
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		
		$start = ($page - 1) * $limit;
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$word= $this->CepingModel->my_error_word_list($uid,$start,$limit);
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	//我的错词状态删除
	public function my_error_word_update(){
		// $wid = $this->request->post('wid');
		$id = $this->request->post('id');
		
		$widlist = explode(',',$id);
		foreach($widlist as $vo){
			$word= $this->CepingModel->my_error_word_update($vo);
		}
		if( $word ==0  ){
			$message ['code'] = 0;
			$message ['data'] = 0;
		}else{
			$message ['code'] = 1;
			$message ['data'] = $word;
		}
		
		echo json_encode ( $message );
		exit ();
	}
	
	//读取单词
	public function read_word(){
		$word = $this->request->post('word');
		// $word = 'failed to open stream';
		$wordurl1 = str_replace('…','',$word);
		$wordurl2 = str_replace('  ',' ',$wordurl1);
		$wordurl = str_replace(' ','_',trim($wordurl2));
		$word = str_replace(' ','%20',trim($wordurl2));
		
		
		

		$url ='./upload/word/wd_'.$wordurl.'.mp3';
		$urlnew ='/upload/word/wd_'.$wordurl.'.mp3';
		// $urlnew =cmf_get_image_url('/upload/word/wd_'.$wordurl.'.mp3');
		// var_dump($word);var_dump($url);die;
		if(file_exists($url)){
			$message ['code'] = 1;
			$message ['msg'] = '语音获取成功！';
			$message ['data'] = $urlnew;
			echo json_encode ( $message );
			exit ();
		}else{
			// $wordreturn = $this->CepingModel->voiceTts($word, $url);
			$wordreturn = $this->CepingModel->baidufanyi($word, $url);
			if($wordreturn == '语音合成成功！'){
				$message ['code'] = 1;
				$message ['msg'] = '语音合成成功！';
				$message ['data'] = $urlnew;
				echo json_encode ( $message );
				exit ();
			}else{
				$message ['code'] = 0;
				$message ['msg'] = $wordreturn;
				echo json_encode ( $message );
				exit ();
			}
		}
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
		
		$list= $this->CepingModel->write_list($user['grade']);
		// var_dump($user);var_dump($list);die;
		// $list= $this->CepingModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	
	//写作提交
	public function submit_write(){
		//增加访问量 书面表达名师评阅
		$this->CepingModel->slide_addnum(6);
		// $sid = $this->request->post('sid');
		// $url = $this->request->post('url');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		if(!$_FILES){
			$message ['code'] = 0;
			$message ['msg'] = '请上传图片';
			echo json_encode ( $message );
			exit ();
		}
		$filename = $_FILES['file']['name'];
		// var_dump($filename);var_dump($_FILES);die;
		foreach($filename as $key => $vo){
			$file[$key]['file']['name'] = $_FILES['file']['name'][$key];
			$file[$key]['file']['tmp_name'] = $_FILES['file']['tmp_name'][$key];
		}
		
		$imgurl = [];
		foreach($file as $key => $vo){
			$url[$key] = $this->YuwenModel->imgfile($vo,$key+1,'xiezuo');
			if($url[$key]){
				$imgurl[]= $url[$key];
			}
			
		}
		
		$url = json_encode($imgurl);
		
		// $url= $this->CepingModel->imgfile($_FILES,'xiezuo');
		// var_dump($url);die;
		// $uid =24;
		// $sid = 1;
		// $url ='./upload/luyin/666.png';
		
		// $write =  $this->CepingModel->write_info($sid);
		$info = $this->CepingModel->write_jilu_add($uid,$url);
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
		
		$list= $this->CepingModel->write_jilu_select($uid,$start,$limit);
		// var_dump($user);var_dump($list);die;
		// $list= $this->CepingModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	//我的写作详情
	public function my_write_info(){
		$id = $this->request->post('id');
		
		$info= $this->CepingModel->my_write_info($id);
		// var_dump($user);var_dump($list);die;
		// $list= $this->CepingModel->write_silently_list($uid);
		$message ['code'] = 1;
		$message ['data'] = $info;
		echo json_encode ( $message );
		exit ();
	}
	
	//名著列表
	public function book_list(){
		$list= $this->CepingModel->book_list();
		
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	//名著详情 章节列表
	public function book_by_id(){
		$id = $this->request->post('id');
		// $sid = 37;
		$word= $this->CepingModel->book_info($id);
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	//名著章节内容
	public function book_content_by_fid(){
		//增加访问量 名著名篇听读
		$this->CepingModel->slide_addnum(7);
		$id = $this->request->post('fid');
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		// $sid = 37;
		$word= $this->CepingModel->book_content_by_fid($id,$uid);
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	//名著录音
	public function book_recording(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$fid = $this->request->post('fid'); //章节id
		$pid = $this->request->post('pid'); //章节id
		// $sid = 37;
		
		$url = $this->CepingModel->urlfile($_FILES);
		
		$this->CepingModel->book_recording($uid,$fid,$url,$pid);
		
		$data['url'] = cmf_get_domain().$url;
		$message ['code'] = 1;
		$message ['data'] = $data;
		echo json_encode ( $message );
		exit ();
	}
	
	
	//消息状态显示剩余数量
	public function notice_status(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}

		
		$list = $this->CepingModel->notice_status($uid);
		
		
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	//消息记录
	public function notice_list(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$type = $this->request->post('type'); //章节id
		// $sid = 37;
		$page = $this->request->post('page');
		$limit = $this->request->post('number');
		$start = ($page - 1) * $limit;

		
		$list = $this->CepingModel->notice_list($uid,$type,$start,$limit);
		
		
		$message ['code'] = 1;
		$message ['data'] = $list;
		echo json_encode ( $message );
		exit ();
	}
	
	//更新消息
	public function notice_updata(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$nid = $this->request->post('nid'); //章节id
		
		$id = $this->CepingModel->notice_updata($uid,$nid);
		
		
		$message ['code'] = 1;
		$message ['data'] = $id;
		echo json_encode ( $message );
		exit ();
	}
	//更新消息
	public function notice_updataall(){
		if($this->request->post('uid')){
			$uid = $this->request->post('uid');
		}else{
			$uid = $this->UserBaseController->userinfo['id'];
		}
		$type = $this->request->post('type'); //章节id
		
		$id = $this->CepingModel->notice_updataall($uid,$type);
		
		
		$message ['code'] = 1;
		$message ['data'] = $id;
		echo json_encode ( $message );
		exit ();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//测试图片识别
	public function tupianceshi(){
		// similar_text('1.我不确定你穿蓝色是否好看。', '我不确定你穿蓝色是否好看。', $percent);
		// var_dump($percent);die;
		 // echo $percent;   
		// $shuju[0]['id'] = 1; 
		// $shuju[0]['val'] = '好的'; 
		// $shuju[1]['id'] = 2; 
		// $shuju[2]['val'] = '热热'; 
		// var_dump(array_column($shuju,'val'));die;
	
		$info= $this->CepingModel->ceshifind(23);
		// $muban= $this->CepingModel->ceshifind(28);
		// $list = $this->CepingModel->object_to_array($info['content']);
		$list = $this->CepingModel->object_to_array(json_decode($info['content']));
		$line =$list['data']['block'][0]['line'];
		// var_dump('111');die;
		for($i=0;$i<count($line);$i++){
			$shuju[$i] = '';
			for($j=0;$j<count($line[$i]['word']);$j++){
				$zn = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $line[$i]['word'][$j]['content']);
				$en = preg_match("/[a-zA-Z]/u", $line[$i]['word'][$j]['content']);
				if(($zn==0&&$en==1)||$zn==1){
					$shuju[$i] .= ' '.$line[$i]['word'][$j]['content'];
				}
			}
			$shuju[$i] = trim($shuju[$i]);
		}
		$i=0;
		for($j=0;$j<count($shuju);$j++){
			if($j<count($shuju)-1){
				$zn = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $shuju[$j]);
				$en = preg_match("/[a-zA-Z]/u", $shuju[$j]);
				$znxt = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $shuju[$j+1]);
				$enxt = preg_match("/[a-zA-Z]/u", $shuju[$j+1]);
				if($zn==1&&$enxt==1){
					$moxie[$i]['que'] =  $shuju[$j];
					$moxie[$i]['anw'] =  $shuju[$j+1];
					$i++;
				}
			}
		}
		
		// $muban = $this->CepingModel->object_to_array(json_decode($muban['content']));

		// for($i=0;$i<count($muban);$i++){
			// for($j=0;$j<count($moxie);$j++){
				// if($moxie[$j]['que']==$muban[$i]['que']){
					// $muban[$i]['rep'] = $moxie[$j]['anw'];
				// }
			// }
		// }
		// var_dump($muban);die;
		// $jsmoxie = json_encode($moxie);
		// $this->CepingModel->ceshiinserf($jsmoxie);
		var_dump($jsmoxie);var_dump($shuju);var_dump($moxie);die;
		$message ['code'] = 1;
		$message ['info'] = $info;
		echo json_encode ( $message );
		exit ();

	}
	
	
	public function diandao(){
		// var_dump($_GET);die;
		$data=$_GET['data'];
		$str_str_new=strrev($data);
		echo $str_str_new;
	}
	//测试
	public function yuyinceshi(){

		$info= $this->CepingModel->yuyinceshi();
		$message ['code'] = 1;
		$message ['info'] = $info;
		echo json_encode ( $message );
		exit ();

	}
	
	//获取单词百度翻译
	public function word_reading(){
		
		
		$word = "hello";
		$url = "https://fanyi.baidu.com/gettts?lan=en&text=".$word."&spd=3&source=web";
		$path ="./upload/word/";
		$arr = parse_url($url);
		// var_dump($arr);die;
		$fileName=$word;
		// $fileName='tts';
		$file=file_get_contents($url);
		// var_dump($arr);var_dump($path.$fileName.'.mp3');var_dump('11');die;
		$ok = file_put_contents($path.$fileName.'.mp3',$file);
		var_dump($url);var_dump($path);var_dump($arr);var_dump($fileName);var_dump($ok);die;
		
		$sid = $this->request->post('sid');
		// $sid = 37;
		$word= $this->CepingModel->word_by_sid($sid);
		$word['url'] = $this->CepingModel->read_word($word['word']);
		// $info= $this->TransapiModel->translate($word['word']);
		// var_dump($info);die;
		$message ['code'] = 1;
		$message ['data'] = $word;
		echo json_encode ( $message );
		exit ();
	}
	
	
	
	public function ceshi(){
		$URL = "/upload/luyin/20181227/21545888751.pcm";
		$info = cmf_get_image_url($URL);
		// $list= Db::name('word')->select();
		// foreach($list as $vo){
			// $word[] = $vo['id'];
		// }
		// $word = json_encode($word);
		// var_dump($list);die;
		// $this->ceshiinserf($word);
		var_dump($info);die;
		var_dump('111');var_dump($_POST);die;
		// $word[0]['word'] = 'how';
		// $word[0]['type'] = 1;
		// $word[1]['word'] = 'are';
		// $word[1]['type'] = 2;
		// $word[2]['word'] = 'you';
		// $word[2]['type'] = 3;
		// $message['word'] =$word;
		// echo json_encode ( $message );
		// exit ();
		$message['mobile1'] = $_POST['mobile'];
		// $message['mobile2'] = $_REQUEST['mobile'];
		$message['haunhang'] = '=>\n';
		file_put_contents("log.txt", $message, FILE_APPEND);
		echo json_encode ( $message );
		exit ();
		var_dump($mobile);die;
		
		var_dump('111');
		var_dump($this->UserBaseController->userinfo);die;
		//识别图片
		// $info= $this->CepingModel->ceshifind(44);
		// $list = $this->CepingModel->object_to_array(json_decode($info['content']));
		// $line =$list['data']['block'][0]['line'];
		
		// for($i=0;$i<count($line);$i++){
			// $shuju[$i] = '';
			// for($j=0;$j<count($line[$i]['word']);$j++){
				// $zn = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $line[$i]['word'][$j]['content']);
				// $en = preg_match("/[a-zA-Z]/u", $line[$i]['word'][$j]['content']);
				// if(($zn==0&&$en==1)||$zn==1){
					// $shuju[$i] .= ' '.$line[$i]['word'][$j]['content'];
				// }
			// }
			// $shuju[$i] = trim($shuju[$i]);
		// }
		// $string = implode('',$shuju);
		// $this->CepingModel->ceshiinserf($string);
		var_dump($shuju);die;
		
		
		
		
		
		
		
		
		
		
		
		$info= $this->CepingModel->ceshi();

		
		echo json_encode ( $message );
		exit ();

	}




}
