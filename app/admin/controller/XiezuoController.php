<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
use app\admin\model\CepingModel;


class XiezuoController extends AdminBaseController{
//写作列表

	function _initialize() {
		$this->CepingModel = new CepingModel();
    }
    public function index(){
        $rolesSrcs = Db::name('writing')
            ->field('b.id,b.image,b.time,ass.n_name,as.user_login,b.num,b.nums')
            ->alias('b')
            ->join('nianji ass ','b.G_ararry=ass.id ' )
            ->join('user as','b.uid=as.id')
            ->order("id")
            ->paginate(20);
        $page = $rolesSrcs->render();;

        $this->assign('page',$page);
        $this->assign('data',$rolesSrcs);
        if( $this->request->post()) {
            $data = $this->request->param();
           $datas = Db::name('writing')
               ->field('b.id,b.image,b.time,ass.n_name,as.user_login,b.num,b.nums')
               ->alias('b')
               ->join('nianji ass ','b.G_ararry=ass.id ' )
               ->join('user as','b.uid=as.id')
               ->where($data)
               ->select();
            $this->assign('data',$datas);
            $this->assign('user_login', isset($data['user_login']) ? $data['user_login'] : '');
        }
        return $this->fetch();

    }
//添加写作
public function add(){
    $data=Db::name('nianji')->select();
    $this->assign('data',$data);
    if( $this->request->post()){
        $data = $this->request->param();
        $data['uid']=  cmf_get_current_admin_id();
        $data['num']=substr(date("ymdHis"),2,8).mt_rand(100000,999999);
        Db::name('writing')->insert($data);
        $this->success("添加成功",url("Xiezuo/index"));
    }
    return $this->fetch();
}
//删除写作
public function dell(){
    $id = $this->request->param('id', 0, 'intval');
    Db::name('writing')->delete($id);
    $this->success("删除成功！",url('Xiezuo/index'));
}
//修改写作
public function etid(){
    $data=Db::name('nianji')->select();
    $this->assign('data',$data);
    $id = $this->request->param('id', 0, 'intval');
      $datas=   Db::name('writing')
          ->alias('b')
          ->join('nianji ass ','b.G_ararry=ass.id ' )
          ->where('b.id',$id)
          ->find();
    $this->assign('datas',$datas);
    return $this->fetch();
}
//修改到数据库
public function updata(){
    if( $this->request->post()){
        $data = $this->request->param();

        Db::name('writing')->update($data);
        $this->success("修改成功",url("Xiezuo/index"));
    }
}
//默写列表
public function m_index(){
    $rolesSrcs = Db::name('silentwriting')
        ->field('b.*,ass.n_name,as.user_login')
        ->alias('b')
        ->join('nianji ass ','b.G_ararry=ass.id ' )
        ->join('user as','b.uid=as.id')
        ->order("id")
        ->paginate(20);
    $page = $rolesSrcs->render();;

    $this->assign('page',$page);
    $this->assign('data',$rolesSrcs);
    if( $this->request->post()) {
        $data = $this->request->param();
        $datas = Db::name('silentwriting')
            ->field('b.*,ass.n_name,as.user_login')
            ->alias('b')
            ->join('nianji ass ','b.G_ararry=ass.id ' )
            ->join('user as','b.uid=as.id')
            ->where($data)
            ->select();
        $this->assign('data',$datas);
        $this->assign('user_login', isset($data['user_login']) ? $data['user_login'] : '');
    }
    return $this->fetch();
}
//添加默写
        public function m_add(){
            $data=Db::name('nianji')->select();
            $this->assign('data',$data);
            if( $this->request->post()){
                $datas = $this->request->param();
                $datas['uid']=  cmf_get_current_admin_id();
                $datas['num']=substr(date("ymdHis"),2,8).mt_rand(100000,999999);
                Db::name('silentwriting')->insert($datas);
                $this->success("添加成功",url("Xiezuo/m_index"));
            }
            return $this->fetch();
        }
        //编辑默写
    public function m_etid(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $datas=   Db::name('silentwriting')
            ->field('b.*,ass.n_name')
            ->alias('b')
            ->join('nianji ass ','b.G_ararry=ass.id ' )
            ->where('b.id',$id)
            ->find();
		if(empty($datas['answer'])&&!empty($datas['image'])){
			$img = cmf_get_image_preview_url($datas['image']);
			
			$answer =  $this->CepingModel->readimg($img);
			$answerlist =  $answer['moxie'];
			$save['id'] = $id;
			$save['answer'] = $answer['moxiejson'];
			Db::name('silentwriting')->update($save);
		}else{
			$answerlist =  $this->CepingModel->object_to_array(json_decode($datas['answer']));
		}
		
		// var_dump($datas);var_dump($answerlist);die;
        $this->assign('datas',$datas);
		$this->assign('answerlist',$answerlist);
        return $this->fetch();
    }
    public function m_update(){
        if( $this->request->post()){
            $data = $this->request->param();
			unset($data['excel_name']);
			unset($data['excel_url']);
			
			$excel_name = $this->request->param('excel_name');
            $excel_url = $this->request->param('excel_url');
            $id = $this->request->param('id');
			// var_dump($excel_url);die;
            if (!empty($excel_name) && !empty($excel_url)) {
                $filename = ROOT_PATH . 'public/upload/' . $excel_url;  //文件路径
                $extension = cmf_get_file_extension($excel_url);    //文件扩展名
                if ($extension == 'xlsx') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else if ($extension == 'xls') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else {
                    $this->error('请上传Excel格式的文件！', '');
                }
                $excel_array = $objPHPExcel->getsheet(0)->toArray();
                $dataexl = [];
				// var_dump($excel_array);die;
                foreach ($excel_array as $k => $v) {
					if($k>0){
						$dataexl[$k]['word'] = $v[0];
						$dataexl[$k]['translation'] = $v[1];
						$dataexl[$k]['phonetic'] = $v[2];
						$dataexl[$k]['sid'] = $id;
						$dataexl[$k]['option1'] = $v[3];
						$dataexl[$k]['option2'] = $v[4];
						$dataexl[$k]['option3'] = $v[5];
						$dataexl[$k]['option4'] = $v[6];
						$dataexl[$k]['eg'] = $v[7];
					}
                }
				if (Db::name('word')->insertAll($dataexl)) {
					$wordlist=Db::name('word')->where(array('sid'=>$id,'eg'=> array('neq','')))->select();
					foreach($wordlist as $vw){
						$egarr = explode('。',$vw['eg']);
						// var_dump($egarr);
						if($egarr){
							foreach($egarr as $ve){
								$eg = explode('.',$ve);
								if(count($eg)>1){
									Db::name('word_eg')->insert(array('zn'=>$eg[0],'en'=>$eg[1],'wid'=>$vw['id']));
								}
								Db::name('word')->update(array('id'=>$vw['id'],'eg'=>''));
							}
						}
						
					}
					// var_dump('1111');
					
					// $this->success('导入数据成功！', url('Xiezuo/m_index'));
				} else {
					$this->error('导入数据失败!', '');
				}
                
            } 
			$data['answer']=json_encode($data['answer']);
            Db::name('silentwriting')->update($data);
            $this->success("修改成功",url("Xiezuo/m_index"));
        }
    }
//删除默写
        public function m_dell(){
            $id = $this->request->param('id', 0, 'intval');
            Db::name('silentwriting')->delete($id);
            $this->success("删除成功！",url('Xiezuo/m_index'));
        }
		
		
		//单词列表
		public function w_index(){
			$id = $this->request->param('id', 0, 'intval');
			
			$list=Db::name('word')->where(array('sid'=>$id))->select();
			
			$this->assign('data',$list);
			
			return $this->fetch();
		}
		//编辑单词
		public function w_etid(){
			$id = $this->request->param('id', 0, 'intval');

			$info=Db::name('word')->where(array('id'=>$id))->find();	
			$eglist=Db::name('word_eg')->where(array('wid'=>$id))->select();	

			$this->assign('datas',$info);
			$this->assign('answerlist',$eglist);
			return $this->fetch();
		}
		//修改到数据库
		public function w_updata(){
			if( $this->request->post()){
				$data = $this->request->param();
				// var_dump($data);die;
				$answereg = '';
				
				
				if($data['answer']){
					foreach($data['answer'] as $va){
						// Db::name('word_eg')->delete($va['id']);
						$va['wid'] = $data['id'];
						unset($va['id']);
						$answereg[] = $va;
					}
				}
				if($data['answernew']){
					foreach($data['answernew'] as $vaw){
						$vaw['wid'] = $data['id'];
						$answereg[] = $vaw;
					}
				}
				
				// 
				unset($data['answer']);
				unset($data['answernew']);
				Db::name('word')->update($data);
				
				//上面修改成功了再处理例句
				Db::name('word_eg')->where('wid',$data['id'])->delete();
				Db::name('word_eg')->insertAll($answereg);
				
				$this->success("修改成功", url("Xiezuo/w_index", array('id' => $data['sid'])));
			}
		}
		
