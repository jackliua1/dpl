<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
class TeacherController extends AdminBaseController{
//老师列表
    public function index(){
        $rolesSrcs = Db::name('teacher')
				->alias('b')
				->field('b.id,ass.kc_name,as.n_name,asr.name,b.l_name')
				->join('ceping_kc ass ','b.kc_id=ass.kc_id ' )
				->join('nianji as ','b.n_id=as.id ' )
				->join('school asr ','b.x_id=asr.id ' )
				->order("id")
				->paginate(10);
		$datas = Db::name('teacher')
				->alias('b')
				->field('b.id,ass.kc_name,as.n_name,asr.name,b.l_name')
				->join('ceping_kc ass ','b.kc_id=ass.kc_id ' )
				->join('nianji as ','b.n_id=as.id ' )
				->join('school asr ','b.x_id=asr.id ' )
				->select();
				
        $page = $rolesSrcs->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrcs);
        if ($this->request->post()) {
            $data = $this->request->param();
            $datas = Db::name('teacher')
                ->alias('b')
                ->field('b.id,ass.kc_name,as.n_name,asr.name,b.l_name')
                ->join('ceping_kc ass ','b.kc_id=ass.kc_id ' )
                ->join('nianji as ','b.n_id=as.id ' )
                ->join('school asr ','b.x_id=asr.id ' )
                ->where($data)
                ->select();
            $this->assign('result', $datas);
            $this->assign('l_name', isset($data['l_name']) ? $data['l_name'] : '');
        }
        return $this->fetch();
    }
    //添加老师
    public function add(){
        $rolesSrc = Db::table('cmf_ceping_kc')->select();
        $this->assign('date',$rolesSrc);
        $rolesSrc = Db::table('cmf_nianji')->select();
        $this->assign('result',$rolesSrc);
        $rolesSrc = Db::table('cmf_school')->select();
        $this->assign('data',$rolesSrc);
        return $this->fetch();
    }
    //添加操作
    public function adds(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('teacher')->insert($data);
            $this->success("添加成功", url("Teacher/index"));
        }
    }
	
	//编辑老师
	public function etid(){
		$id = $this->request->param('id', 0, 'intval');
		$rolesSrcs = Db::name('teacher')
			->alias('b')
			->field('b.*,ass.kc_name,as.n_name,asr.name as s_name,b.l_name')
			->join('ceping_kc ass ','b.kc_id=ass.kc_id ' )
			->join('nianji as ','b.n_id=as.id ' )
			->join('school asr ','b.x_id=asr.id ' )
			->where('b.id',$id)
			->find();
			// var_dump($rolesSrcs);die;
		$this->assign('results',$rolesSrcs);
		$rolesSrc = Db::table('cmf_ceping_kc')->select();
		$this->assign('date',$rolesSrc);
		$rolesSrc = Db::table('cmf_nianji')->select();
		$this->assign('result',$rolesSrc);
		$rolesSrc = Db::table('cmf_school')->select();
		$this->assign('data',$rolesSrc);
		return $this->fetch();
	}
    //编辑修改
    public function update(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('teacher')->update($data);
            $this->success("修改成功", url("Teacher/index"));
        }
    }
    //删除老师
    public function dell(){
		$id = $this->request->param('id', 0, 'intval');
		Db::name('teacher')->delete($id);
		$this->success("删除成功！", url("Teacher/index"));
    }

}