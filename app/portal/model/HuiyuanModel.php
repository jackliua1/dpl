<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:大蛇 < 1924835471@qq.com>
// +----------------------------------------------------------------------
namespace app\portal\model;

use think\Model;
use think\Db;

class HuiyuanModel extends Model
{
    public function user_by_id($id){
        // $info= Db::name('huiyuan')->where('id',$id)->find();
        //chancle 修改如下信息 班级与年级不在挂钩
		$info = Db::name('huiyuan')
				->alias('a')
				->field('a.*,ifnull(b.b_name,"") b_name,ifnull(c.n_name,"") n_name,ifnull(s.name,"") s_name')
				->join('banji b ','a.class_id=b.id ','LEFT' )
				->join('nianji c ','a.grade=c.id ' ,'LEFT')
				->join('school s ','a.s_id=s.id ' ,'LEFT')
				->where('a.id',$id)
				->find();
		
        return $info;
    }
	
	public function user_by_tel($tel){
		
        $info= Db::name('huiyuan')->where(array('tel'=>$tel))->find();
        return $info;
    }
    /**
    * 注册会员信息
    * $data 内容
    */
    public function user_add($data){    	
        $info= Db::name('huiyuan')->insertGetId($data);
        return $info;
    }
    /**
    * 修改会员信息
    * $param 修改条件
    * $data 修改的内容
    */
    public function user_save($param,$data){    	
        $info= Db::name('huiyuan')->where($param)->update($data);
        return $info;
    }
	

	/**
    * 通过token获取会员信息
    * $token 登陆时产生的token
    */
    public function user_by_token($token){
    	//获取token对应的uid
        $info = Db::name('huiyuan_token')
				->alias('a')
				->field('b.*,a.token,ifnull(n.n_name,"") n_name,ifnull(bj.b_name,"") b_name,ifnull(s.name,"") s_name')
				->join('huiyuan b ','a.uid=b.id ','LEFT' )
				->join('nianji n ','b.grade=n.id ' ,'LEFT')
				->join('banji bj ','b.class_id=bj.id ','LEFT' )
				->join('school s ','b.s_id=s.id ' ,'LEFT')
				->where('a.token',$token)
				->find();
        return $info;
    }

    /**
    * 通过token获取会员信息
    * $token 登陆时产生的token
    */
    public function user_logout($token){
    	$data['token'] = '';//清空token
        $data['is_login'] = 0; //登出变成0
        $info = Db::name('huiyuan_token')->where('token',$token)->update($data);
        return $info;
    }

    /**
    * 会员使用记录信息
    * $uid 用户id
    * $data 需要更改的数据
    */
    public function user_use_log($uid,$data=array()){
    	$param['uid'] = $uid;
    	$param['logdate'] = date('Y-m-d');//获取当天的日期
		
		//累加记录
		$ceshiparam['uid'] = $uid;
    	$ceshiparam['logdate'] = date('Y-m-d');//获取当天的日期
		$ceshiparam['begintime'] = date("Y-m-d H:i:s");
		if($data){
			$ceshiparam['usetime'] = $data['usetime'];
			Db::name('huiyuan_use_logjilu')->insert($ceshiparam);
		}
		
		
    	//查询当天是否已打卡
    	$daka = Db::name('huiyuan_use_log')->where($param)->find();
    	if($daka){
    		//当天已产生登录记录直接更新记录
    		if($data){
    			if(isset($data['usetime'])){
		    		//如果存在这个字段多累加处理
		    		$usetime = Db::name('huiyuan_use_log')->where($param)->value('usetime');
		    		$data['usetime'] += $usetime;
		    	}
		    	if(isset($data['is_daka'])){
		    		//如果是打卡操作
		    		$data['lx_date'] = $daka['lx_date']+1;//连续打卡天数加1
		    	}
    			Db::name('huiyuan_use_log')->where($param)->update($data);
    		}
    	}else{
    		//当天未产生记录创建记录
    		$param['begintime'] = date("Y-m-d H:i:s");
    		//查询用户昨天是否有打卡
    		$where['logdate'] = date("Y-m-d",strtotime("-1 day"));
    		$where['is_daka'] = 1;
    		$where['uid'] = $uid;
    		$info = Db::name('huiyuan_use_log')->where($where)->find();
    		$param['lx_date'] = 0; //连续打卡天数
    		if($info){
    			$param['lx_date'] = $info['lx_date'];
    		}
    		Db::name('huiyuan_use_log')->insert($param);
    	}
    }

