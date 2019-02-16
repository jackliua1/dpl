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
use app\admin\model\CreditModel;
use app\admin\model\CepingModel;

class CreditController extends UserBaseController
{
    public $HuiyuanModel;
    public $CreditModel;
    function _initialize() {
        parent::_initialize();
        $this->HuiyuanModel = new HuiyuanModel(); //会员表模型
        $this->CreditModel = new CreditModel(); //积分模型
		$this->CepingModel = new CepingModel();  //srl
    }


    /**
     * 积分商城首页内容
    */
    public function index()
    {
        //获取列表信息
        $param['status'] = 1;//前台只展示显示的内容
        $goods = $this->CreditModel->get_goods($param);
        $arr = array();
        if($goods){
             foreach ($goods as $k => $v) {
                $arr[$k] = $v;
                $arr[$k]['imgurl'] = cmf_get_image_preview_url($v['imgurl']);
            }   
        }
        echo return_json($arr);exit ();
    }

    /**
     * 积分商城商品详情
    */
    public function detail()
    {
        //获取列表信息
        $id = $this->request->param('id');
        if(!$id){
            echo return_json(0,-2,'参数异常');exit ();
        }
        $info = $this->CreditModel->get_goodinfo($id);
        if(($info && $info['status']==0) || !$info){
            echo return_json(0,-3,'数据不存在');exit ();
        }
        if($info){
            $info['imgurl'] = cmf_get_image_preview_url($info['imgurl']);
            $info['info'] = htmlspecialchars_decode($info['info']);
        }
        
        echo return_json($info);exit ();
    }

    /**
     * 积分兑换商品
    */
    public function exchange()
    {
        //获取列表信息
        $id = $this->request->param('id');
        $num = $this->request->param('num',1,'intval');
        if(!$id){
            echo return_json(0,-2,'参数异常');exit ();
        }
        //查询该商品兑换需要多少积分
        $info = $this->CreditModel->get_goodinfo($id);
        //先判断数量
        if(($info['num']-$num) < 0){
            echo return_json(0,-1005,'商品数量不足');exit ();
        }
        $usecredit = intval($info['credit'])*$num; //需要消耗的积分
        //用户拥有的积分
        $hascredit = $this->userinfo['integral'];
        if($usecredit > $hascredit){
            echo return_json(0,-1006,'积分不足');exit ();
        }

        //处理商品兑换
        $datalog['uid'] = $data['uid'] = $this->userinfo['id'];   //用户id
        $data['cg_id'] = $id;  //积分商品id
        $data['num'] = $num;  //购买的数量
        $datalog['credit'] = $data['usecredit'] = $usecredit;//需要消耗的积分
        $datalog['create_time'] = $data['create_time'] = date("Y-m-d H:i:s"); //创建的时间
        $datalog['third_id'] = $this->CreditModel->exchange_goods($data);

        $datalog['type'] = 2;//购买消耗积分
        $datalog['reason'] = "兑换商品<".$info['title'].">消耗积分";//购买消耗积分
        $datalog['reason_type'] = 1; //1：积分商城兑换
        //处理积分
        $res = $this->CreditModel->set_log($datalog);
		
		//srl 更新商品数量
		$sgdata['num'] = $info['num']-$num;
		$sgdata['buy_num'] = $info['buy_num']+$num;
		$savegoods = $this->CreditModel->save_goods($id,$sgdata);
		

        echo return_json(0,1,"兑换成功");exit ();
    }

    /**
     * 积分兑换订单列表
     */
    public function exchange_order(){
        $param = $this->request->param();
        $param['uid'] = $this->userinfo['id'];
        $list = $this->CreditModel->get_exchange($param);
		
		$listg = json_decode(json_encode($list));
		$listg = $this->CepingModel->object_to_array($listg);
		
		$arr = array();
		foreach ($listg['data'] as $k => $v) {
                $arr[$k] = $v;
                $arr[$k]['imgurl'] = cmf_get_image_preview_url($v['imgurl']);
            }  
			$listg['data']=$arr;
			// var_dump($arr);die;
		// var_dump($listg->data);var_dump($arr);die;
        echo return_json($listg);exit ();
    }


}
