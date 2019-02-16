<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
use think\finfo_open;
class SuoxueController extends AdminBaseController{
//写作列表
    public function index(){
        $rolesSrc = Db::name('souxue')
            ->alias('b')
            ->join('nianji as ','b.grade=as.id' )
            ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
            ->paginate(20);

        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();

    }
    //查询
    public function indexs(){
        if( $this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');

            $datass =Db::name('souxue')
                ->alias('b')
                ->join('nianji as ','b.grade=as.id' )
                ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
                ->where($tj)
                ->select();

            $datas =Db::name('souxue')
                ->alias('b')
                ->join('nianji as ','b.grade=as.id' )
                ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
                ->where($tj)
                ->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('result',$datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //添加试卷
    public function add(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('souxue')->insert($data);
            $this->success("添加成功", url("suoxue/index"));
        }
        return $this->fetch();
    }
    //修改试卷
        public function etid(){
            $data=Db::name('nianji')->select();
            $this->assign('data',$data);
            $id = $this->request->param('id', 0, 'intval');
            $rolesSrc =    Db::name('souxue')
                ->alias('b')
                ->join('nianji as ','b.grade=as.id' )
                ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping,b.grade')
               ->where('b.id',$id)
              ->find();
            $this->assign('datas',$rolesSrc);

            if( $this->request->post()) {
                $data = $this->request->param();

                Db::name('souxue')->update($data);
                $this->success("修改成功", url("suoxue/index"));
            }


            return $this->fetch();

        }
        //删除试卷
        public function dell(){
            $id = $this->request->param('id', 0, 'intval');
           Db::name('souxue')->delete($id);
            $this->success("删除成功", url("suoxue/index"));
        }
		
		//阅读列表
		public function reading_index(){
			$where = array();
			 if( $this->request->post()) {
				 $title = $this->request->post('title');
				 $where['title'] = array('like','%'.$title.'%');
			 }
			
			
			$rolesSrc = Db::name('sx_reading')
				->where($where)
				->paginate(20);
			// var_dump($rolesSrc);die;
			$page = $rolesSrc->render();
			$this->assign('page',$page);
			$this->assign('result',$rolesSrc);
			return $this->fetch();
		}
		//添加阅读
		public function reading_add(){
			if( $this->request->post()) {
				$data = $this->request->param();
				$data['time'] = date('Y-m-d H:i:s');
				Db::name('sx_reading')->insert($data);
				$this->success("添加成功", url("suoxue/reading_index"));
			}
			return $this->fetch();
		}
		//编辑阅读
		public function reading_edit(){
			$id = $this->request->param('id', 0, 'intval');
			$data=Db::name('sx_reading')->where('id',$id )->find();
			$this->assign('data',$data);
			if( $this->request->post()) {
				$savedata = $this->request->param();

				Db::name('sx_reading')->update($savedata);
				$this->success("修改成功", url("suoxue/reading_index"));
			}


			return $this->fetch();

		}
		//删除阅读
		public function reading_delete(){
			$id = $this->request->param('id', 0, 'intval');
			Db::name('sx_reading')->delete($id);
			$this->success("删除成功", url("suoxue/reading_index"));
		}
}