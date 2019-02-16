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

use cmf\controller\HomeBaseController;
use alisms\SendSms;
use app\admin\model\CepingModel;

class IndexController extends HomeBaseController
{
    public function index()
    {
        return $this->fetch(':index');
    }
	
	public function updown()
    {
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        


        
        if($is_iphone||$is_ipad){
            header('Location:https://dibaqu.com/f2ya');
        }elseif($is_android){
            header('Location:https://dibaqu.com/m2Yz');
        }else{
			return $this->fetch(":updown/index");
		}
		
        
    }
	public function product_concept()
    {
        return $this->fetch(":updown/product_concept");
    }
	public function about()
    {
        return $this->fetch(":updown/about");
    }
	
	public function agreement()
    {
        return $this->fetch(":agreement");
    }
	//下载二维码
	public function updownewm()
    {
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
			echo 'IOS 端尽请期待';
		}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
			$url = cmf_get_image_url("admin/DPLupdown.apk");
			
			
			header('Location:'.$url);
		}else{
			header('Location:'.$url);
		}
        
    }
	
	//发送短信
	public function sendsms(){
		$this->CepingModel = new CepingModel();
		$mobile = $this->request->post('mobile');
		// $mobile = $_POST['mobile'];
		// var_dump($mobile );die;
		
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
		
		// var_dump($info);die;
		if($info['Code']=='OK'){
			$message ['code'] = 1;
			$message ['msg'] = '发送成功';
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
	
	function downFile($url,$path){
		
		$arr = parse_url($url);
		$fileName=basename($arr['path']);
		// $fileName='tts';
		$file=file_get_contents($url);
		$ok = file_put_contents($path.$fileName,$file);
		var_dump($url);var_dump($path);var_dump($arr);var_dump($fileName);var_dump($ok);die;
	//     $arr = parse_url($url);
	//     $fileName=basename($arr['path']);
	//     $file=file_get_contents($url);
	//     file_put_contents($path.$fileName,$file);
	}
}
