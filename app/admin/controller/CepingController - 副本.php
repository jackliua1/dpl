<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
use think\Session;
use app\admin\model\CepingModel;
class CepingController extends AdminBaseController{

	function _initialize() {
		$this->CepingModel = new CepingModel();
    }
    public function index(){
        $datas = Db::name('test')
            ->field('t.id,t.title,as.n_name,t.time,t.hearingone,t.type,t.num,t.authentication')
            ->alias('t')
            ->join('nianji as ','t.grade=as.id' )
            ->paginate(20);
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
            $this->success("添加成功", url("Ceping/index"));
        }
        return $this->fetch();
    }


//删除
    public function dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('test')->delete($id);
        $this->success("删除成功！", url("Ceping/index"));

    }
//编辑
    public function etid(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=  Db::name('test')
            ->field('t.id,t.title,as.n_name,t.time,t.hearingone,t.type,t.num,t.authentication,as.id as ids')
            ->alias('t')
            ->join('nianji as ','t.grade=as.id' )
            ->where('t.id',$id)
            ->find();

        $this->assign('datas',$datas);
        return  $this->fetch();
    }
    public function etids(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('test')->update($data);
            $this->success("修改成功", url("Ceping/index"));
        }
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

    //分类列表
//    public function index(){
//        $data=Db::name('ceping_kc')->order("kc_id DESC")
//            ->paginate(10);
//        $page = $data->render();
//        $this->assign('page',$page);
//        $this->assign('data',$data);
//        return $this->fetch();
//    }
//    //添加页面
//    public function add(){
//        if( $this->request->post()) {
//            $data = $this->request->param();
//            Db::name('ceping_kc')->insert($data);
//            $this->success("添加成功", url("ceping/index"));
//        }
//        return $this->fetch();
//    }
//
////删除分类
//	public function dell(){
//		$id = $this->request->param('id', 0, 'intval');
//		Db::name('ceping_kc')->delete($id);
//		$this->success("删除成功！", url("Ceping/index"));
//	}
//            //编辑分类
//    public function edit(){
//        $id = $this->request->param('id', 0, 'intval');
//       $data= Db::name('ceping_kc')->where('kc_id',$id)->find();
//       $this->assign('data',$data);
//        return $this->fetch();
//    }
//    //修改分类
//    public function edit_post(){
//        if ($this->request->post()) {
//            $datas = $this->request->param();
//            Db::name('ceping_kc')->update($datas);
//            $this->success("编辑成功",url("Ceping/index"));
//        }
//    }
    //题目类型列表
    public function t_index(){
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('test')->where('id',$id)->find();

        session::set('ids',$datas['id']);
        $date=$_SESSION;
        $this->assign('date',$date);
        $data=Db::name('timu')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

//添加类型
    public function t_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('timu')->insert($data);
            $this->success("添加成功", url("ceping/t_index"));
        }
        return $this->fetch();
    }
    //编辑类型一
    public function t_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$datas);
        return $this->fetch();
    }
    //修改类型一
    public function update(){
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('timu')->update($datas);
            $this->success("编辑成功",url("Ceping/t_index"));
        }
    }

    //子标题
    public function ti_index(){
        $re=$_SESSION['think'];

        $this->assign('re',$re);
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$datas);
        $ids = $this->request->param('id', 0, 'intval');
        $data=Db::name('hearingone1')->where('type',$ids)->order("id DESC")
            ->paginate(20);
        $page = $data->render();
        $this->assign('page',$page);
        $this->assign('data',$data);
        if ($this->request->post()) {
            $data = $this->request->param();

            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);

        }

            $datas = Db::name('test')->where('id', $re['ids'])->find();
            $dater['hearingone'] = json_decode($datas['hearingone'], true);

            $this->assign('dater', $dater['hearingone']['a']);


        return $this->fetch();
    }
    public function xian(){
        if ($this->request->post()) {
            $data = $this->request->param();

            $datas = Db::name('test')->where('id',$data['id'])->find();

            // dump($datas);
            $arr = json_decode($datas['hearingone'],true);
            // dump($arr);

            $str = ',';
            foreach ($arr as $k => $v) {
                foreach ($arr[$k] as $k1 => $v1) {
                    $str .= $v1 . ',';
                }
            }
            
            // $str = rtrim($str,',');
            // dump($str);

            // $str = ',72,69,68,67,66,65,64,63,';


            return $str;
            die;



            $dater['hearingone'] = json_decode($datas['hearingone'], true);

         $re=$dater['hearingone']['a'];
//          $dr=  urldecode($re);
//          var_dump($dr);
           return $re;
        }
    }
    //题型1模糊查询
    public function ti_indexs(){
        $re=$_SESSION['think'];
        $this->assign('re',$re);
        if ($this->request->post()) {
            $data = $this->request->param();

            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();
            $datas =Db::name('hearingone1')->where($tj)->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //题型2模糊查询
    public function ti_indexs2(){
        $re=$_SESSION['think'];
        $this->assign('re',$re);
        if ($this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();
            $datas =Db::name('hearingone1')->where($tj)->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //题型3模糊查询
    public function ti_indexs3(){
        $re=$_SESSION['think'];
        $this->assign('re',$re);
        if ($this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();
            $datas =Db::name('hearingone1')->where($tj)->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //题型4模糊查询
    public function ti_indexs4(){
        $re=$_SESSION['think'];
        $this->assign('re',$re);
        if ($this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();
            $datas =Db::name('hearingone1')->where($tj)->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //题型5模糊查询
    public function ti_indexs5(){
        $re=$_SESSION['think'];
        $this->assign('re',$re);
        if ($this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');
            $tj['type'] = array('like','%'.$data['type'].'%');
            $datass =Db::name('hearingone1')->where($tj)->select();
            $datas =Db::name('hearingone1')->where($tj)->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('data', $datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //添加子题目
    public function ti_add(){
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$datas);
        $data=Db::name('timu_zibiaoti')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //添加标题
    public function te_add(){
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$datas);
        if( $this->request->post()) {
            $data = $this->request->param();
			$data['author_id']=cmf_get_current_admin_id();
			$data['time']=date("Y-m-d h:i:s");
            $info = Db::name('hearingone1')->insertGetId($data);

			if($info&&$data['type']==1){
				$data1['q1_id'] = $info;
				$data1['title'] = $data['title'];
				Db::name('hearingone2')->insert($data1);
			}
            $this->success("添加成功", url("Ceping/ti_index",array('id'=>$data['type'] )));
        }
        return $this->fetch();
    }
    //编辑类型一题目
    public function t_update(){
        $id = $this->request->param('id', 0, 'intval');
        $date= Db::name('hearingone1')->where('id',$id)->find();
		
		$srlget['type'] = $this->request->param('type');
		
        $this->assign('datas',$date);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->update($datas);
            $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
        }
		
		$this->assign('srlget',$srlget);
        return $this->fetch();
    }
    //阅读
    public function te_adds(){
        $id = $this->request->param('id', 0, 'intval');
        $datas= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$datas);
        return $this->fetch();
    }
    //删除标题
    public function t_dell(){
        $id = $this->request->param('id', 0, 'intval');
		$data['type'] = $this->request->param('type');
		$one2list= Db::name('hearingone2')->where('q1_id',$id)->select();
		// var_dump($one2list);die;
        $info = Db::name('hearingone1')->delete($id);
		if($info){
			foreach($one2list as $vo){
				Db::name('hearingone2')->delete($vo['id']);
			}
		}
        $this->success("删除成功！", url("Ceping/ti_index",array('id'=>$data['type'] )));
    }

    //题目一小题列表
    public function tm_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=Db::name('hearingone2')->where('q1_id',$id)->select();
		$num=count($datas);
		if($num==0){
			// var_dump('111');die;
			$data1['q1_id'] = $data['id'];
			$data1['title'] = $data['title'];
			$info = Db::name('hearingone2')->insertGetId($data1);
			$datas=Db::name('hearingone2')->where('id',$info)->select();
		}
		// var_dump('222');die;
        $this->assign('datas',$datas);
        return $this->fetch();
    }
    //添加题目
    public function tm_add(){
        $id = $this->request->param('id', 0, 'intval');
		$date= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('date',$date);
        if ($this->request->post()) {
            $datas = $this->request->param();
				IF(!empty($datas['questiona_img'])){
					$datas['questiona_img']=json_encode($datas['questiona_img']);
				}
                Db::name('hearingone2')->insert($datas);
            $this->success("添加成功",url("Ceping/tm_index",array('id'=>$datas['id'] )));
        }
        return $this->fetch();
    }
    //类型一题目编辑
    public function tm_update(){
        $id = $this->request->param('id', 0, 'intval');
        $datas=Db::name('hearingone2')->where('id',$id)->find();
		$dateold= Db::name('hearingone1')->where('id',$datas['q1_id'])->find();
		
        $data['questiona_img']=json_decode($datas['questiona_img']);
		// var_dump($datas);var_dump($data);die;
        $this->assign('dateold',$dateold);
		$this->assign('data',$data);
        $this->assign('date',$datas);
		// var_dump($this->request->param());var_dump($this->request->post());die;
        if ($this->request->post()) {
			// var_dump($this->request->post());die;
            $save = $this->request->param();
			IF(!empty($save['questiona_img'])){
					$save['questiona_img']=json_encode($save['questiona_img']);
				}
            
            Db::name('hearingone2')->update($save);
            $this->success("编辑成功",url("Ceping/tm_index",array('id'=>$datas['q1_id'] )));
        }
        return $this->fetch();
    }
    //删除题目
	public function tm_dell(){
        $id = $this->request->param('id', 0, 'intval');
        $data['id'] = $this->request->param('id');
//        var_dump($data);die;
//        $one2list= Db::name('hearingone2')->where('q1_id',$id)->select();

        $info = Db::name('hearingone2')->delete($id);
//        if($info){
//            foreach($one2list as $vo){
//                Db::name('hearingone2')->delete($vo['id']);
//            }
//        }
        $this->success("删除成功！", url("Ceping/tm_index",array('id'=>$data['id'] )));
	}
	//题目二类型
	public function tmer_add(){
		$id = $this->request->param('id', 0, 'intval');
		$data= Db::name('timu')->where('id',$id)->find();
		$this->assign('datas',$data);
		if ($this->request->post()) {
			$datas = $this->request->param();
			$datas['author_id']=cmf_get_current_admin_id();
			$datas['time']=date("Y-m-d h:i:s");
             Db::name('hearingone1')->insertGetId($datas);

            $this->success("添加成功", url("Ceping/ti_index",array('id'=>$datas['type'] )));
		}
		return $this->fetch();
	}
	//编辑题型二子标题
    public function tmer_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();

            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->update($datas);
            $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
        }
        $this->assign('srlget',$srlget);
        return $this->fetch();
    }
            //删除题型二子标题
    public function tmer_dell(){
        $id = $this->request->param('id', 0, 'intval');
        $data['type'] = $this->request->param('type');
        $one2list= Db::name('hearingone2')->where('q1_id',$id)->select();
        // var_dump($one2list);die;
        $info = Db::name('hearingone1')->delete($id);
        if($info){
            foreach($one2list as $vo){
                Db::name('hearingone2')->delete($vo['id']);
            }
        }
        $this->success("删除成功！", url("Ceping/ti_index",array('id'=>$data['type'] )));
    }
	//题型二列表
	public function tmerzi_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=Db::name('hearingone2')->where('q1_id',$id)->select();
//        $num=count($datas);
//        if($num==0){
//            // var_dump('111');die;
//            $data1['q1_id'] = $data['id'];
//            $data1['title'] = $data['title'];
//            $info = Db::name('hearingone2')->insertGetId($data1);
//            $datas=Db::name('hearingone2')->where('id',$info)->select();
//        }
        // var_dump('222');die;
        $this->assign('datas',$datas);
		return $this->fetch();
	}
	//添加题目
	public function tmerzi_add(){
                $id = $this->request->param('id', 0, 'intval');
                $date= Db::name('hearingone1')->where('id',$id)->find();
                $this->assign('date',$date);
                if ($this->request->post()) {
                    $datas = $this->request->param();
                    Db::name('hearingone2')->insert($datas);
                    $this->success("添加成功",url("Ceping/tmerzi_index",array('id'=>$datas['q1_id'] )));
                }
                return $this->fetch();

            }
    //题型二编辑题目
    public function tmerzi_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $date= Db::name('hearingone2')->where('id',$id)->find();
		$dateold= Db::name('hearingone1')->where('id',$date['q1_id'])->find();
		
		$this->assign('dateold',$dateold);
        $this->assign('date',$date);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->update($datas);
            $this->success("编辑成功",url("Ceping/tmerzi_index",array('id'=>$date['q1_id'] )));
        }
        return $this->fetch();

    }
            //删除题目二
    public function tmerzi_dell(){
        $id = $this->request->param('id', 0, 'intval');

        $data['type'] = $this->request->param('id');

        $one2list= Db::name('hearingone2')->where('id',$id)->find();

        Db::name('hearingone2')->delete($id);
//        if($info){
//            foreach($one2list as $vo){
//                Db::name('hearingone2')->delete($vo['id']);
//            }
//        }
        $this->success("删除成功！", url("Ceping/tmerzi_index",array('id'=>$one2list['q1_id'] )));
    }
            //添加题型三字标题
            public function txs_add(){
                $id = $this->request->param('id', 0, 'intval');
                $data= Db::name('timu')->where('id',$id)->find();
                $this->assign('datas',$data);
                if ($this->request->post()) {
                    $datas = $this->request->param();
                    $datas['author_id']=cmf_get_current_admin_id();
                    $datas['time']=date("Y-m-d h:i:s");
                    $info = Db::name('hearingone1')->insertGetId($datas);

                    if($info&&$datas['type']==3){
                        $data1['q1_id'] = $info;
                        $data1['title'] = $datas['title'];
                        Db::name('hearingone2')->insert($data1);
                    }
                    $this->success("添加成功", url("Ceping/ti_index",array('id'=>$datas['type'] )));
                }
                return $this->fetch();
            }
            //题型三字标题编辑
    public function txs_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('srlget',$srlget);
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->update($datas);
            $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
        }
        return $this->fetch();
    }
            //题型三删除字标题
    public function txs_dells(){
        $id = $this->request->param('id', 0, 'intval');
        $srlget['type'] = $this->request->param('type');
        Db::name('hearingone1')->delete($id);
        $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
    }
            //题型三题目列表
    public function txs_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=Db::name('hearingone2')->where('q1_id',$id)->select();
        $num=count($datas);
        if($num==0){
            // var_dump('111');die;
            $data1['q1_id'] = $data['id'];
            $data1['title'] = $data['title'];
            $info = Db::name('hearingone2')->insertGetId($data1);
            $datas=Db::name('hearingone2')->where('id',$info)->select();
        }
        // var_dump('222');die;
        $this->assign('datas',$datas);
        return $this->fetch();

    }
    //类型三题目
    public function txsm_add(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->insert($datas);
            $this->success("添加成功",url("Ceping/t_index"));
        }
        return $this->fetch();
    }
    //题型三题目编辑
    public function txsm_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone2')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->update($datas);
            $this->success("编辑成功",url("Ceping/txs_index",array('id'=>$data['q1_id'])));
        }
        return $this->fetch();
    }
    //题型三题目删除
    public function txs_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('hearingone2')->delete($id);
        $this->success("删除成功！", url("Ceping/t_index"));
    }

    //题型四添加子标题
    public function txsi_add(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->insert($datas);
//

            $this->success("添加成功", url("Ceping/ti_index",array('id'=>$datas['type'] )));
        }
        return $this->fetch();
    }
    //题型四编辑子标题
    public function txsi_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('srlget',$srlget);
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->update($datas);
            $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
        }
        return $this->fetch();
    }
    //题型四删除字标题
    public function txsi_dells(){
        $id = $this->request->param('id', 0, 'intval');
        $data['type'] = $this->request->param('type');
        Db::name('hearingone1')->delete($id);
        $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$data['type'] )));
    }
    //题型四题目列表
    public function txsi_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('dates',$data);
        $id = $this->request->param('id', 0, 'intval');

        $datas=Db::name('hearingone2')->where('q1_id',$id)->select();
		
		foreach($datas as $vo){
			if(empty($vo['url'])){
				$urlnew = $this->CepingModel->read_fanyi($vo['id'].'_timu4',$vo['title']);
				// var_dump($urlnew);die;
				$save['id'] = $vo['id'];
				$save['url'] = $urlnew;
				 // var_dump($urlnew);die;
				Db::name('hearingone2')->update($save);
			}
			
		}