		//删除单词
        public function w_dell(){
            $id = $this->request->param('id', 0, 'intval');
            Db::name('word')->delete($id);
            $this->success("删除成功！");
        }
		
		//临时导入单词，怨念
		public function daoru(){
		$data=Db::name('nianji')->select();
        $this->assign('data',$data);
        if( $this->request->post()){
            $data = $this->request->param();
			unset($data['excel_name']);
			unset($data['excel_url']);
			
			$G_ararry = $this->request->param('G_ararry');
			$excel_name = $this->request->param('excel_name');
            $excel_url = $this->request->param('excel_url');
            // $id = $this->request->param('id');
			// var_dump($excel_url);var_dump($excel_url);die;
            if (!empty($excel_name) && !empty($excel_url)) {
                $filename = ROOT_PATH . 'public/upload/' . $excel_url;  //文件路径
                $extension = cmf_get_file_extension($excel_url);    //文件扩展名
                if ($extension == 'xlsx') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else if ($extension == 'xls') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else {
                    $this->error('请上传Excel格式的文件！', '');
                }
                $excel_array = $objPHPExcel->getsheet(0)->toArray();
                $dataexl = [];
				// var_dump($excel_array);
                foreach ($excel_array as $k => $v) {
					$silentwritinginfo=Db::name('silentwriting')->where(array('title'=>$v[4]))->find();
					if($silentwritinginfo){
						$sid = $silentwritinginfo['id'];
					}else{
						$datasilentwriting = array('title'=> $v[4],'G_ararry'=> $G_ararry,'uid'=> 1,'num'=> substr(date("ymdHis"),2,8).mt_rand(100000,999999),'time'=> date('Y-m-d H:i:s'));
						$sid = Db::name('silentwriting')->insertGetId($datasilentwriting);
					}
					// var_dump($sid);die;
					if($v[0]){
						$dataexl[$k]['must'] = 1;
					}else{
						$dataexl[$k]['must'] = 0;
					}
						$dataexl[$k]['word'] = $v[1];
						$dataexl[$k]['genre'] = $v[2];
						$dataexl[$k]['sid'] = $sid;
						$dataexl[$k]['translation'] = $v[3];
						$dataexl[$k]['option1'] = $v[3];
						
						// $dataexl[$k]['option2'] = $v[4];
						// $dataexl[$k]['option3'] = $v[5];
						// $dataexl[$k]['option4'] = $v[6];
						// $dataexl[$k]['eg'] = $v[7];
					
                }
				if (Db::name('word')->insertAll($dataexl)) {
					// $wordlist=Db::name('word')->where(array('sid'=>$id,'eg'=> array('neq','')))->select();
					// foreach($wordlist as $vw){
						// $egarr = explode('。',$vw['eg']);\
						// if($egarr){
							// foreach($egarr as $ve){
								// $eg = explode('.',$ve);
								// if(count($eg)>1){
									// Db::name('word_eg')->insert(array('zn'=>$eg[0],'en'=>$eg[1],'wid'=>$vw['id']));
								// }
								// Db::name('word')->update(array('id'=>$vw['id'],'eg'=>''));
							// }
						// }
						
					// }
				} else {
					$this->error('导入数据失败!', '');
				}
                
            } 
			// $data['answer']=json_encode($data['answer']);
            // Db::name('silentwriting')->update($data);
            $this->success("修改成功",url("Xiezuo/m_index"));
        }
		return $this->fetch();
    }
}