    /**
     * 获取当天的使用状态
     * $param 查询条件
     */
    public function get_use_log($param){
    	$info = Db::name('huiyuan_use_log')->where($param)->order('id desc')->find();
    	return $info;
    }


    /**
    * 会员token记录
    * $param 修改条件
    * $data 修改的内容
    */
    public function save_token($param,$data=array()){
    	$where['uid'] = $param['uid'];
    	//先查询有几个设备号
    	$huiyuan_token = Db::name('huiyuan_token')->where($where)->select();
    	$count = count($huiyuan_token);
    	if($count<1){
    		//首次登陆设备
    		$data['is_main'] = 1;
    	}

		//判断此设备是否存在 此处用于控制 只有两个设备能登陆
		$token_info = Db::name('huiyuan_token')->where($param)->find();
		
		//大于两个不添加 huifu
    	// if($count>=2 && !$token_info){
    		// return -1;
    	// }
    	
    	if($token_info){
    		$info = Db::name('huiyuan_token')->where($param)->update($data);
    	}else{
    		$info = Db::name('huiyuan_token')->insert($data);
    	}
    	return $info;
    }

    /**
    * 获取排行榜
    * $param 修改条件
    */
    public function get_uselog_phb($param){
    	$where = "1=1 ";
    	if(isset($param['start_date'])){
			$where .= " and logdate >= '".$param['start_date']."'";
    	}
    	if(isset($param['end_date'])){
			$where .= " and logdate <= '".$param['end_date']."'";
    	}

    	$list = Db::name('huiyuan_use_log')
    			->alias('ul')
    			->field("b.username,b.integral,b.avatar,sum(ul.usetime) as usetime_all")
    			->where($where)
    			->join('huiyuan b','ul.uid=b.id ','LEFT')
    			->group("ul.uid")
    			->order("usetime_all DESC")
    			->select()->toArray();
    	return $list;
    }
	public function jia_get_uselog_phb($param){
    	$where = "1=1 ";
  

    	$list = Db::name('huiyuan_use_log')
    			->alias('ul')
    			->field("b.username,b.integral,b.avatar,sum(ul.usetime) as usetime_all")
    			->where($where)
    			->join('huiyuan b','ul.uid=b.id ','LEFT')
    			->group("ul.uid")
    			->order("usetime_all DESC")
    			->select()->toArray();
    	return $list;
    }

    /**
    * 获取自己的名次
    * $param 修改条件
    * $uid 用户id
    */
    public function get_uselog_my($uid,$param){
    	$where = "1=1 ";
    	if(isset($param['start_date'])){
			$where .= " and logdate >= '".$param['start_date']."'";
    	}
    	if(isset($param['end_date'])){
			$where .= " and logdate <= '".$param['end_date']."'";
    	}

    	//获取自己的学习总时长
    	$my_usetime = Db::name('huiyuan_use_log')->where('uid',$uid)->where($where)->sum('usetime');
    	//获取比自己学习时长多的人
    	$sql = "select count(*) as count_all from (select uid,sum(usetime) as usetime_all from cmf_huiyuan_use_log where ".$where." group by uid) as a where a.usetime_all >$my_usetime";
    	$count = Db::query($sql);
    	return $count[0]['count_all']+1;
    }
    
	
	
}