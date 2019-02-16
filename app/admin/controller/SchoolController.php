<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
class SchoolController extends AdminBaseController{
//学校列表
    public function index(){
        $rolesSrcs = Db::name('school')
            ->alias('b')
            ->join('region ass ','b.province_id=ass.region_id ' )
            ->order("id")
            ->paginate(20);
        $page = $rolesSrcs->render();;

        $this->assign('page',$page);

        $this->assign('result',$rolesSrcs);
        if ($this->request->post()) {
            $data = $this->request->param();
            $datas =Db::name('school')
                ->alias('b')
                ->join('region ass ','b.province_id=ass.region_id ' )
                ->where($data)
                ->select();
            $this->assign('result', $datas);
            $this->assign('name', isset($data['name']) ? $data['name'] : '');
        }
        return $this->fetch();
    }

//添加学校
public function add(){
    $list=Db::name('region')->where('parent_id',0)->select();
    $this->assign('list',$list);
    return $this->fetch();
}
//ajax传输数据
    public function  ajaxArea()
    {
        $pid=$this->request->get('pid');
        $list=Db::name('region')->where('parent_id',$pid)->select();
        echo json_encode($list);
    }
//添加学校数据得到数据库
    public function addPost(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('school')->insert($data);

            $this->success("添加成功", url("school/index"));
        }
    }
    //编辑数据查询
        public function etid(){
            $list=Db::name('region')->where('parent_id',0)->select();
            $this->assign('list',$list);
            $id = $this->request->param('id', 0, 'intval');
            $data=  Db::name('school')->where('id',$id)->find();
            $this->assign('data',$data);
                return $this->fetch();
        }
        //修改数据
        public function add_etid()
        {
            if ($this->request->post()) {
                $datas = $this->request->param();
                Db::name('school')->update($datas);
                $this->success("编辑成功", url("school/index"));

            }
        }
        public function dell(){

            $id = $this->request->param('id', 0, 'intval');
            Db::name('school')->delete($id);
            $this->success("删除成功！", url("school/index"));
        }


        //班级列表
    public function b_index(){
        $rolesSrcs = Db::name('banji')
            ->alias('b')
            ->field('b.id,b.b_name,ass.name,as.n_name')
            ->join('school ass ','b.b_id=ass.id ' )
            ->join('nianji as ','b.n_id=as.id ' )
            ->order("id")
            ->paginate(20);
        $page = $rolesSrcs->render();;

        $this->assign('page',$page);
        $this->assign('result',$rolesSrcs);
        if ($this->request->post()) {
            $data = $this->request->param();
            $datas =Db::name('banji')
                ->alias('b')
                ->field('b.id,b.b_name,ass.name,as.n_name')
                ->join('school ass ','b.b_id=ass.id ' )
                ->join('nianji as ','b.n_id=as.id ' )
                ->where($data)
                ->select();
            $this->assign('result', $datas);
            $this->assign('b_name', isset($data['b_name']) ? $data['b_name'] : '');
        }

        return $this->fetch();
    }
//查询学校
    public function b_add(){
        $rolesSrcs = Db::name('school')->select();
        $this->assign('result',$rolesSrcs);
        $data = Db::name('nianji')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //添加班级
    public function add_post(){
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('banji')->insert($data);

            $this->success("添加成功", url("school/b_index"));
        }
    }
    //编辑班级
    public function b_etid(){
        $datas = Db::name('nianji')->select();
        $this->assign('datas',$datas);
        $list=Db::name('school')->select();
        $this->assign('list',$list);
        $id = $this->request->param('id', 0, 'intval');
        $datas =Db::name('banji')
            ->alias('b')
            ->where('b.id',$id)
            ->field('b.id,b.b_name,ass.name,as.n_name')
            ->join('school ass ','b.b_id=ass.id ' )
            ->join('nianji as ','b.n_id=as.id ' )
            ->find();
        $this->assign('data',$datas);
        return $this->fetch();
    }
    //修改班级
    public function adds_etid(){
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('banji')->update($datas);
            $this->success("编辑成功", url("school/b_index"));

        }
    }

    //删除班级
    public function b_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('banji')->delete($id);
        $this->success("删除成功！", url("school/b_index"));

    }
    //年级列表
    public function n_index(){
      $data=  Db::name('nianji') ->order("id")
          ->paginate(20);
        $page = $data->render();;

        $this->assign('page',$page);
        $this->assign('data',$data);
        if ($this->request->post()) {
            $data = $this->request->param();
            $datas = Db::name('nianji')->where($data)->select();
            $this->assign('data', $datas);
            $this->assign('n_name', isset($data['n_name']) ? $data['n_name'] : '');
        }
        return $this->fetch();
    }
    //年级页面
        public function n_add(){
            if( $this->request->post()) {
                $data = $this->request->param();
                Db::name('nianji')->insert($data);

                $this->success("添加成功", url("school/n_index"));
            }
            return $this->fetch();
        }
        //编辑年级
        public function n_etid(){
            $id = $this->request->param('id', 0, 'intval');
            $data=  Db::name('nianji')->where('id',$id)->find();
            $this->assign('data',$data);
            return $this->fetch();
        }
        public function n_post(){
            if ($this->request->post()) {
                $datas = $this->request->param();
                Db::name('nianji')->update($datas);
                $this->success("编辑成功", url("school/n_index"));

            }
        }
        //删除年级
    public function n_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('nianji')->delete($id);
        $this->success("删除成功！", url("school/n_index"));
    }
}