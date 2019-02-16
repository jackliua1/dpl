<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: chancle < 893296307@qq.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\UserBaseController;
use app\portal\model\HuiyuanModel;
use app\admin\model\CepingModel;

class UserController extends UserBaseController
{
    function _initialize() {
        parent::_initialize();
        $this->HuiyuanModel = new HuiyuanModel();
		$this->CepingModel = new CepingModel();  //srl
    }


    /**
     * 首页内容
    */
    public function index()
    {
		$info['userinfo']=$this->userinfo;
        //获取年级
        $n_name = empty($this->userinfo['n_name'])?"七年级":$this->userinfo['n_name'];
        $num = intval((time() - strtotime("2018-12-5"))/100);//基础数值
        $info['fenlei'] = array(
                0=>array(
                        'type' => 'yuwen',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/CN.png",
                        'imgurl_c'=> "http://dpl.zoyomei.com/static/images/CN1.png",
                        'title'=> "得到语文",
                        'color'=> '#2c9eda',
                        'color_c'=> '#2c9eda'
                    ),
                1=>array(
                        'type' => 'shuxue',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/Ma.png",
                        'imgurl_c'=> "http://dpl.zoyomei.com/static/images/Ma1.png",
                        'title'=> "派一数学",
                        'color'=> '#38b7a9',
                        'color_c'=> '#38b7a9'
                    ),
                2=>array(
                        'type' => 'yingyu',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/EN.png",
                        'imgurl_c'=> "http://dpl.zoyomei.com/static/images/EN1.png",
                        'title'=> "Leo Liu英语",
                        'color'=> '#ec6e5a',
                        'color_c'=> '#ec6e5a'
                    )
            );
			
		$ceshi = $this->request->param('ceshi');
		if(!$ceshi){
			$liebiao= $this->CepingModel->slide_itemlist('英语首页效果');
			foreach($liebiao as $k => $v){
				 $info['liebiao'][$k]['imgurl'] = cmf_get_image_preview_url($v['image']);
				 $info['liebiao'][$k]['title'] = $v['title'];
				 if($v['title']=='江苏中考听力口语模拟训练'){
					 
					 if($this->userinfo['n_name']=='七年级'){
						 $info['liebiao'][$k]['title'] = '七年级听力口语模拟训练';
					 }
					 if($this->userinfo['n_name']=='八年级'){
						 $info['liebiao'][$k]['title'] = '八年级听力口语模拟训练';
					 }
				 }
				 
				 
				 $info['liebiao'][$k]['des'] = $v['description'];
				 $info['liebiao'][$k]['nianji'] = $n_name;
				 $info['liebiao'][$k]['studynum'] = 103+$num+$v['num'];
				 $info['liebiao'][$k]['type'] = $v['slide_id'];
				 $info['liebiao'][$k]['id'] = $v['id'];
				 }
			$liebiao_yw= $this->CepingModel->slide_itemlist('语文首页效果');
			foreach($liebiao_yw as $k => $v){
				 $info['liebiao_yw'][$k]['imgurl'] = cmf_get_image_preview_url($v['image']);
				 $info['liebiao_yw'][$k]['title'] = $v['title'];
				 $info['liebiao_yw'][$k]['des'] = $v['description'];
				 $info['liebiao_yw'][$k]['nianji'] = $n_name;
				 $info['liebiao_yw'][$k]['studynum'] = 20+$num+$v['num'];
				 $info['liebiao_yw'][$k]['type'] = $v['slide_id'];
				 $info['liebiao_yw'][$k]['id'] = $v['id'];
			}
			$liebiao_sx= $this->CepingModel->slide_itemlist('数学首页效果');
			foreach($liebiao_sx as $k => $v){
				 $info['liebiao_sx'][$k]['imgurl'] = cmf_get_image_preview_url($v['image']);
				 $info['liebiao_sx'][$k]['title'] = $v['title'];
				 $info['liebiao_sx'][$k]['des'] = $v['description'];
				 $info['liebiao_sx'][$k]['nianji'] = $n_name;
				 $info['liebiao_sx'][$k]['studynum'] = 88+$num+$v['num'];
				 $info['liebiao_sx'][$k]['type'] = $v['slide_id'];
				 $info['liebiao_sx'][$k]['id'] = $v['id'];
			}
			// var_dump($liebiao);die;
		}else{
		
        //英语
        $info['liebiao'] = array(
				array(
                        'type' => 'live',//直播视频
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "直播视频",
                        'des'=> "幽默风趣直播讲解",
                        'nianji'=> "$n_name",
                        'studynum'=> (53+$num)."人学习"
                    ),
                array(
                        'type' => 'kouyu',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "中考听力口语",
                        'des'=> "标准的英语发音 高清的音质效果",
                        'nianji'=> "$n_name",
                        'studynum'=> (103+$num)."人学习"
                    ),
                array(
                        'type' => 'moxie',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/composition.png",
                        'title'=> "单词默写",
                        'des'=> "轻松应对一切情景对话",
                        'nianji'=> "$n_name",
                        'studynum'=> (20+$num)."人学习"
                    ),
                array(
                        'type' => 'xiezuo',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/write.png",
                        'title'=> "英文写作辅助",
                        'des'=> "专业老师按句点评一目了然",
                        'nianji'=> "$n_name",
                        'studynum'=> (45+$num)."人学习"
                    ),
                array(
                        'type' => 'shangxi',
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/read.png",
                        'title'=> "名片赏析",
                        'des'=> "每日秦松学习趣味英语",
                        'nianji'=> "$n_name",
                        'studynum'=> (88+$num)."人学习"
                    )
            );
        //语文
        $info['liebiao_yw'] = array(
				array(
                        'type' => 'live',//直播视频
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "直播视频",
                        'des'=> "幽默风趣直播讲解",
                        'nianji'=> "$n_name",
                        'studynum'=> (53+$num)."人学习"
                    ),
                array(
                        'type' => 'mwsx',//美文赏析
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "美文赏析",
                        'des'=> "标准的语文发音 高清的音质效果",
                        'nianji'=> "$n_name",
                        'studynum'=> (103+$num)."人学习"
                    ),
                array(
                        'type' => 'gscpj',//古诗词评鉴
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/composition.png",
                        'title'=> "古诗词评鉴",
                        'des'=> "轻松应对一切情景对话",
                        'nianji'=> "$n_name",
                        'studynum'=> (20+$num)."人学习"
                    ),
                array(
                        'type' => 'qhyd',//强化阅读
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/write.png",
                        'title'=> "强化阅读",
                        'des'=> "专业老师按句点评一目了然",
                        'nianji'=> "$n_name",
                        'studynum'=> (45+$num)."人学习"
                    ),
                array(
                        'type' => 'xzts',//写作提升
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/read.png",
                        'title'=> "写作提升",
                        'des'=> "每日秦松学习趣味英语",
                        'nianji'=> "$n_name",
                        'studynum'=> (88+$num)."人学习"
                    )
            );
        //数学
        $info['liebiao_sx'] = array(
				array(
                        'type' => 'live',//直播视频
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "直播视频",
                        'des'=> "幽默风趣直播讲解",
                        'nianji'=> "$n_name",
                        'studynum'=> (53+$num)."人学习"
                    ),
                array(
                        'type' => 'ndjx',//难点解析
                        'imgurl'=> "http://dpl.zoyomei.com/static/images/listen.png",
                        'title'=> "难点解析",
                        'des'=> "标准的英语发音 高清的音质效果",
                        'nianji'=> "$n_name",
                        'studynum'=> (103+$num)."人学习"
                    )
            );
			
		}

        echo return_json($info);exit ();
    }

