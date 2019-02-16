<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use app\user\model\UserModel;
use cmf\controller\HomeBaseController;
use app\admin\model\CepingModel;
use app\portal\model\HuiyuanModel;

class IndexController extends HomeBaseController
{
    function _initialize() {
        $this->CepingModel = new CepingModel();
        $this->HuiyuanModel = new HuiyuanModel();
    }
    /**
     * 前台用户首页(公开)
     */
    public function index()
    {
        $id        = $this->request->param("id", 0, "intval");
        $userModel = new UserModel();
        $user      = $userModel->where('id', $id)->find();
        if (empty($user)) {
            $this->error("查无此人！");
        }
        $this->assign($user->toArray());
        $this->assign('user',$user);
        return $this->fetch(":index");
    }

    /**
     * 前台ajax 判断用户登录状态接口
     */
    function isLogin()
    {
        if (cmf_is_user_login()) {
            $this->success("用户已登录",null,['user'=>cmf_get_current_user()]);
        } else {
            $this->error("此用户未登录!");
        }
    }

    /**
     * 退出登录
    */
    public function logout_old()
    {
        session("user", null);//只有前台用户退出
        return redirect($this->request->root() . "/");
    }

    /**
     * 用户登录
    */
    public function login()
    {
        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        $sys = $this->request->post('sys'); //系统
        $model = $this->request->post('model'); //设备型号
        $model_id = $this->request->post('model_id'); //设备号唯一标识
		
		//测试账号
		if ($mobile!='15852765528'||$mobile=='15189386706'||$mobile=='15706182060'){
			 $verify = 1;  //暂时不验证
		}else{
			 if(!$mobile || !$code || !$sys || !$model_id){
					echo return_json(0,-2,'参数异常');exit ();
				}
				$verify= $this->CepingModel->sendVerify($mobile,$code);
		}
        // if(!$mobile || !$code || !$sys || !$model_id){
            // echo return_json(0,-2,'参数异常');exit ();
        // }
        // $verify= $this->CepingModel->sendVerify($mobile,$code);
		

        // $verify = 1;  //暂时不验证
        if($verify==1){
            $huiyuan = $this->HuiyuanModel->user_by_tel($mobile);
            $msg = '登录成功';
            $code = 1;
            if(!$huiyuan){
                //用户不存在进行注册操作
                $huiyuan['tel'] = $mobile;
                $huiyuan['id'] = $uid = $this->HuiyuanModel->user_add($huiyuan);
                //重新获取下用户信息
                $huiyuan = $this->HuiyuanModel->user_by_tel($mobile);
                $msg = '用户不存在,已自动注册';
                $code = 1002;
            }

            //记录登录操作
            $thistime = time();
            $token = $huiyuan['id'].$thistime;//唯一设备登录的token值
            $data['uid'] = $huiyuan['id']; //会员id
            $data['create_time'] = $thistime; //登录创建时间
            $data['expire_time'] = $thistime+365*2*24*3600; //失效时间 暂时没用
            $data['sys'] = $sys;
            $data['model'] = $model?$model:"";
            $data['model_id'] = $model_id;
            
            $data['token'] = md5($token); //之后所有信息通过token来获取用户信息
            $data['is_login'] = 1; //1表示已登录
            $tokeninfo = $this->HuiyuanModel->save_token(array('uid'=>$huiyuan['id'],'model_id'=>$model_id),$data);
            // if($tokeninfo == -1){
				if($tokeninfo == -1 && $huiyuan['id']!= 61&&  ($mobile!='15852765528'||$mobile=='15189386706'||$mobile=='15706182060')){
					echo return_json(0,1003,'此账号绑定设备数已满');exit ();
				}

            //保存更新用户使用记录
            $this->HuiyuanModel->user_use_log($huiyuan['id']);

            //原代码太多 uid问题
            $huiyuan['uid'] = $huiyuan['id'];
            $huiyuan['token'] = $data['token'];
            if($code == 1){
                $huiyuan['avatar'] = cmf_get_user_avatar_url($huiyuan['avatar']);//会员头像处理下
            }
            echo return_json($huiyuan,$code,$msg);exit ();

            
        }else{
            echo return_json(0,1001,'验证码错误');exit ();
        }
    }

    /**
     * chancle 退出登录
    */
    public function logout()
    {
        $token = $this->request->param("token");
        $this->HuiyuanModel->user_logout($token);
        echo return_json(0,1,'退出成功');exit ();
    }


}
