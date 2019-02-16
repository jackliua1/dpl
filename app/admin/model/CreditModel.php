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
namespace app\admin\model;

use app\admin\model\RouteModel;
use think\Model;
use think\Db;

class CreditModel extends Model
{
    /**
     * 获取积分商品列表
     * $param查询条件
     * $size 显示个数
     * $order 排序
     */
    public function get_goods($param=array(),$size=10,$order='id desc'){
        $where = array();
        if(isset($param['title']) && $param['title']){
            $where['title'] = array("like","%".$param['title']."%");
        }
        if(isset($param['status']) && $param['status']){
            $where['status'] = intval($param['status']);
        }
        $list= Db::name('credit_goods')->where($where)->order($order)->paginate($size,false,['query' => $param]);
        return $list;
    }


    /**
     * 获取单个积分商品信息
     * $id 积分商品的id
     */
    public function get_goodinfo($id){
        $info= Db::name('credit_goods')->where(array('id'=>$id))->find();
        return $info;
    }

    /**
     * 获取更新积分商品信息
     * $id 积分商品的id
     * $data 更新的数据
     */
    public function save_goods($id,$data){
        $res= Db::name('credit_goods')->where(array('id'=>$id))->update($data);
        return $res;
    }

    /**
     * 删除积分商品
     * $id 积分商品的id
     */
    public function del_goods($id){
        $res= Db::name('credit_goods')->where(array('id'=>$id))->delete();
        return $res;
    }

    /**
     * 获取积分兑换列表
     * $param查询条件
     * $size 显示个数
     * $order 排序
     */
    public function get_exchange($param=array(),$size=10,$order='ce.id desc'){
        $where = array();
        if(isset($param['uid']) && $param['uid']){
            $where['ce.uid'] = intval($param['uid']);
        }
        if(isset($param['status']) && $param['status']){
            $where['ce.status'] = intval($param['status']);
        }
        $list= Db::name('credit_exchange')
            ->alias('ce')
            ->field('ce.*,ifnull(cg.title,"") title,ifnull(hy.username,"") username,cg.imgurl,cg.info')
            ->join('credit_goods cg ','ce.cg_id=cg.id ','LEFT' )
            ->join('huiyuan hy ','ce.uid=hy.id ','LEFT' )
            ->where($where)->order($order)->paginate($size,false,['query' => $param]);
			
		
        return $list;
    }

    /**
     * 更新积分兑换的商品
     * $param查询条件
     * $data 需要保存的内容
     */
    public function save_exchange_goods($param,$data){
        $res= Db::name('credit_exchange')->where($param)->update($data);
        return $res;
    }

    /**
     * 处理积分兑换商品
     */
    public function exchange_goods($data){
        $res= Db::name('credit_exchange')->insertGetId($data);
        return $res;
    }


    /**
     * 创建积分日志
     * $data 需要保存的内容
     */
    public function set_log($data){
        $res= Db::name('credit_log')->insert($data);
        //对会员表进行变更
        $where['id'] = intval($data['uid']);
        $credit = intval($data['credit']);
        if($data['type']==1){
            //增加积分
            Db::name('huiyuan')->where($where)->setInc('integral',$credit);
        }else{
            //减少积分
            Db::name('huiyuan')->where($where)->setDec('integral',$credit);
        }
        return $res;
    }
}