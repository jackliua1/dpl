<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
use think\db\Query;
use think\Session;
class ShijuanController extends AdminBaseController{

//试卷列表
    public function index(){
        $datas = Db::name('test')
            ->field('t.id,t.title,as.n_name,t.time,t.hearingone,t.type,t.num,t.authentication')
            ->alias('t')
            ->join('nianji as ','t.grade=as.id' )
            ->paginate(10);
        $page = $datas->render();;
        $this->assign('page',$page);
        $this->assign('data',$datas);
        if( $this->request->post()) {
            $data = $this->request->param();
            $datas= Db::name('test')
                ->field('t.id,t.title,as.n_name,t.time,t.hearingone,t.type,t.num,t.authentication')
                ->alias('t')
                ->join('nianji as ','t.grade=as.id' )
                ->where($data)
                ->select();
            $this->assign('data',$datas);
            $this->assign('title', isset($data['title']) ? $data['title'] : '');


        }
        return $this->fetch();
    }
    //添加试卷
    public function add(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();
           $data['type'] =cmf_get_current_admin_id();
//        $date= Db::name('hearingone1')->where('type',1)->limit(5)->select();

            Db::name('test')->insert($data);
            $this->success("添加成功", url("Shijuan/index"));
        }
        return $this->fetch();
    }
    //查看试卷
    public function authorize(){
        $datas= Db::name('hearingone1')
            ->alias('t')
            ->join('hearingone2 as ','t.id=as.q1_id' )
            ->select();


        return $this->fetch();
    }

//删除
        public function dell(){
            $id = $this->request->param('id', 0, 'intval');
            Db::name('test')->delete($id);
            $this->success("删除成功！", url("Shijuan/index"));

        }
//编辑
        public function etid(){
            $data=Db::name('nianji')->select();
            $this->assign('data',$data);
            $id = $this->request->param('id', 0, 'intval');
            $datas=  Db::name('test')
                ->field('t.id,t.title,as.n_name,t.time,t.hearingone,t.type,t.num,t.authentication')
                ->alias('t')
                ->join('nianji as ','t.grade=as.id' )
                ->where('t.id',$id)
                ->find();
            $this->assign('datas',$datas);
            return  $this->fetch();
        }
//类型一添加题目
            public function listOrder(){
                $re=$_SESSION['think'];
            $der['ids']=$re['ids'];
            if($der['ids']==null){
                $this->error('请到试卷管理添加题目！');
                }else{

                if( $this->request->post()) {
                    $data = $this->request->param();
                    $dated= Db::name('test')->where('id',$data['id'])->find();
                    $dater['hearingone']=json_decode($dated['hearingone'],true);
                    $de['a']=$dater['hearingone']['a'];
                    if($de['a']==null){
                        $datae['hearingone']['a']=$data['hearingone'];

                        foreach( $datae['hearingone']['a'] as $k=>$v)
                        {
                            $datae['hearingone']['a'][$k]=(int)$v;
                        }

                        $datas['hearingone']=json_encode($datae['hearingone']);
                        $datas['id']=$data['id'];

                      Db::name('test')->update($datas);
                        $this->success("添加成功", url("Ceping/t_index",array('id'=>$data['id'])));
                    }else{
                        $datae['a']=$data['hearingone'];

                        foreach($datae as $key => $value) {
                            $dater['hearingone'][$key]=$value;
                        }
                        //转换整型
                        foreach( $dater['hearingone']['a'] as $k=>$v)
                        {
                            $dater['hearingone']['a'][$k]=(int)$v;
                        }

                        $dataes['id'] = $data['id'];
                        $dataes['hearingone']=json_encode($dater['hearingone']);

                        Db::name('test')->update($dataes);

                        $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                          }
                    }
                 }


                }


//类型二添加题目
    public function listOrder2(){
        $re=$_SESSION['think'];
        $der['ids']=$re['ids'];
        if($der['ids']==null){
            $this->error('请到试卷管理添加题目！');
        }else {
            if ($this->request->post()) {
                $data = $this->request->param();
                $dated = Db::name('test')->where('id', $data['id'])->find();
                $dater['hearingone'] = json_decode($dated['hearingone'], true);
                $de['b'] = $dater['hearingone'];
                if ($de['b'] == null) {
                    $datae['hearingone']['b'] = $data['hearingone'];
                    foreach ($dater as $k => $r) {
                        $arr = array_merge($r, $datae[$k]);
                    }
                    foreach( $arr as $k=>$v)
                    {
                        $dater['hearingone']['b'][$k]=(int)$v;
                    }
                    $de['hearingone'] = json_encode($arr);
                    $de['id'] = $data['id'];
                    Db::name('test')->update($de);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                } else {
                    $datae['b'] = $data['hearingone'];
                    foreach ($datae as $key => $value) {
                        $dater['hearingone'][$key] = $value;
                    }
                    foreach( $dater['hearingone']['b'] as $k=>$v)
                    {
                        $dater['hearingone']['b'][$k]=(int)$v;
                    }

                    $dataes['id'] = $data['id'];
                    $dataes['hearingone'] = json_encode($dater['hearingone']);

                    Db::name('test')->update($dataes);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                }
            }
        }
    }
