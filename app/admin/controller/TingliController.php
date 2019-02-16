<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
class TingliController extends AdminBaseController{

    //分类列表
    public function index(){
        $data=Db::name('ceping_kc')->select();
        $this->assign('data',$data);
        return $this->fetch();

    }
    //添加页面
    public function add(){
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('ceping_kc')->insert($data);

            $this->success("添加成功", url("ceping/index"));
        }
        return $this->fetch();
    }
	public function edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('ceping_kc')->where('kc_id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }
	public function delete(){
		$id = $this->request->param('id', 0, 'intval');
		Db::name('ceping_kc')->delete($id);
		$this->success("删除成功！", url("Ceping/index"));
	}
	
	//分类列表
    public function q1_index(){
		$type=$this->request->param('type', 0, 'intval');
		// var_dump($type);die;
        $data=Db::name('hearingone1')->where(array('type'=>$type))->select();
		// var_dump($data);die;
        $this->assign('data',$data);
        return $this->fetch();

    }
    //添加页面
    public function q1_add(){
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('ceping_kc')->insert($data);

            $this->success("添加成功", url("ceping/index"));
        }
        return $this->fetch();
    }
	public function q1_delete(){
		$id = $this->request->param('id', 0, 'intval');
		Db::name('ceping_kc')->delete($id);
		$this->success("删除成功！", url("Ceping/index"));
	}
	//分类列表
    public function q2_index(){
		$q1_id=$this->request->param('q1_id', 0, 'intval');
		// var_dump($type);die;
        $data=Db::name('hearingone2')->where(array('q1_id'=>$q1_id))->select();
		// var_dump($data);die;
        $this->assign('data',$data);
        return $this->fetch();

    }
    //添加页面
    public function q2_add(){
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('ceping_kc')->insert($data);

            $this->success("添加成功", url("ceping/index"));
        }
        return $this->fetch();
    }
	public function q2_delete(){
		$id = $this->request->param('id', 0, 'intval');
		Db::name('ceping_kc')->delete($id);
		$this->success("删除成功！", url("Ceping/index"));
	}

//删除分类
            
            //编辑分类
    
    //修改分类
    public function etid_post(){
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('ceping_kc')->update($datas);
            $this->success("编辑成功",url("Ceping/index"));

        }
    }
    //题目列表
    public function t_index(){
        $data=Db::name('timu')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

//添加题目
    public function t_add(){
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('timu')->insert($data);

            $this->success("添加成功", url("ceping/t_index"));
        }
        return $this->fetch();
    }
    //子题目
    public function ti_index(){
        $data=Db::name('timu_zibiaoti')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //添加子题目
    public function ti_add(){
        $data=Db::name('timu_zibiaoti')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //添加标题
    public function te_add(){
        $data=Db::name('timu')->select();
        $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('timu_zibiaoti')->insert($data);

            $this->success("添加成功", url("ceping/ti_index"));
        }
        return $this->fetch();
    }
}