//        $num=count($datas);
//        if($num==0){
//            // var_dump('111');die;
//            $data1['q1_id'] = $data['id'];
//            $data1['title'] = $data['title'];
//            $info = Db::name('hearingone2')->insertGetId($data1);
//            $datas=Db::name('hearingone2')->where('id',$info)->select();
//        }
//        // var_dump('222');die;
        $this->assign('datas',$datas);
        return $this->fetch();
    }
    //题型四添加题目
    public function txsi_adds(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('q1_id');
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->insert($datas);
            $this->success("编辑成功",url("Ceping/txsi_index",array('id'=>$srlget['type'] )));
        }
        return $this->fetch();
    }
    //题型四编辑题目
    public function txsi_update(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone2')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('q1_id');
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->update($datas);
            $this->success("编辑成功",url("Ceping/txsi_index",array('id'=> $srlget['type'] )));
        }
        return $this->fetch();
    }
    //删除题型四题目
    public function txsi_dell(){
        $id = $this->request->param('id', 0, 'intval');

        $data['type'] = $this->request->param('id');

        $one2list= Db::name('hearingone2')->where('id',$id)->find();

        Db::name('hearingone2')->delete($id);
//        if($info){
//            foreach($one2list as $vo){
//                Db::name('hearingone2')->delete($vo['id']);
//            }
//        }
        $this->success("删除成功！", url("Ceping/tmerzi_index",array('id'=>$one2list['q1_id'] )));
    }
        //题型五添加子标题
    public function txw_add(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('timu')->where('id',$id)->find();
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            $info = Db::name('hearingone1')->insertGetId($datas);

            if($info&&$datas['type']==5){
                $data1['q1_id'] = $info;
                $data1['title'] = $datas['title'];
                Db::name('hearingone2')->insert($data1);
            }
            $this->success("添加成功", url("Ceping/ti_index",array('id'=>$datas['type'] )));
        }
        return $this->fetch();
    }
    //题型五编辑子标题
    public function txw_edit(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('srlget',$srlget);
        $this->assign('datas',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            $datas['author_id']=cmf_get_current_admin_id();
            $datas['time']=date("Y-m-d h:i:s");
            Db::name('hearingone1')->update($datas);
            $this->success("编辑成功",url("Ceping/ti_index",array('id'=>$srlget['type'] )));
        }
        return $this->fetch();
    }
    //删除题型五的字标题
    public function txw_dells(){
        $id = $this->request->param('id', 0, 'intval');
        $data['type'] = $this->request->param('type');
        Db::name('hearingone1')->delete($id);
        $this->success("删除成功！", url("Ceping/ti_index",array('id'=>$data['type'] )));

    }
    //题型五题目列表
    public function txw_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $this->assign('dates',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=Db::name('hearingone2')->where('q1_id',$id)->select();
       $num=count($datas);
       if($num==0){
           $data1['q1_id'] = $data['id'];
           $data1['title'] = $data['title'];
           $info = Db::name('hearingone2')->insertGetId($data1);
           $datas=Db::name('hearingone2')->where('id',$info)->select();
       }
	   
	   
	   
        $this->assign('date',$datas);
        // var_dump('222');die;
        return $this->fetch();
    }

    //题型五添加题目
    public function txw_adds(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone1')->where('id',$id)->find();
        $srlget['type'] = $this->request->param('type');
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();
            Db::name('hearingone2')->insert($datas);
            $this->success("添加成功",url("Ceping/t_index"));
        }
        return $this->fetch();
    }
    //题型五编辑题目
    public function txw_update(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('hearingone2')->where('id',$id)->find();
        $this->assign('date',$data);
        if ($this->request->post()) {
            $datas = $this->request->param();

            Db::name('hearingone2')->update($datas);
            $this->success("编辑成功",url("Ceping/txw_index",array('id'=>$data['q1_id'] )));

        }
        return $this->fetch();
    }
    //题型五题目删除
    public function txw_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('hearingone2')->delete($id);
        $this->success("删除成功！", url("Ceping/t_index"));
    }
    //试卷
    public function s_index(){
        $id = $this->request->param('id', 0, 'intval');
        $data= Db::name('test')->where('id',$id)->find();
		if ($this->request->post()) {
            $datas = $this->request->param();
			
			// var_dump($datas);die;
			foreach($datas['ids'] as $k => $vo){
				$sort[$vo] = $datas['list_orders'][$k];
			}
			
			$save['sort'] = json_encode($sort);
			// var_dump($id);var_dump($save);
			// var_dump(Db::name('test')->where(array('id'=>$id))->update($save));die;
            Db::name('test')->where(array('id'=>$id))->update($save);
            $this->success("操作成功");

        }
		$testsort = json_decode($data['sort'],true);
		// var_dump($testsort);die;
		
         $datas= json_decode( $data['hearingone'],true)  ;
         if($datas==null){
             $this->error('请到题目列表添加题目！');
         }else{
             $datr=  array_merge($datas['a'],$datas['b'],$datas['c'],$datas['d'],$datas['e']);
			 
			 
             $datae = [];
             foreach ($datr as $v){
                 $list = Db::name('hearingone2')->where('q1_id',$v)->select();
//                dump($list);
                 $tr[]= json_decode(json_encode($list),true);

             }
			 
             // $tr=[];
             // foreach ($datae as $k=>$vs){
                 // $tr[]=  $vs;
             // }
			$datrs=array();
			foreach($tr as $votr ){
				$datrs=array_merge($datrs,$votr);
			 }
             // $datrs=  array_merge($tr[0],$tr[1],$tr[2],$tr[3],$tr[4],$tr[5],$tr[6],$tr[7],$tr[8],$tr[9],$tr[10],$tr[11],$tr[12],$tr[13],$tr[14]);
			
// if( $id = 14){
				 // var_dump($datr); var_dump($tr); 
				 // var_dump($testsort);die;
			 // }
             foreach ($datrs as $k=>$vl){
                 $datew[$k]['questiona_img']=   json_decode($vl['questiona_img']);
             }
             $arr = array();

             foreach($datrs as $k=>$r){
                 $arr[] = array_merge($r,$datew[$k]);
             }
         }
		 
		 // if($testsort){
			 foreach ($arr as  $key => $vo){
				 
				 if($testsort){
					 $vo['sort'] = $testsort[$vo['id']];
				 }else{
					 $vo['sort'] = '';
				 }
				 
                 $newlist[] = $vo;
             }
			 $arr = $newlist;
		 // }
		// array_multisort($this->_array_column($arr,'sort'),SORT_ASC,$arr);
		$arr = srl_array_sort($arr,'sort','asc');
		 // var_dump($tr);var_dump($datae);var_dump($datrs);
		 // var_dump($newlist);die;
        $this->assign('data',$data);
        $this->assign('datas',$arr);
        return $this->fetch();
    }
	


}