//类型三添加题目
    public function listOrder3(){
        $re=$_SESSION['think'];
        $der['ids']=$re['ids'];
        if($der['ids']==null){
            $this->error('请到试卷管理添加题目！');
        }else {
            if ($this->request->post()) {
                $data = $this->request->param();
                $dated = Db::name('test')->where('id', $data['id'])->find();
                $dater['hearingone'] = json_decode($dated['hearingone'], true);
                $de['c'] = $dater['hearingone'];
                if ($de['c'] == null) {
                    $datae['hearingone']['c'] = $data['hearingone'];
                    foreach ($dater as $k => $r) {
                        $arr = array_merge($r, $datae[$k]);
                    }
                    foreach( $arr as $k=>$v)
                    {
                        $dater['hearingone']['c'][$k]=(int)$v;
                    }
                    $de['hearingone'] = json_encode($arr);
                    $de['id'] = $data['id'];
                    Db::name('test')->update($de);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                } else {
                    $datae['c'] = $data['hearingone'];
                    foreach ($datae as $key => $value) {
                        $dater['hearingone'][$key] = $value;
                    }
                    foreach( $dater['hearingone']['c'] as $k=>$v)
                    {
                        $dater['hearingone']['c'][$k]=(int)$v;
                    }

                    $dataes['id'] = $data['id'];
                    $dataes['hearingone'] = json_encode($dater['hearingone']);

                    Db::name('test')->update($dataes);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                }
            }
        }
    }
    //类型四添加题目
    public function listOrder4(){
        $re=$_SESSION['think'];
        $der['ids']=$re['ids'];
        if($der['ids']==null){
            $this->error('请到试卷管理添加题目！');
        }else {
            if ($this->request->post()) {
                $data = $this->request->param();
                $dated = Db::name('test')->where('id', $data['id'])->find();
                $dater['hearingone'] = json_decode($dated['hearingone'], true);
                $de['d'] = $dater['hearingone'];
                if ($de['d'] == null) {
                    $datae['hearingone']['d'] = $data['hearingone'];
                    foreach ($dater as $k => $r) {
                        $arr = array_merge($r, $datae[$k]);
                    }
                    foreach( $arr as $k=>$v)
                    {
                        $dater['hearingone']['d'][$k]=(int)$v;
                    }
                    $de['hearingone'] = json_encode($arr);
                    $de['id'] = $data['id'];
                    Db::name('test')->update($de);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                } else {
                    $datae['d'] = $data['hearingone'];
                    foreach ($datae as $key => $value) {
                        $dater['hearingone'][$key] = $value;
                    }
                    foreach( $dater['hearingone']['d'] as $k=>$v)
                    {
                        $dater['hearingone']['d'][$k]=(int)$v;
                    }

                    $dataes['id'] = $data['id'];
                    $dataes['hearingone'] = json_encode($dater['hearingone']);

                    Db::name('test')->update($dataes);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                }
            }
        }
    }
    //类型五添加题目
    public function listOrder5()
    {
        $re=$_SESSION['think'];
        $der['ids']=$re['ids'];
        if($der['ids']==null){
            $this->error('请到试卷管理添加题目！');
        }else {
            if ($this->request->post()) {
                $data = $this->request->param();
                $dated = Db::name('test')->where('id', $data['id'])->find();
                $dater['hearingone'] = json_decode($dated['hearingone'], true);
                $de['e'] = $dater['hearingone'];
                if ($de['e'] == null) {
                    $datae['hearingone']['e'] = $data['hearingone'];
                    foreach ($dater as $k => $r) {
                        $arr = array_merge($r, $datae[$k]);
                    }
                    foreach( $arr as $k=>$v)
                    {
                        $dater['hearingone']['e'][$k]=(int)$v;
                    }
                    $de['hearingone'] = json_encode($arr);
                    $de['id'] = $data['id'];
                    Db::name('test')->update($de);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                } else {
                    $datae['e'] = $data['hearingone'];
                    foreach ($datae as $key => $value) {
                        $dater['hearingone'][$key] = $value;
                    }
                    foreach( $dater['hearingone']['e'] as $k=>$v)
                    {
                        $dater['hearingone']['e'][$k]=(int)$v;
                    }

                    $dataes['id'] = $data['id'];
                    $dataes['hearingone'] = json_encode($dater['hearingone']);

                    Db::name('test')->update($dataes);
                    $this->success("添加成功", url("Ceping/t_index", array('id' => $data['id'])));
                }
            }
        }
    }
}