    /**
     * 首页打卡接口
    */
    public function index_dk()
    {
        //获取连续打卡天数
        $dk_info = $this->HuiyuanModel->get_use_log(array('uid'=>$this->userinfo['id']));
		// var_dump($this->userinfo);var_dump($dk_info);die;
        $info =array(
                "studytime" =>$dk_info['usetime'],//学习时间 单位s
                "dk_mtime" =>1200,//打卡需要的最少时间
                "score" =>80,//测评分数
                "type" =>1, //代表与上次测评分数相比 0无变化 1增加 2减少
                "pscore" =>1,//与上次的相差分数
                "dk_date" =>$dk_info['lx_date'],//连续打卡天数
                "is_daka" =>$dk_info['is_daka'],//是否已打卡 0:未打卡 1:已打卡
            );
        echo return_json($info);exit ();
    }

    public function get_userinfo()
    {
        $userinfo = $this->HuiyuanModel->user_by_id($this->userinfo['id']);
        $userinfo['avatar'] = cmf_get_user_avatar_url($userinfo['avatar']); 
        $userinfo['token'] = $this->request->param('token');     
		if(empty($userinfo['username'])&&empty($userinfo['birthday'])&&empty($userinfo['grade'])&&empty($userinfo['avatar'])&&empty($userinfo['address'])){
			$userinfo['verification'] = 0;
		}else{
			$userinfo['verification'] = 1;
		}

		
        echo return_json($userinfo);exit ();
    }
    

