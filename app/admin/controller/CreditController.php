<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\RouteModel;
use cmf\controller\AdminBaseController;
use app\admin\model\CreditModel;
use think\Db;
use app\admin\model\ThemeModel;


class CreditController extends AdminBaseController
{
    public $creditModel;
    public function _initialize()
    {
        $this->creditModel = new CreditModel();
        
    }

    /**
     * 获取积分商品列表
     */
    public function index(){
        $param = $this->request->param();
        if(isset($param['title']))$this->assign('title', $param['title']);
        $goods = $this->creditModel->get_goods($param);
        $this->assign('goods', $goods);
        
        $this->assign('page', $goods->render());
        return $this->fetch();
    }

    /**
     * 获取积分商品添加
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * 获取积分商品添加处理
     */
    public function addGoods(){
        if($this->request->post()){
            $data = $this->request->param();
            if(Db::name('credit_goods')->insert($data)){
                $this->success("添加成功",url("Credit/index"));
            }else{
                $this->error("添加失败");
            }
            
        }
    }

    /**
     * 获取积分商品修改
     */
    public function edit(){
        $id = $this->request->param('id');
        if(!$id){
            $this->error("非法进入");
        }
        $info = $this->creditModel->get_goodinfo($id);
        $this->assign('data', $info);
        return $this->fetch();
    }

    /**
     * 积分商品编辑处理
     */
    public function editGoods(){
        if( $this->request->post()){
            $data = $this->request->param();
            if($this->creditModel->save_goods($data['id'],$data)){
                $this->success("修改成功",url("Credit/index"));
            }else{
                $this->error("修改失败");
            }
        }
    }
    /**
     * 积分商品修改状态
     */
    public function editsGoods(){
        $id = $this->request->param('id');
        $status = $this->request->param('status');
        if(!$id){
            $this->error("非法进入");
        }
        $data['status'] = $status?0:1;
        if($this->creditModel->save_goods($id,$data)){
            $this->success("修改成功",url("Credit/index"));
        }else{
            $this->error("修改失败");
        }
    }


    /**
     * 获取积分商品编辑处理
     */
    public function del(){
        $id = $this->request->param('id');
        if(!$id){
            $this->error("非法进入");
        }
        if($this->creditModel->del_goods($id)){
            $this->success("删除成功",url("Credit/index"));
        }else{
            $this->error("删除失败");
        }
    }

    /**
     * 积分兑换列表
     */
    public function exchange(){
        $param = $this->request->param();
        if(isset($param['uid']))$this->assign('uid', $param['uid']);
        $status = isset($param['status'])?$param['status']:"";
        $this->assign('status', $status);
        $list = $this->creditModel->get_exchange($param);
        $this->assign('list', $list);
        
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    /**
     * 编辑修改积分兑换列表
     */
    public function edit_exchange(){
        $param['id'] = $this->request->param('id');
        $data['status'] = $this->request->param('status');
        if(!$param['id']){
            $this->error("非法进入");
        }
        if($data['status'] == 2){
            $data['send_time'] = date("Y-m-d H:i:s"); //发货时间修改
        }
        if($this->creditModel->save_exchange_goods($param,$data)){
            $this->success("修改成功",url("Credit/exchange"));
        }else{
            $this->error("修改失败");
        }
    }

    
}