    /**
     * chancle 用户编辑
    */
    public function edituser()
    {
        $data = array();//需要更新的内容
        //循环处理过滤为空的值
        foreach ($_POST as $k => $v) {
            if($v){
                $data[$k] = $v;
            }
        }
        if(isset($data['token'])){
            unset($data['token']);
        }
        $param['id'] = $this->userinfo['id'];
        $this->HuiyuanModel->user_save($param,$data);
        
        echo return_json(0,1,'修改成功');exit ();
    }

    /**
     *  打卡接口
     */
    public function daka()
    {
        $data['is_daka'] = 1;
        $data['daka_time'] = date("Y-m-d H:i:s");
        $data['usetime'] = $this->request->param("usetime", 0, "intval");//本次更新使用时长
		// var_dump($this->userinfo);die;
		
		//获取连续打卡天数
        $dk_info = $this->HuiyuanModel->get_use_log(array('uid'=>$this->userinfo['id']));
		if(empty($dk_info['daka_time'])){
			$this->HuiyuanModel->user_use_log($this->userinfo['id'],$data);
			echo return_json(0,1,'打卡成功');exit ();
		}else{
			echo return_json(0,1,'今日已打卡');exit ();
		}
        
    }

    /**
     * 更新学习时长
     */
    public function setusetime()
    {
        $data['usetime'] = $this->request->param("usetime", 0, "intval");//本次更新使用时长
		
		// if($this->userinfo['id'] ==24 ){
			// $data2 = array('content'=> $data['usetime']);
			// $url = Db::name('ceshi')->insert($data2);
			// var_dump($data2);die;
		// }
		
        $this->HuiyuanModel->user_use_log($this->userinfo['id'],$data);
        
        echo return_json(0,1,'更新成功');exit ();
    }

    /**
     *  获取排行榜
     */
    public function phb()
    {
        $type = $this->request->param("type", 1, "intval");
        if($type==1){
            //按日排行
            $param['end_date'] = $param['start_date'] = date("Y-m-d");
        }else{
            //按周排行
            $param['start_date'] = date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600));
            $param['end_date'] = date("Y-m-d");
        }

        $data['list'] = $this->HuiyuanModel->get_uselog_phb($param);
		if(count($data['list'])<100){
			$data['list'] = $this->HuiyuanModel->jia_get_uselog_phb($param);
		}
		
        if($data['list']){
            foreach ($data['list'] as $k=>&$v) {
                if($v['avatar']){
                    $data['list'][$k]['avatar'] = cmf_get_user_avatar_url($v['avatar']);
                }else{
					$data['list'][$k]['avatar'] = "http://dpl.zoyomei.com/static/images/avatar.png";
				}
				if(empty($v['username'])){
                    $data['list'][$k]['username'] = substr( time(), 0, -4 );
				}
            }
        }
        //获取我的排行
        $data['count'] = $this->HuiyuanModel->get_uselog_my($this->userinfo['id'],$param);
        echo return_json($data);exit ();
    }


    /**
     * 上传用户头像
     */
    public function upload_avatar(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('avatar');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $filepath = '.' . DS . 'upload' . DS . 'avatar' . DS;
        if($file){
            $info = $file->move($filepath);
            if($info){
                // 成功上传后 获取上传信息
                $avatarSaveName = str_replace('//', '/', str_replace('\\', '/', $info->getSaveName()));
                $avatar         = 'avatar/' . $avatarSaveName;

                //将路径保存进数据库
                $param['id'] = $this->userinfo['id'];
                $data['avatar'] = $avatar;
                $this->HuiyuanModel->user_save($param,$data);

                //返回的完整路径
                //$fileurl = "http://".$_SERVER['HTTP_HOST']."/upload/".$avatar;
                echo return_json(cmf_get_user_avatar_url($avatar),1,'上传成功');exit ();
            }else{
                // 上传失败获取错误信息
                echo return_json($file->getError(),1004,'图片上传失败');exit ();
            }
        }else{
            echo return_json(0,-2,'参数异常');exit ();
        }
    }

}
