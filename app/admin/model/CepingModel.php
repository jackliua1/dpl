<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:kane < chengjin005@163.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Exception;
use think\Model;
use tree\Tree;
use think\Db;
use think\Request;

class CepingModel extends Model
{
	//基础信息
	 public function jichu() {
		$info['APP_ID'] = '5beb8f89';
		$info['APP_KEY_IAT'] = 'a4da5e9fba5091323cf89dafed5a2c5c';
		$info['APP_KEY_ISE'] = '9fb847fa1f70015c41b74553683afe40';
		$info['APP_KEY_TTS'] = '65a3425ceba6eb4846a7ca0a8aaa07d7';
		$info['APP_KEY_HW'] = '5bb2afb2bc2a4c07321cbb77fc08cb20';
		$info['duanxin_key'] = '4e6fbcefc6c1ef2096c0b95fc1c14568';
		$info['duanxin_tpl_id'] = '115380';
		$info['duanxin_tpl_value'] = '#code#=';
		return $info;
    }
	
	//临时获取积分
	 public function addjifen($uid,$jifen,$type) {
		 //type  1:听力考试；2:
		$user= Db::name('huiyuan')->where('id',$uid)->find();
		
		switch($type){
			case 1:
			$beishu=10;
			break;  
			case 2:
			$beishu=10;
			break;
			default:
			$beishu=1;
		}
		
		
		$integral = $user['integral']+$jifen*$beishu;
		Db::name('huiyuan')->where('id',$uid)->update(array('integral'=>$integral));
		$info['type'] = $type;
		
		
		
		return $info;
    }
	
	
	
	/**试卷相关
	*
	* 获取试卷列表 selectTest
	* 获取试卷详情 selectTestinfo
	* 获取题库列表 selectALLtiku
	* 获取题库详情 findTimuinfo
	* 处理提交的试卷 tijiaoShijuan
	* 获取答题记录 getdatijilu
	*/
	//获取试卷列表
    public function selectTest(){
        $list= Db::name('test')->limit(50)->select();
        return $list;
    }
	//获取试卷列表
    public function my_selectTest($uid,$grade,$type = 1){
		// $list0= Db::name('test')->where(array('grade'=>$grade,'type'=>$type))->order('id asc')->limit(50)->select();
        $list0= Db::name('test')->where(array('grade'=>$grade,'type'=>$type))->order('id asc')->limit(30)->select();
		$gradeinfo= Db::name('nianji')->where(array('id'=>$grade))->find();
		$numall = count($list0);
		$num = 0;
		$list = array();
		$xu = 0;
		foreach($list0 as $key => $vo){
			$info0= Db::name('achievement')->where(array('uid'=>$uid,'test_id'=>$vo['id']))->order('id desc')->find();
			$list[] = $vo;
			
			if($info0){
				$num++;
				$list[$key]['show'] = 1;
				$list[$key]['score'] = intval($info0['score']);
				$list[$key]['jilu_id'] = intval($info0['id']);
			}else{
				$xu++;
				if($xu==1){
					$list[$key]['show'] = 1;
				}else{
					$list[$key]['show'] = 0;
				}
				$list[$key]['score'] = -1;
				$list[$key]['jilu_id'] = -1;
			}
			
			//临时展示其他封锁
			// if($key==0){
				// $list[$key]['show'] = 1;
			// }else{
				// $list[$key]['show'] = 0;
			// }
		}
		
		$studynum = intval((time() - strtotime("2018-12-5"))/1000);//基础数值
		if($type==1){
			$info ['name'] = '实战演练';
		}else{
			$info ['name'] = '名师测评';
		}
		$slideitem = $this->slide_item_info(4);
		
		$info ['content'] = '标准的英语发音，高清的音质效果';
		$info ['imgurl'] = 'http://dpl.zoyomei.com/static/images/listen.png';
		$info ['grade'] = $gradeinfo['n_name'];
		$info ['studynum'] = $studynum+$slideitem['num'];
		$info ['num'] = $num;
		$info ['numall'] = $numall;
		$data['info'] = $info;
		$data['list'] = $list;
        return $data;
    }
	
	//获取试卷详情
    public function selectTestinfo($id){
        $info= Db::name('test')->where('id',$id)->find();
		if(!$info){
			$message ['code'] = 0;
			$message ['msg'] = '未找到试卷';
		}else{
			$data = json_decode($info['hearingone'],true);
			if(!$data){
				$message ['code'] = 0;
				$message ['msg'] = '未找到题目';
			}else{
				// foreach($data['a'] as $ka => $a){
					// $data['a'][$ka] = intval($a);
				// }
				// foreach($data['b'] as $kb => $b){
					// $data['b'][$kb] = intval($b);
				// }
				// foreach($data['c'] as $kc => $c){
					// $data['c'][$kc] = intval($c);
				// }
				// foreach($data['d'] as $kd => $d){
					// $data['d'][$kd] = intval($d);
				// }
				// foreach($data['e'] as $ke => $e){
					// $data['e'][$ke] = intval($e);
				// }
				//排序  没排序就给个排序
				
				if(empty($info['sort'])){
					$data1=  array_merge($data['a'],$data['b'],$data['c'],$data['d'],$data['e']);
					
					$tr = [];
					foreach ($data1 as $v){
						 $list = Db::name('hearingone2')->where('q1_id',$v)->select();
						 $tr[]= json_decode(json_encode($list),true);
					}
					
					$data2=array();
					foreach($tr as $votr ){
						$data2=array_merge($data2,$votr);
					}
					foreach($data2 as $kdis => $val){
						$doid[$kdis] = $val['id'];
					}
				
					array_multisort($doid,SORT_ASC,$data2);

					// $data2=  array_merge($tr[0],$tr[1],$tr[2],$tr[3],$tr[4],$tr[5],$tr[6],$tr[7],$tr[8],$tr[9],$tr[10],$tr[11],$tr[12],$tr[13]);
// var_dump($data2);die;
					foreach($data2 as $k => $vo){
						$data3[$vo['id']] = $k+1;
					}
					
					$save['sort'] = json_encode($data3);
					// var_dump($id);var_dump($save);
					// var_dump(Db::name('test')->where(array('id'=>$id))->update($save));die;
					Db::name('test')->where(array('id'=>$id))->update($save);
					
					$sort = $data3;
				}else{
					$sort = json_decode($info['sort'],true);
				}
				// var_dump($sort);die;
				$sortarr = array();
				$sortarr2 = array();
				//
				$i = 0;
				foreach($sort as $sk => $svo){
					$sortarr[$i]['sort'] = intval($svo);
					$sortarr[$i]['id'] = $sk;
					$i++;
				}
				$sortarr = srl_array_sort($sortarr,'sort','asc');
				$i = 0;
				$datanew = array();
				$dataall['a'] = array();
				$dataall['b'] = array();
				$dataall['c'] = array();
				$dataall['d'] = array();
				$dataall['e'] = array();
				foreach($sortarr as $sk1 => $svo1){
					$sortarr2[$i]['sort'] = $svo1['sort'];
					$sortarr2[$i]['id'] = $svo1['id'];
					// $hearingone2= Db::name('hearingone2')->where('id',$svo1['id'])->find();
					$hearingone2 = Db::name('hearingone2')
					   ->field('b.*,a.type')
					   ->alias('b')
					   ->join('hearingone1 a ','b.q1_id=a.id ' )
					   ->where('b.id',$svo1['id'])
					   ->find();
					// $sortarr2[$i]['q1_id'] = $hearingone2['q1_id'];
					if(!in_array(intval($hearingone2['q1_id']),$datanew)){
						
						array_push($datanew,intval($hearingone2['q1_id']));
						switch($hearingone2['type']){
							case 1:
								array_push($dataall['a'],intval($hearingone2['q1_id']));
								break;
							case 2:
								array_push($dataall['b'],intval($hearingone2['q1_id']));
								break;
							case 3:
								array_push($dataall['c'],intval($hearingone2['q1_id']));
								break;
							case 4:
								array_push($dataall['d'],intval($hearingone2['q1_id']));
								break;
							default:
								array_push($dataall['e'],intval($hearingone2['q1_id']));
							   break;
						}
					}
					
					$i++;
				}
				// $datanew = array_unique(json_decode(json_encode($datanew)));
				
				
				$save2['hearingone'] = json_encode($dataall);
				if($info['hearingone']!=$save2['hearingone']){
					Db::name('test')->where(array('id'=>$id))->update($save2);
				}
					
				$info['tiku'] = $dataall;
				// $info['tiku'] = $data;
				
				$info['tiku_old'] = $data;	//排序后的id
				$info['datanew'] = $datanew;	//排序后的id
				$info['sort'] = $sortarr2;		//排序后的id和
				$message ['code'] = 1;
				$message ['data'] = $info;
			}
		}
		
        return $message;
    }
	//获取随机试卷详情
    public function selectTestinfo_suiji($type){
		
		$data['a'] = [];
		$data['b'] = [];
		$data['c'] = [];
		$data['d'] = [];
		$data['e'] = [];
		
		
		$sql = "SELECT a.* FROM cmf_hearingone1 AS a LEFT JOIN cmf_hearingone2 as b on a.id=b.q1_id WHERE a.type= 1 AND  length(b.questiona_img)>5 ORDER BY  RAND() LIMIT 4";
		$type1 = Db::query($sql);
		foreach($type1 as $k1=>$v1){
			$data['a'][] = $v1['id'];
		}
		

				// var_dump($data);die;
		//题型1后6题
		$sql = "select a.* from cmf_hearingone1 as a LEFT JOIN cmf_hearingone2 as b on a.id=b.q1_id where a.type= 1 and  (length(b.questiona_img) <=2 or b.questiona_img is null ) ORDER BY  RAND() LIMIT 6";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['a'][] = $v['id'];
		}
		
		
		//第二题 第一题型
		$sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 2 ORDER BY  RAND() LIMIT 1";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['b'][] = $v['id'];
		}
	
		//第二题 第二题型
		$sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 3 ORDER BY  RAND() LIMIT 1 ";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['b'][] = $v['id'];
		}
		
		
		//第二题 第三题型
		$sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 5 ORDER BY  RAND() LIMIT 1 ";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['b'][] = $v['id'];
		}

		$sql = "select * from cmf_hearingone1 where type = 3 ORDER BY  RAND() LIMIT 1 ";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['c'][] = $v['id'];
		}
		
		// var_dump($data);die;
		$sql = "select * from cmf_hearingone1 where type = 4 ORDER BY  RAND() LIMIT 1 ";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['d'][] = $v['id'];
		}
		$sql = "select * from cmf_hearingone1 where type = 5 ORDER BY  RAND() LIMIT 1 ";
		$type = Db::query($sql);
		foreach($type as $k=>$v){
			$data['e'][] = $v['id'];
		}
		
		// $sql = "SELECT a.* FROM cmf_hearingone1 AS a LEFT JOIN cmf_hearingone2 as b on a.id=b.q1_id WHERE a.type= 1 AND  b.questiona = 0 AND  b.questionb = 0 AND  b.questionc = 0 ";
		// $type1 = Db::query($sql);
		// $count1 = count($type1);
		// $num1 = range(0,$count1-1);//利用range()函数产生一个0到10的数组
		// shuffle($num1);
		// foreach($num1 as $k1=>$v1){
			// if(count($data['a'])<=3){
				// if(!in_array($type1[$v1]['id'],$data['a'])){
					// $data['a'][] = $type1[$v1]['id'];
				// }
			// }
		// }
				// var_dump($data);die;
		////题型1后6题
		// $sql = "select a.* from cmf_hearingone1 as a LEFT JOIN cmf_hearingone2 as b on a.id=b.q1_id where a.type= 1 and  b.questiona != 0 ";
		// $type = Db::query($sql);
		
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if(count($data['a'])<=9){
				// if(!in_array($type[$v]['id'],$data['a'])){
					// $data['a'][] = $type[$v]['id'];
				// }
			// }
		// }
		
		////第二题 第一题型
		// $sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 2 ";
		// $type = Db::query($sql);
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['b'][] = $type[$v]['id'];
			// }
		// }
		////第二题 第二题型
		// $sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 3 ";
		// $type = Db::query($sql);
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['b'][] = $type[$v]['id'];
			// }
		// }
		////第二题 第三题型
		// $sql = "select * from (select *,  (select count(*) from  cmf_hearingone2 where q1_id  = cmf_hearingone1.id) as tmnum from cmf_hearingone1 where type= 2) as a  where a.tmnum = 5 ";
		// $type = Db::query($sql);
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['b'][] = $type[$v]['id'];
			// }
		// }

		// $type= Db::name('hearingone1')->where('type',3)->select();
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['c'][] = $type[$v]['id'];
			// }
		// }
		// $type= Db::name('hearingone1')->where('type',4)->select();
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['d'][] = $type[$v]['id'];
			// }
		// }
		// $type= Db::name('hearingone1')->where('type',5)->select();
		// $count = count($type);
		// $num = range(0,$count-1);//利用range()函数产生一个0到10的数组
		// shuffle($num);
		// foreach($num as $k=>$v){
			// if($k==0){
				// $data['e'][] = $type[$v]['id'];
			// }
		// }
		
		//生成随机试卷
		$data1=  array_merge($data['a'],$data['b'],$data['c'],$data['d'],$data['e']);
					
		$tr = [];
		foreach ($data1 as $v){
			 $list = Db::name('hearingone2')->where('q1_id',$v)->select();
			 $tr[]= json_decode(json_encode($list),true);
		}
		
		$data2=array();
		foreach($tr as $votr ){
			$data2=array_merge($data2,$votr);
		}

		// $data2=  array_merge($tr[0],$tr[1],$tr[2],$tr[3],$tr[4],$tr[5],$tr[6],$tr[7],$tr[8],$tr[9],$tr[10],$tr[11],$tr[12],$tr[13]);

		foreach($data2 as $knew => $vonew){
			$data3[$vonew['id']] = $knew+1;
			// var_dump($knew);
		}
		// var_dump($data2);die;
		$save['hearingone'] = json_encode($data);
		$save['sort'] = json_encode($data3);
		$save['time'] = date('Y-m-d H:i:s');
		$save['title'] = date('YmdHis').'随机测评';
		$testid = Db::name('test_random')->insertGetId($save);
		// var_dump($id);var_dump($save);
		// var_dump(Db::name('test')->where(array('id'=>$id))->update($save));die;
		// Db::name('test')->where(array('id'=>$id))->update($save);
		
		// $sort = $data3;
		$info['title'] = '随机测评';
		$info['tiku'] = $data;
		$info['testid'] = $testid;
        return $info;
    }
	
	//获取题库列表
    public function selectALLtiku(){
		$tikulist= Db::name('hearingone1')->select();
		// $data = array('a'=>'','b'=>'','c'=>'','d'=>'','e'=>'');
		foreach($tikulist as $key => $vo){
			if($vo['type']==1){
				$data['a'][] = intval($vo['id']);
			}
			if($vo['type']==2){
				$data['b'][] = intval($vo['id']);
			}
			if($vo['type']==3){
				$data['c'][] = intval($vo['id']);
			}
			if($vo['type']==4){
				$data['d'][] = intval($vo['id']);
			}
			if($vo['type']==5){
				$data['e'][] = intval($vo['id']);
			}
		}

        return $data;

    }
	
	//获取题库详情
    public function findTimuinfo($id,$type,$test_id,$test_type = 0){
		if($test_id){
			if($test_type == 1){
				$testinfo= Db::name('test_random')->where('id',$test_id)->find();//试卷
				$sort = json_decode($testinfo['sort'],true);	//题号
			}else{
				$testinfo= Db::name('test')->where('id',$test_id)->find();//试卷
				$sort = json_decode($testinfo['sort'],true);	//题号
			}
			
			if(empty($sort)){  //临时加的，之后去掉 如果没找着，在重新在随机卷找下
				$testinfo= Db::name('test_random')->where('id',$test_id)->find();//试卷
				$sort = json_decode($testinfo['sort'],true);	//题号
			}
			
		}
		
		$info= Db::name('hearingone1')->where('id',$id)->find();
		
		
		$info['url'] = cmf_get_image_preview_url($info['url']);
		$info['image'] = cmf_get_image_preview_url($info['image']);
		$list2= Db::name('hearingone2')->where('q1_id',$id)->select();
		$list = array();
		foreach($list2 as $key => $vo){
			$list[$key] = $vo;
			$list[$key]['xuhao'] = 0;  //序号
			if($test_id){
				foreach($sort as $sk => $svo){
					if(intval($sk)==intval($vo['id'])){
						$list[$key]['xuhao'] = intval($svo);
						break;
					}
				}
			}
			if($list[$key]['xuhao'] > 0){
				if($list[$key]['xuhao']==13||$list[$key]['xuhao']==14||$list[$key]['xuhao']==15){
					$list[$key]['title'] = $list[$key]['xuhao'].'. ';
				}else{
					$list[$key]['title'] = $list[$key]['xuhao'].'. '. $list[$key]['title'];
				}
			}
			
			$list[$key]['url'] = cmf_get_image_preview_url($vo['url']);
			$list[$key]['type'] = $type;
			$list[$key]['A'] = $vo['questiona'];
			$list[$key]['B'] = $vo['questionb'];
			$list[$key]['C'] = $vo['questionc'];
			$list[$key]['D'] = $vo['questiond'];
			$img = json_decode($vo['questiona_img']);
			if(!empty($img[0])){$list[$key]['Aimg'] = cmf_get_image_url($img[0]);}else{$list[$key]['Aimg'] = '';}
			if(!empty($img[1])){$list[$key]['Bimg'] = cmf_get_image_url($img[1]);}else{$list[$key]['Bimg'] = '';}
			if(!empty($img[2])){$list[$key]['Cimg'] = cmf_get_image_url($img[2]);}else{$list[$key]['Cimg'] = '';}
			if(!empty($img[3])){$list[$key]['Dimg'] = cmf_get_image_url($img[3]);}else{$list[$key]['Dimg'] = '';}
			$list[$key]['answer'] = array('A'=>$vo['questiona'],'B'=>$vo['questionb'],'C'=>$vo['questionc'],'D'=>$vo['questiond']); 
			// $answer[]['title'] =$vo['questiona'];
			// $answer[]['title'] =$vo['questionb'] ;
			// $answer[]['title'] =$vo['questionc'];
			// $answer[]['title'] =$vo['questiond'];
			// $list[$key]['answer'] = $answer ;
			// $list[$key]['answer']['A']= $vo['questiona'];
			// $list[$key]['answer']['B']= $vo['questionb'];
			// $list[$key]['answer']['C']= $vo['questionc'];
			// $list[$key]['answer']['D']= $vo['questiond'];
			if($vo['questiona'] == ''){
				$vo['questiona'] = 0;
			}
			if($vo['questionb'] == ''){
				$vo['questionb'] = 0;
			}
			if($vo['questionc'] == ''){
				$vo['questionc'] = 0;
			}
			$answer[0]['ans'] =$vo['questiona'];
			$answer[1]['ans'] =$vo['questionb'] ;
			$answer[2]['ans'] =$vo['questionc'];
			if(!empty($vo['questiond']))$answer[3]['ans'] =$vo['questiond'];
			
			$answer[0]['ansimg'] =$list[$key]['Aimg'];
			$answer[1]['ansimg'] =$list[$key]['Bimg'] ;
			$answer[2]['ansimg'] =$list[$key]['Cimg'];
			if(!empty($list[$key]['Dimg']))$answer[3]['ansimg'] =$list[$key]['Dimg'];
			
			$answer[0]['mark'] = 'A';
			$answer[1]['mark'] = 'B';
			$answer[2]['mark'] = 'C';
			if(!empty($list[$key]['Dimg'])||!empty($vo['questiond']))$answer[3]['mark'] = 'D';
			
			// var_dump($answer);die;
			$list[$key]['answer'] = $answer ;
			
			// $list[$key]['answerimg'] = array(0=>$list[$key]['Aimg'],1=>$list[$key]['Bimg'],2=>$list[$key]['Cimg'],3=>$list[$key]['Dimg']); 
		}
		$info['timu'] = $list;
		$info['timu'] = $list;
		// dump($info);die;
        return $info;

    }
	
	//处理提交的试卷
    public function tijiaoShijuan($uid,$test_id,$shuju,$test_type = 0){
		//增加访问量 江苏中考听力口语模拟训练
		$this->slide_addnum(4);
		$user= Db::name('huiyuan')->where('id',$uid)->find();
		
		if($test_type == 1){
				$test= Db::name('test_random')->where('id',$test_id)->find();
			}else{
				$test= Db::name('test')->where('id',$test_id)->find();
			}
		
		// $list2= Db::name('hearingone2')->where('q1_id',$id)->select();
		// $list = array();
		// var_dump($test_id);var_dump($shuju);die;
		
		
		$data2 = array('test'=> $test['title'],'test_id'=> $test_id,'username'=> $user['username'],'uid'=> $uid,'time'=> time(),'test_type'=>$test_type);
		// Db::name('achievement')->insert($data2);
		$kaoshijiluid = Db::name('achievement')->insertGetId($data2);
		
		$num1 = 0;
		$num2 = 0;
		$num3 = 0;
		$num4 = 0;
		$num5 = 0;
		$shuju = json_decode($shuju);
		
		$tuku =array();
		$i = 0;
		foreach($shuju as $key => $vo){
			
			$one2 = Db::name('hearingone2')
               ->field('b.*,a.type')
               ->alias('b')
               ->join('hearingone1 a ','b.q1_id=a.id ' )
               ->where('b.id',$key)
               ->find();
			   // var_dump($key);var_dump($one2);die;
			   // var_dump($one2);
			if($one2['type']==1){
				$timu['a'][] = $key;
				$timud['a'][] = $one2['q1_id'];
			}
			if($one2['type']==2){
				$timu['b'][] = $key;
				$timud['b'][] = $one2['q1_id'];
			}
			if($one2['type']==3){
				$timu['c'][] = $key;
				$timud['c'][] = $one2['q1_id'];
			}
			if($one2['type']==4){
				$timu['d'][] = $key;
				$timud['d'][] = $one2['q1_id'];
			}
			if($one2['type']==5){
				$timu['e'][] = $key;
				$timud['e'][] = $one2['q1_id'];
			}
		}
		// var_dump('111');die;
		$timu = json_encode($timu);
		$timud = json_encode($timud);
		
		
		//显示序号
		$sort = json_decode($test['sort'],true);
		// if($uid == 1){
				
				// var_dump($sort);
			// }
		// var_dump(json_encode($timu));die;
		foreach($shuju as $key => $vo){
			//添加序号
			
			$xuhao = 0;
			foreach($sort as $sk => $svo){
				if($uid == 1){
				
				// var_dump($sk);var_dump($key);die;
			}
				if($sk == intval($key)){
					$xuhao = intval($svo);
					break;
				}
			}
			
			// if($uid == 1){
				
				// var_dump($key);var_dump($xuhao);
			// }
			
			// $one2id = intval($key);
			// $one2= Db::name('hearingone2')->where('id',$key)->find();
			$i++;
			$one2 = Db::name('hearingone2')
               ->field('b.*,a.type')
               ->alias('b')
               ->join('hearingone1 a ','b.q1_id=a.id ' )
               ->where('b.id',$key)
               ->find();
			   
			// var_dump($key);var_dump($one2id);var_dump($one2);die;
			if($one2['type']==1||$one2['type']==2){
				$answer[$key] = 0;
			}
			if($one2['answer']==$vo){
				if($one2['type']==1){
					$answer[$key] = 1;
					$num1++;
				}
				if($one2['type']==2){
					$answer[$key] = 1;
					$num2++;
				}
				if($one2['type']==3){
					$num3++;
					// $vo = $this->urlfile($vo);
				}
				if($one2['type']==4){
					$num4++;
					// $vo = $this->urlfile($vo);
				}
				if($one2['type']==5){
					$num5++;
					// $vo = $this->urlfile($vo);
				}
			}
			
			$data1 = array('test'=> $test['title'],'test_id'=> $test_id,'hearingone_id'=> $key,'hearingone'=> $one2['title'],'xuhao'=>$xuhao,
			'optionone'=> $one2['answer'],'type'=> $one2['type'],'uid'=> $uid,'time'=> time(),'optionchoose'=> $vo,'jilu_id'=> $kaoshijiluid,'test_type'=> $test_type);
			// if($key=='126'){
				   // var_dump($answer);var_dump($shuju);var_dump($one2);die;
			   // }
			$jiluid = Db::name('testjilu')->insertGetId($data1);
			
			if(($one2['type']==1||$one2['type']==2)&&$answer[$key] == 0){
				$where2 = array('test_id'=> $test_id,'hearingone_id'=> $key,'uid'=> $uid);
				$mytimu= Db::name('my_timu')->where($where2)->find();
				if($mytimu){
					Db::name('my_timu')->where($where2)->update(array('jilu_id'=> $jiluid));
				}else{
					$data2 = array('time'=> time(),'test_id'=> $test_id,'hearingone_id'=> $key,'uid'=> $uid,'jilu_id'=> $jiluid);
					Db::name('my_timu')->insert($data2);
				}
				
			}
			
			// var_dump($data1 );die;
			
		}
		

		// $score = ($num1+$num2)+10;
		Db::name('achievement')->where('id',$kaoshijiluid)->update(array('timu'=>$timud));
		
		//添加用户积分
		// var_dump( $user);die;
		// $this->addjifen($uid,$jifen,1);
		$integral = $user['integral']+1;
		Db::name('huiyuan')->where('id',$uid)->update(array('integral'=>$integral));
		
		
		//题目是否正确
		// $hearingonelist = json_decode($timud,true);
		// foreach($hearingonelist['a'] as $ka => $a){
			// $timujilu = $this->findTimuinfo($a,1);
			
			// $jilua[$ka]= Db::name('testjilu')->where(array('hearingone_id'=>$timujilu['timu'][0]['id'],'test_id'=> $test_id,'uid'=> $uid))->order('id desc')->find();
			// if(!empty($jilua[$ka])&&($jilua[$ka]['optionone']==$jilua[$ka]['optionchoose'])){
				// $answer2['a'][] = 1;
			// }else{
				// $answer2['a'][] = 0;
			// }
		// }
		// foreach($hearingonelist['b'] as $kb => $b){
			// $timujilu = $this->findTimuinfo($b,1);
			// foreach($timujilu['timu'] as $kb2 => $b2){
				// $jilub[$kb2]= Db::name('testjilu')->where(array('hearingone_id'=>$b2['id'],'test_id'=> $test_id,'uid'=> $uid))->order('id desc')->find();
				// if(!empty($jilub[$kb2])&&($jilub[$kb2]['optionone']==$jilub[$kb2]['optionchoose'])){
					// $answer2['b'][] = 1;
				// }else{
					// $answer2['b'][] = 0;
				// }
			// }
		// }
		// var_dump($answer2);var_dump($hearingonelist['b']);die;
		
		// var_dump($vo);var_dump($num1);var_dump($num2);var_dump($num3);var_dump($num4);var_dump($num5);die;
		$info['jilu_id'] = $kaoshijiluid;
		// $info['score'] = $score;
		$info['tuxing'][0] = 80;
		$info['tuxing'][1] = 80;
		$info['tuxing'][2] = 80;
		$info['tuxing'][3] = 80;
		$info['tuxing'][4] = 80;
		$info['answer'] = $answer;
		// $info['answer2'] = $answer2;
        return $info;
    }
	
	//获取答题记录
	public function getdatijilu($id){
		$id = intval($id);
		$info= Db::name('achievement')->where('id',$id)->find();
		$list= Db::name('testjilu')->where('jilu_id',$id)->order('xuhao asc')->select();
		// var_dump($id);var_dump($info);var_dump($list);die;
		
		
		//答题人次 
		$allthisid= Db::name('achievement')->where('test_id',$info['test_id'])->order('score desc')->select();
		foreach($allthisid as $key => $vo){
			if($id == $vo['id']){
				$mingci = $key+1;
			}
		}
		// var_dump($allthisid);die;
		$daticount = count($allthisid);
		//本人名次
		
		$fenshuarr = array();
		$answer = array();
		$fenshu = 0;
		array_push($fenshuarr,"$fenshu");
		$num1 = 0;
		$num2 = 0;
		$num3 = 0;
		$num4 = 0;
		$num5 = 0;
		$answer['module1'] = array();
		$answer['module2'] = array();
		$answer['module3'] = array();
		$answer['module4'] = array();
		$answer['module5'] = array();
		$answer00['module1'] = array();
		$answer00['module2'] = array();
		$answer00['module3'] = array();
		$answer00['module4'] = array();
		$answer00['module5'] = array();
		
		foreach($list as $k => $vo){
			
			if(($vo['type']==1||$vo['type']==2)&&($vo['optionone']==$vo['optionchoose'])){
				if($vo['type']==1){
					$answer['module1'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module1'][$k]['score'] = 1;
					$answer['module1'][$k]['id'] = $vo['id'];
				}
				if($vo['type']==2){
					$answer['module2'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module2'][$k]['score'] = 1;
					$answer['module2'][$k]['id'] = $vo['id'];
				}
			}else{
				if($vo['type']==1){
					$answer['module1'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module1'][$k]['score'] = 0;
					$answer['module1'][$k]['id'] = $vo['id'];
				}
				if($vo['type']==2){
					$answer['module2'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module2'][$k]['score'] = 0;
					$answer['module2'][$k]['id'] = $vo['id'];
				}
			}
			
			if($vo['type']==3||$vo['type']==4||$vo['type']==5){
				if($vo['type']==3){
					$answer['module3'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module3'][$k]['score'] = 0;
					$answer['module3'][$k]['id'] = $vo['id'];
				}
				if($vo['type']==4){
					$answer['module4'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module4'][$k]['score'] = 0;
					$answer['module4'][$k]['id'] = $vo['id'];
				}
				if($vo['type']==5){
					$answer['module5'][$k]['xuhao'] = $vo['xuhao'];
					$answer['module5'][$k]['score'] = 0;
					$answer['module5'][$k]['id'] = $vo['id'];
				}
				if($vo['optionchoose']){
					var_dump($vo['optionchoose']);var_dump($vo['optionone']);die;
					@$fenshu = $this->voiceIse('.'.$vo['optionchoose'],$vo['optionone']);
					
					$fenshu = json_decode($fenshu);
					$fenshu = $this->object_to_array($fenshu);
					
					if(empty($fenshu['data'])){
						$fenshu = 0;
					}else{
						$fenshu = $fenshu['data']['read_sentence']['rec_paper']['read_chapter']['total_score'];
					}
					
					if($vo['type']==3){
						$answer['module3'][$k]['score'] = ceil (3*$fenshu/100);
					}
					if($vo['type']==4){
						$answer['module4'][$k]['score'] = round ($fenshu/100);
					}
					if($vo['type']==5){
						$answer['module5'][$k]['score'] = ceil (5*$fenshu/100);
					}
					array_push($fenshuarr,"$fenshu");
				}
			}
		}
		
		foreach($answer['module1'] as $km1 => $vom1){
			$answer00['module1'][] = $vom1;
			$num1 = $vom1['score']+$num1;
		}
		foreach($answer['module2'] as $km2 => $vom2){
			$answer00['module2'][] = $vom2;
			$num2 = $vom2['score']+$num2;
		}
		foreach($answer['module3'] as $km3 => $vom3){
			$answer00['module3'][] = $vom3;
			$num3 = $vom3['score']+$num3;
		}
		foreach($answer['module4'] as $km4 => $vom4){
			$answer00['module4'][] = $vom4;
			$num4 = $vom4['score']+$num4;
		}
		foreach($answer['module5'] as $km5 => $vom5){
			$answer00['module5'][] = $vom5;
			$num5 = $vom5['score']+$num5;
		}
		
		// var_dump($answer);die;
		$fenshu = max($fenshuarr);
		if($fenshu>0){
			$fens = (($fenshu-10)<0)?0:($fenshu-10);
			$fene = (($fenshu+10)>100)?100:($fenshu+10);
			
			// var_dump($fenshu);var_dump($fene);die;
			$one=mt_rand($fens,$fene);
			$two=mt_rand($fens,$fene);
			$three=mt_rand($fens,$fene);
			$four=mt_rand($fens,$fene);
			$data['tuxing']=array((string)intval($fenshu),(string)$one,(string)$two,(string)$three,(string)$four);
		}else{
			$data['tuxing']=array('50','60','70','80','90');
		}
		$score = $num1+$num2+$num3+$num4+$num5;
		Db::name('achievement')->where('id',$id)->update(array('score'=>$score));
		$data['answer'] = $answer00;
		$data['score'] = $score;
		$data['daticount'] = $daticount;
		$data['mingci'] = $mingci;
		// $data['scorenum'] = $num1.$num2.$num3.$num4.$num5;
        return $data;

    }
	
	//获取单条答题记录
	public function getdatijilu_one($id){
		$id = intval($id);
		$info= Db::name('testjilu')->where('id',$id)->find();
		
		$one2= Db::name('hearingone2')->where('id',$info['hearingone_id'])->find();
		$data= $this->findTimuinfo($one2['q1_id'],1,$info['test_id'],$info['test_type']);//题目
		foreach($data['timu'] as $key => $vo){
			$info2[$key]= Db::name('testjilu')->where(array('hearingone_id'=>$vo['id'],'jilu_id'=>$info['jilu_id']))->find();
			
			
			$vonew['id'] = $vo['id'];
			$vonew['q1_id'] = $vo['q1_id'];
			$vonew['title'] = $vo['title'];
			$vonew['url'] = $vo['url'];
			$vonew['answer'] = $vo['answer'];
			$vonew['duanwen'] = $vo['duanwen'];
			$vonew['rightanswer'] = $info2[$key]['optionone'];	//答案
			$vonew['choose'] = $info2[$key]['optionchoose'];	//用户选择
			$vonew['returncontent'] = '';
			
			if($info2[$key]['type']==3){
				
				// if($info2[$key]['type']==3||$info2[$key]['type']==4||$info2[$key]['type']==5){
				$vonew['rightanswer'] = mb_substr($vonew['rightanswer'],0,180);
				// var_dump($vonew['rightanswer']);die;
				if($vonew['choose']){
					$fenshu = $this->voiceIse('.'.$vonew['choose'],$vonew['rightanswer']);
					$fenshu = json_decode($fenshu);
					$fenshu = $this->object_to_array($fenshu);
					// $vonew['fenshu'] = $fenshu;	//用户选择
					// $vonew['total_score'] = $fenshu['data']['read_sentence']['rec_paper']['read_chapter']['total_score'];
					$sentence = $fenshu['data']['read_sentence']['rec_paper']['read_chapter']['sentence'];
					
					var_dump($sentence );die;
					foreach($sentence as $keys=>$vos){
						foreach($vos['word'] as $keyw=>$vow){
							if(count($vow)>3){
								$re['wordcon'] = $vow['content'];
								$re['wordsco'] = $vow['total_score'];
								$word[] = $re;
							}
						}
					}
					// $vonew['word'] = $word;	
					$returncontent = "<p style='font-size:250%'>";
					foreach($word as $kw=>$vw){
						if($vw['wordsco']>40){
							$returncontent .= "<span style='color:#00A0EA'>".$vw['wordcon']."</span> ";
						}elseif($vw['wordsco']>20){
							$returncontent .= "<span style='color:#f39800'>".$vw['wordcon']."</span> ";
						}else{
							$returncontent .= "<span style='color:#e60012'>".$vw['wordcon']."</span> ";
						}
					}
					$returncontent .= "</p>";
					$vonew['returncontent'] = $returncontent;
					// if(empty($fenshu['data'])){
						// $fenshu = 0;
					// }else{
						// $fenshu = $fenshu['data']['read_sentence']['rec_paper']['read_chapter']['total_score'];
					// }
					
					// if($vo['type']==3){
						// $answer['module3'][$k]['score'] = ceil (3*$fenshu/100);
						// $answer['module3'][$k]['id'] = $vo['id'];
					// }
					// if($vo['type']==4){
						// $answer['module4'][$k]['score'] = round ($fenshu/100);
						// $answer['module4'][$k]['id'] = $vo['id'];
					// }
					// if($vo['type']==5){
						// $answer['module5'][$k]['score'] = ceil (5*$fenshu/100);
						// $answer['module5'][$k]['id'] = $vo['id'];
					// }
					// array_push($fenshuarr,"$fenshu");
				}
			}
			if($info2[$key]['type']==3||$info2[$key]['type']==4||$info2[$key]['type']==5){
				$vonew['choose'] = cmf_get_image_url($vonew['choose']);
			}
			
			$timu[] = $vonew;
			
		}
		$data1['id'] = $data['id'];
		$data1['type'] = $data['type'];
		$data1['url'] = $data['url'];
		$data1['title'] = $data['title'];
		$data1['timu'] = $timu;
		
		return $data1;
	}
	
	//获取我的错题记录
	public function getmydatijilu($uid,$start,$limit){
		$list= Db::name('my_timu')->where(array('uid'=>$uid,'type'=>1))->order('id desc')->limit($start,$limit)->select();
		$arr = array();
		foreach($list as $key => $vo){
			$test= Db::name('test')->where('id',$vo['test_id'])->find();
			$one2= Db::name('hearingone2')->where('id',$vo['hearingone_id'])->find();
			$vo['test_name'] = $test['title'];
			$vo['timu_name'] = $one2['title'];
			$arr[] = $vo;
		}
		return $arr;
	}
	//我的错题状态删除
	public function my_error_timu_update($id,$type = 0){
		$save = Db::name('my_timu')->where('id',$id)->update(array('type'=>$type));
		return $save ;
	}
	
	
	
	/**存文件
	*
	* 录音 urlfile
	* 图片 imgfile
	*/
	//录音存入文件夹
	public function urlfile($file){
		$imgtype = substr ( strrchr ( $file['file']['name'], '.' ), 1 );
		// if($imgtype=='pcm'||$imgtype=='wav'){
			// $name = rand( 6, 0 ) . time().'.' . $imgtype;
		// }
		// else{
			$name = rand( 6, 0 ) . time().'.wav';
		// }
		// $name = rand( 6, 0 ) . time().'.' . $imgtype;
		// $name = rand( 6, 0 ) . time().'.wav';
		$date = date('Ymd',time()).'/';
		$dir = iconv("UTF-8", "GBK", './upload/luyin/'.$date);	//生成文件夹
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        } 
	
		$ok =move_uploaded_file($file['file']['tmp_name'], './upload/luyin/'.$date.$name );

		$url ='/upload/luyin/'.$date.$name;
        return $url;

    }
	
	//图片存入文件夹
	public function imgfile($file,$wenjian){
		$imgtype = substr ( strrchr ( $file['file']['name'], '.' ), 1 );

		$name = rand( 6, 0 ) . time().'.' . $imgtype;
		
		$date = date('Ymd',time()).'/';
		$dir = iconv("UTF-8", "GBK", './upload/'.$wenjian.'/'.$date);	//生成文件夹
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        } 
		$ok =move_uploaded_file($file['file']['tmp_name'], './upload/'.$wenjian.'/'.$date.$name );

		$url ='./upload/'.$wenjian.'/'.$date.$name;
        return $url;

    }
	
	
	/**讯飞测评
	*
	* 语音听写 voiceIat
	* 语音测评 voiceIse
	* 手写识别 voiceHw
	*/
	//语音听写
	public function voiceIat($file_path){
		$jichu=$this->jichu();
		$param = [
			// 'engine_type' => 'sms16k',
			'engine_type' => 'sms-en16k',
			'aue' => 'raw'
		];
		$cur_time = (string)time();
		$x_param = base64_encode(json_encode($param));
		$header_data = [
			'X-Appid:'.$jichu['APP_ID'],
			'X-CurTime:'.$cur_time,
			'X-Param:'.$x_param,
			'X-CheckSum:'.md5($jichu['APP_KEY_IAT'].$cur_time.$x_param),
			'Content-Type:application/x-www-form-urlencoded; charset=utf-8'
		];

		//Body
		// $file_path = './upload/luyin/20181107/cepingpt.wav';
		// $file_path = './upload/luyin/20181107/ceping001.wav';
		$file_content = file_get_contents($file_path);
		$body_data = 'audio='.urlencode(base64_encode($file_content));
// var_dump($file_path);var_dump($body_data);die;
		//Request
		$url = "http://api.xfyun.cn/v1/service/v1/iat";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body_data);
		$result = curl_exec($ch);
		curl_close($ch);
		// $info= $this->CepingModel->ceshiinserf($result);
// var_dump($result);die;
		return $result;
	}
	
	//语音测评
	public function voiceIse($file_path,$content){
		$jichu=$this->jichu();
		$param = [
			'language' => 'en_us',
			'aue' => 'raw',
			'category' => 'read_sentence'
		];
		$cur_time = (string)time();
		$x_param = base64_encode(json_encode($param));
		$header_data = [
			'X-Appid:'.$jichu['APP_ID'],
			'X-CurTime:'.$cur_time,
			'X-Param:'.$x_param,
			'X-CheckSum:'.md5($jichu['APP_KEY_ISE'].$cur_time.$x_param),
			'Content-Type:application/x-www-form-urlencoded; charset=utf-8'
		];
// var_dump($file_path);var_dump($content);die;
		//Body
		$file_path = $file_path;
		// $content = 'UNICEF believes that all children should have clean water and food so that they can be healthy.';
		// $file_path = './upload/luyin/20181107/cepingyy.wav';
		$file_content = file_get_contents($file_path);
		$body_data = 'audio='.urlencode(base64_encode($file_content)).'&text='.urlencode($content);
		// var_dump($file_content);var_dump($body_data);die;
		$url = "http://api.xfyun.cn/v1/service/v1/ise";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body_data);

		$result = curl_exec($ch);
		curl_close($ch);
		// $info= $this->CepingModel->ceshiinserf($result);
// var_dump($result);die;
		return $result;
	}
	
	//语音合成
	public function voiceTts($content, $output_path){
		
		$jichu=$this->jichu();
		$param = [
			'engine_type' => 'intp65',
			'auf' => 'audio/L16;rate=16000',
			'aue' => 'lame',
			'voice_name' => 'xiaoyan',
			'speed' => '50',
			'engine_type' => 'intp65_en',
		];
		$cur_time = (string)time();
		$x_param = base64_encode(json_encode($param));
		$header_data = [
			'X-Appid:'.$jichu['APP_ID'],
			'X-CurTime:'.$cur_time,
			'X-Param:'.$x_param,
			'X-CheckSum:'.md5($jichu['APP_KEY_TTS'].$cur_time.$x_param),
			'Content-Type:application/x-www-form-urlencoded; charset=utf-8'
		];

		//Body
		$body_data = 'text='.urlencode($content);

		//Request
		$url = "http://api.xfyun.cn/v1/service/v1/tts";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body_data);
		$result = curl_exec($ch);

		$res_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$res_header = substr($result, 0, $res_header_size);
		curl_close($ch);

		if(stripos($res_header, 'Content-Type: audio/mpeg') === FALSE){ //合成错误
			return substr($result, $res_header_size);
		}else{
			file_put_contents($output_path, substr($result, $res_header_size));
			return '语音合成成功！';
		}
	}
	
	//手写识别
	public function voiceHw($file_path){
		$jichu=$this->jichu();
		
		$param = [
			// 'engine_type' => 'sms16k',
			'language' => 'cn|en',
			'location' => 'true'
		];
		$cur_time = (string)time();
		$x_param = base64_encode(json_encode($param));
		$header_data = [
			'X-Appid:'.$jichu['APP_ID'],
			'X-CurTime:'.$cur_time,
			'X-Param:'.$x_param,
			'X-CheckSum:'.md5($jichu['APP_KEY_HW'].$cur_time.$x_param),
			'Content-Type:application/x-www-form-urlencoded; charset=utf-8'
		];

		//Body
		// $file_path = './upload/luyin/20181107/cepingpt.wav';
		// var_dump($header_data);var_dump($file_path);die;
		$file_content = file_get_contents($file_path);
		$body_data = 'image='.urlencode(base64_encode($file_content));
// var_dump($file_path);var_dump($body_data);die;
		//Request
		$url = "http://webapi.xfyun.cn/v1/service/v1/ocr/handwriting";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body_data);
		$result = curl_exec($ch);
		curl_close($ch);
		$info= $this->ceshiinserf($result);
// var_dump($result);die;
		return $result;
	}
	
	/**公用方法
	*
	* 对象转数组 object_to_array
	* 通用提交 juhecurl
	*/
	//对象转数组
	function object_to_array($obj) {
		$obj = (array)$obj;
		foreach ($obj as $k => $v) {
			if (gettype($v) == 'resource') {
				return;
			}
			if (gettype($v) == 'object' || gettype($v) == 'array') {
				$obj[$k] = (array)$this->object_to_array($v);
			}
		}
		return $obj;
	}
	
	//通用提交
	function juhecurl($url, $params = false, $ispost = 0) {
		$httpInfo = array ();
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		if ($ispost) {
			curl_setopt ( $ch, CURLOPT_POST, true );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
			curl_setopt ( $ch, CURLOPT_URL, $url );
		} else {
			if ($params) {
				curl_setopt ( $ch, CURLOPT_URL, $url . '?' . $params );
			} else {
				curl_setopt ( $ch, CURLOPT_URL, $url );
			}
		}
		$response = curl_exec ( $ch );
		if ($response === FALSE) { // echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		$httpInfo = array_merge ( $httpInfo, curl_getinfo ( $ch ) );
		curl_close ( $ch );
		return $response;
	}
	
	
	/**短信验证
	*
	* 添加短信验证码 sendinserf
	* 短信验证码验证 sendVerify
	*/
	//短信验证码写入数据库
	public function sendinserf($mobile,$code){
		// var_dump($data);die;
		$data2 = array('mobile'=> $mobile,'code'=> $code,'time'=> time());
		$url = Db::name('sendsms')->insert($data2);
        return $url;
    }
	//短信验证码验证
	public function sendVerify($mobile,$code){
		//测试代码
		// $info= Db::name('sendsms')->where(array('mobile'=>$mobile))->order('time desc')->find();
	
		// $dete = date('Y-m-d',time());
		// if($code==$info['code']&&$dete==date('Y-m-d',$info['time'])){
			// return 1;
		// }else{
			// return 0;
		// }
		//正式
		$info= Db::name('sendsms')->where(array('mobile'=>$mobile,'status'=>0))->order('time desc')->find();
	
		$dete = time()-3600;
		if($code==$info['code']&&$info['time']>$dete){
			Db::name('sendsms')->where('mobile',$mobile)->update(array('status'=>1));
			return 1;
		}else{
			return 0;
		}


    }
	
	//发送短信   错误 以后删除
	public function sendsms($key, $mobile, $tpl_id, $tpl_value) {
		// var_dump(APP_PATH);die;

		require APP_PATH . '/admin/model/aliyun_dysms_sdk/api_demo/SmsDemo.php';
		// $SmsDemofdfd = new aliyun_dysms_sdk\api_demo\SmsDemo();
		
		// $message=aliyun_dysms_sdk\api_demo\SmsDemo::sendSms ( $con ); // 请求发送短信
		// $SmsDemo = new aliyun_dysms_sdk\api_demo\SmsDemo();
		$con['mobile'] = $mobile;
		$con['code'] ='SMS_151625120';
		$con['rand'] = $tpl_value;
		$message=SmsDemo::sendSms ( $con ); // 请求发送短信
		var_dump('111');die;
		
		
		// $message=$this->SmsDemo->sendSms ( $con ); // 请求发送短信

		$message=SmsDemo::sendSms ( $con ); // 请求发送短信
		
		return $message;
	}
	
	//发送短信
	public function sendsms_old($key, $mobile, $tpl_id, $tpl_value) {
		$jichu=$this->jichu();
		$key = $jichu['duanxin_key'];
		$mobile = $mobile;
		$tpl_id = $jichu['duanxin_tpl_id'];
		$tpl_value = $jichu['duanxin_tpl_value'].$tpl_value;

		// var_dump($key);die;
		
		$sendUrl = 'http://v.juhe.cn/sms/send'; // 短信接口的URL
		$message [] = array ();
		$smsConf = array (
				'key' => $key, // 您申请的APPKEY
				'mobile' => $mobile, // 接受短信的用户手机号码
				'tpl_id' => $tpl_id, // 您申请的短信模板ID，根据实际情况修改
				'tpl_value' => $tpl_value 
		); // 您设置的模板变量，根据实际情况修改
		
		$content = $this->juhecurl ( $sendUrl, $smsConf, 1 ); // 请求发送短信
		
		if ($content) {
			$result = json_decode ( $content, true );
			$error_code = $result ['error_code'];
			if ($error_code == 0) {
				$message ['code'] = 1;
				// 状态为0，说明短信发送成功
				// echo "短信发送成功,短信ID：".$result['result']['sid'];
				
				$message ['msg'] = $result ['result'] ['sid'];
			} else {
				// 状态非0，说明失败
				$message ['code'] = 0;
				$msg = $result ['reason'];
				$message ['msg'] = $msg;
				
				// echo "短信发送失败(".$error_code.")：".$msg;
			}
		} else {
			// 返回内容异常，以下可根据业务逻辑自行修改
			// echo "请求发送短信失败";
			$message ['code'] = 0;
			
			$message ['msg'] = '请求发送短信失败';
		}
		// var_dump($message);die;
		return $message;
	}
	
	/**默写相关
	* 读取图片 readimg
	* 默写列表 write_silently_list
	* 
	*/
	public function readimg($file_path){
		// var_dump($file_path);die;
		// $file_path = './upload/luyin/1111.jpg';
		$return = $this->voiceHw($file_path);
		// var_dump($return);die;
		$list = $this->object_to_array(json_decode($return));
		$line =$list['data']['block'][0]['line'];
		
		if(empty($line)){
			$data ['code'] =0;
			$data ['msg'] ='未识别到数据';
		}else{
			// var_dump('111');die;
			for($i=0;$i<count($line);$i++){
				$shuju[$i] = '';
				for($j=0;$j<count($line[$i]['word']);$j++){
					$zn = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $line[$i]['word'][$j]['content']);
					$en = preg_match("/[a-zA-Z]/u", $line[$i]['word'][$j]['content']);
					if(($zn==0&&$en==1)||$zn==1){
						$shuju[$i] .= ' '.$line[$i]['word'][$j]['content'];
					}
				}
				$shuju[$i] = trim($shuju[$i]);
			}
			// var_dump($shuju);die;
			$i=0;
			$moxie = null;
			for($j=0;$j<count($shuju);$j++){
				if($j<count($shuju)-1){
					$zn = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $shuju[$j]);
					$en = preg_match("/[a-zA-Z]/u", $shuju[$j]);
					$znxt = preg_match("/[\x{4e00}-\x{9fa5}]+/u", $shuju[$j+1]);
					$enxt = preg_match("/[a-zA-Z]/u", $shuju[$j+1]);
					if($zn==1&&$enxt==1){
						$moxie[$i]['que'] =  $shuju[$j];
						$moxie[$i]['anw'] =  $shuju[$j+1];
						$i++;
					}
				}
			}
			if(empty($moxie)){
				$data ['code'] =0;
				$data ['msg'] ='未识别到数据';
			}else{
				$data ['code'] =1;
				$data ['return'] =$return;
				$data ['moxiejson'] =json_encode($moxie);
				$data ['moxie'] =$moxie;
			}
			// var_dump($line);var_dump($shuju);var_dump($moxie);die;
			
		}
		
        return $data;
	}
	
	//默写列表
	public function write_silently_list($nid){
		$list= Db::name('silentwriting')->where(array('G_ararry'=>$nid))->order('time asc')->select();
		return $list;
	}
	//默写详情
	public function write_silently_info($id){
		$info= Db::name('silentwriting')->where(array('id'=>$id))->find();
		return $info;
	}
	//查询默写记录
	public function write_silently_jilu_find($sid,$uid){
		$info= Db::name('silentwritingjilu')->where(array('sid'=>$sid,'uid'=>$uid))->find();
		return $info;
	}
	//个人默写记录
	public function write_silently_jilu_select($uid){
		$info= Db::name('silentwritingjilu')->where(array('uid'=>$uid))->order('time desc')->select();
		$list=array();
		foreach($info as $key => $vo){
			$list[$key]['id'] = $vo['id'];
			$list[$key]['title'] = $vo['title'];
			$list[$key]['image'] = cmf_get_image_url($vo['image']);
			$list[$key]['time'] = date('Y-m-d',$vo['time']);
		}
		
		return $list;
	}
	//我的默写记录详情
	public function my_write_silently_info($id){
		$info= Db::name('silentwritingjilu')->where(array('id'=>$id))->find();
		$info['image'] = cmf_get_image_url($info['image']);
		$info['time'] = date('Y-m-d',$info['time']);
		$info['result'] = json_decode($info['result']);
		return $info;
	}
	//添加默写记录
	public function write_silently_jilu_add($sid,$uid,$answer,$result){
		$info= $this->write_silently_info($sid);
		$data = array('sid'=> $sid,'uid'=> $uid,'image'=> $info['image'],'title'=> $info['title'],'answer'=> $answer,'result'=> $result,'time'=> time());
		$url = Db::name('silentwritingjilu')->insert($data);
        return $url;
	}

	//单词列表by_sid
	public function wordlist_by_sid($sid,$start,$limit){
		$info= $this->write_silently_info($sid);
		
		$word= Db::name('word')->where(array('sid'=>$sid))->limit($start,$limit)->select();
		$studyword=array();
		foreach($word as $vo){
			$studyword[]=$vo['id'];
		}
		$info['word']=$word;
		$info['studyword']=$studyword;
		// var_dump($info);var_dump($word);die;
		return $info;
	}
	//单词详情 根据单词获取
	public function word_by_word($word){
		$info= Db::name('word')->where(array('word'=>$word))->find();
		return $info;
	}
	//单词详情  根据id获取
	public function word_by_sid($id){
		$info = Db::name('word')->where(array('id'=>$id))->find();
		$answerlist = Db::name('word')->select();
		$eg= Db::name('word_eg')->where(array('wid'=>$id))->select();
		$info['eg'] = array();
		
		// var_dump($info);die;
		$numan = range(0,count($answerlist)-1);
		shuffle($numan);
		$answer[0] = $info['option1'];
		// foreach($answerlist as $key => $vo){
		for($i=0;$i<count($answerlist);$i++){
			$xuanxiang = $answerlist[$numan[$i]]['option1'];
			if (!in_array($xuanxiang, $answer)){
			// if($xuanxiang!=$info['option1']){
				$answer[] = $xuanxiang;
			}
			if(count($answer)>3){
				break;
			}
		}
		foreach($eg as $key => $vo){
			 $info['eg'][] =$vo ;
			// var_dump($vo['id']);var_dump($vo['en']);die;
			$url = $this->read_fanyi($vo['id'],$vo['en']);
			$info['eg'][$key]['url'] =$url ;
			// var_dump($url);die;
		}
		// if($info['eg']){
			// $info['eg'] = array();
		// }
		$num = range(0,3);//利用range()函数产生一个0到10的数组
		shuffle($num);
		// var_dump($answer);die;
		$info['xuanzea'] = $answer[$num[0]];
		$info['xuanzeb'] = $answer[$num[1]];
		$info['xuanzec'] = $answer[$num[2]];
		$info['xuanzed'] = $answer[$num[3]];
		$info['answer'] = $info['option1'];
		
		return $info;
	}
	//单词测试列表
	public function wordstudy_list($id){
		$info= $this->write_silently_info($id);
		$word= Db::name('word')->where(array('sid'=>$id))->select();
		$count = count($word);
		
		if($count>0){
			$list = array();
			$num = range(0,$count-1);//利用range()函数产生一个0到10的数组
			shuffle($num);
			foreach($num as $k=>$v){
				if($k<10){
					$list[] = $word[$v]['id'];
				}
			}
			$listcount = count($list);
			$timu = json_encode($list);
			// var_dump($timu);die;
			$data = array('sid'=> $id,'timu'=> $timu,'num'=> $listcount,'time'=> time());
			
			$ceshiid = Db::name('word_ceshi')->insertGetId($data);
			$ruturn['id'] = $ceshiid;
			$ruturn['num'] = $listcount;
			$ruturn['list'] = $list;
			
			
			$message ['code'] = 1;
			$message ['data'] = $ruturn;
			
		}else{
			$message ['code'] = 0;
			$message ['msg'] = '没找到单词';
		}
		return $message;
	}
	//单词测试提交
	public function wordstudy_submit($csid,$uid,$id,$answer){
		$info = Db::name('word')->where(array('id'=>$id))->find();
		if(empty($info)){
			$message ['msg']="未找到单词";
			$message ['code'] = 0;
			return $message;
		}
		$data ['answer'] = $info['option1'];
		$data ['choice'] = $answer;
		
		if($info['option1']==$answer){
			
			
			$data ['type'] = 1;
			$message ['msg']="回答正确";
			$message ['code'] = 1;
			$message ['data'] = $data;
			return $message;
		}else{
			$save = array('uid'=> $uid,'wid'=> $id,'csid'=> $csid,'choice'=> $info['option1'],'word'=> $info['word'],'answer'=> $answer,'time'=> time());
			$url = Db::name('wordjilu')->insertGetId($save);
			$data ['type'] = 0;
			$message ['msg']="回答错误";
			$message ['code'] = 1;
			$message ['data'] = $data;
			return $message;
		}
	}
	//单词测试结果
	public function wordstudy_result($csid,$uid,$usetime){
		
		Db::name('word_ceshi')->where('id',$csid)->update(array('uid'=>$uid,'usetime'=>$usetime));
		$word_ceshi = Db::name('word_ceshi')->where(array('id'=>$csid))->find();
		$wordjilubycsid = Db::name('wordjilu')->where(array('csid'=>$csid))->select();
		if($wordjilubycsid){
			foreach($wordjilubycsid as $k => $vo){
				$wordjilubyuid = Db::name('wordjilu')->where(array('uid'=>$vo['uid'],'wid'=>$vo['wid']))->select();
				
				$voo['num'] = count($wordjilubyuid);
				$voo['word'] = $vo['word'];
				$voo['wid'] = $vo['wid'];
				$word[]=$voo;
			}
			// var_dump(	$word );die;
			$data['usetime'] = $usetime;
			$data['num'] = $word_ceshi['num'];
			$data['accuracy'] = (string)((1-count($wordjilubycsid)/$word_ceshi['num'])*100);
			$data['word'] = $word; 
			
			$message ['code'] = 1;
			$message ['data'] = $data;
		}else{
			$message ['code'] = 0;
			$message ['msg'] = '未找到记录';
		}
		
		return $message;
	}
	//添加到我的单词本
	public function to_my_error_word($wid,$uid){
		$word= Db::name('word')->where(array('id'=>$wid))->find();
		
		// $myword= Db::name('my_erword')->where(array('wid'=>$wid,'uid'=> $uid))->find();
		$myword= Db::name('my_erword')->where(array('word'=>$word['word'],'uid'=> $uid))->find();
		// var_dump($myword);die;
		if(empty($myword)){
			$data = array('wid'=> $wid,'uid'=> $uid,'word'=> $word['word'],'time'=> time());
			$url = Db::name('my_erword')->insert($data);
		}else{
			Db::name('my_erword')->where(array('wid'=>$wid,'uid'=> $uid))->update(array('status'=>1));
		}
        return 1;
	}
	//我的错词本
	public function my_error_word_list($uid,$start,$limit){
		
		$wordall= Db::name('my_erword')->where(array('uid'=>$uid))->limit($start,$limit)->select();
		
		// foreach($wordall as $vo){
			
			// $wordinfo = $this->word_by_sid($vo['wid']);
			// var_dump($vo);die;
			// $vo = $wordinfo['url'];
			// $wordall1[] = $vo;
		// }
		$wordon= Db::name('my_erword')->where(array('uid'=>$uid,'status'=>1))->limit($start,$limit)->select();
		$wordoff= Db::name('my_erword')->where(array('uid'=>$uid,'status'=>0))->limit($start,$limit)->select();
		$data['wordall'] = $wordall;
		$data['wordon'] = $wordon;
		$data['wordoff'] = $wordoff;
		return $data;
	}
	
	//我的错词状态删除
	public function my_error_word_update($id,$status = 0){
		
		$save = Db::name('my_erword')->where('id',$id)->update(array('status'=>$status));
		return $save ;
	}
	
	
	//读取单词
	public function read_word_old($word){
		
		$wordurl = str_replace(' ','_',$word);
		$url ='./upload/word/wd_'.$wordurl.'.mp3';
		$urlnew ='/upload/word/wd_'.$wordurl.'.mp3';
		
		if(file_exists($url)){
			return $urlnew;
		}else{
			$wordreturn = $this->voiceTts($word, $url);
			// var_dump($wordreturn);die;
			if($wordreturn == '语音合成成功！'){
				return $urlnew;
			}else{
				return $wordreturn;
			}
		}
	}
	//读取翻译
	public function read_fanyi($id,$word){
		$url ='./upload/fanyi/fy_'.$id.'.mp3';
		$urlnew ='/upload/fanyi/fy_'.$id.'.mp3';
		// var_dump($word);var_dump($url);die;
		if(file_exists($url)){
			return $urlnew;
		}else{
			$wordreturn = $this->voiceTts($word, $url);
			if($wordreturn == '语音合成成功！'){
				return $urlnew;
			}else{
				return '0';
			}
		}
	}
	
	
	
	//写作列表
	public function write_list($nid){
		$list= Db::name('writing')->where(array('G_ararry'=>$nid))->order('time desc')->select();
		// var_dump($nid);var_dump($list);die;
		return $list;
	}
	//写作详情
	public function write_info($id){
		$info= Db::name('writing')->where(array('id'=>$id))->find();
		return $info;
	}
	//查询写作记录
	public function write_jilu_find($sid,$uid){
		$info= Db::name('writingjilu')->where(array('sid'=>$sid,'uid'=>$uid))->find();
		return $info;
	}
	//查询写作记录
	public function my_write_info($id){
		$info= Db::name('writingjilu')->where(array('id'=>$id))->find();
		$info['image'] = cmf_get_image_url(substr_replace($info['image'],"",0,1));
		return $info;
	}
	//个人写作记录
	public function write_jilu_select($uid,$start,$limit){
		$list0= Db::name('writingjilu')->where(array('uid'=>$uid))->order('time desc')->limit($start,$limit)->select();
		$list=array();
		foreach($list0 as $key => $vo){
			$list[] = $vo;
			$list[$key]['image'] = cmf_get_image_url(substr_replace($vo['image'],"",0,1));
			$list[$key]['time'] = date('Y-m-d',$vo['time']);
		}
		return $list;
	}
	//添加写作记录
	public function write_jilu_add($uid,$url){
		// $info= $this->write_info($sid);
		$data = array('uid'=> $uid,'image'=> $url,'time'=> time());
		$url = Db::name('writingjilu')->insertGetId($data);
        return $url;
	}
	
	//名著列表
	public function book_list(){
		$list0= Db::name('book')->order('id asc')->select();
		$list = array();
		foreach($list0 as $key => $vo){
			$list[] = $vo;
			$list[$key]['image'] = cmf_get_image_preview_url($vo['image']);
		}
		return $list;
	}
	//名著详情
	public function book_info($id){
		$info= Db::name('book')->where(array('id'=>$id))->find();
		$info['image'] = cmf_get_image_preview_url($info['image']);
		$info['nums'] = intval((time() - strtotime("2018-12-5"))/1000);//基础数值
		$booksectionlist= Db::name('booksection')->where(array('bid'=>$id))->select();
		$list = array();
		$arr=[];
		$arrurl=[];
		foreach($booksectionlist as $vo){
			$vo['url'] = cmf_get_image_preview_url($vo['url']);
			$list[]=$vo;
			$arr[]=$vo['id'];
			$arrurl[]=$vo['url'];
		}
		$info['fenduan'] = $list;
		$info['fenduanarray'] = $arr;
		$info['urlarray'] = $arrurl;
		return $info;
	}
	//名著章节内容
	public function book_content_by_fid($id,$uid){
		$info= Db::name('booksection')->where(array('id'=>$id))->find();
		$reading= Db::name('bookreading')->where( array('uid'=> $uid,'fid'=> $id))->find();
		if($reading){
			$info['readurl'] = cmf_get_image_url($reading['url']);
		}else{
			$info['readurl'] = '';
		}
		
		
		$info['url'] = cmf_get_image_preview_url($info['url']);
		$where = array('fid'=>$id);
		$list = Db::name('bookparagraph')->where($where)->select();
		$info['list'] =$list;
		return $info;
	}
	//添加名著语音
	public function book_recording($uid,$fid,$url,$pid){
		$where = array('uid'=> $uid,'fid'=> $fid,'pid'=> $pid);
		$info= Db::name('bookreading')->where($where)->find();
		if($info){
			Db::name('bookreading')->where($where)->update(array('url'=>$url,'time'=> time()));
		}else{
			Db::name('bookreading')->insert(array('uid'=> $uid,'url'=> $url,'fid'=> $fid,'pid'=> $pid,'time'=> time()));
		}
	}
	
	
	//幻灯片
	public function slide_itemlist($name){
        $slide= Db::name('slide')->where(array('name'=>$name))->find();
		$list0= Db::name('slide_item')->where(array('slide_id'=>$slide['id'],'status'=>1))->order('list_order desc')->select();
		// $studynum = intval((time() - strtotime("2018-12-5"))/1000);//基础数值
		$list = array();
		foreach($list0 as $key => $vo){
			$list[] = $vo;
			$list[$key]['image'] = cmf_get_image_preview_url($vo['image']);
			// $list[$key]['num'] = $studynum+$vo['num'];
		}
		return $list;
	}
	//添加访问量
	public function slide_addnum($id){
		$info= Db::name('slide_item')->where('id',$id)->setInc('num', 1);
	}
	
	//添加访问量
	public function slide_item_info($id){
		$info = Db::name('slide_item')->where(array('id'=>$id))->find();
		return $info;
	}
	
	
	//消息状态显示剩余数量
	public function notice_status($uid){
		$num1 = 0;
		$num2 = 0;
		$list1= Db::name('notice')->where(array('type'=>1))->select();
		for($i=0;$i<count($list1);$i++){
			$info1= Db::name('notice_record')->where(array('noticeid'=>$list1[$i]['id'],'authorid'=>$uid))->find();
			if($info1){
				$list11[$i]['status'] = 1;
			}else{
				$list11[$i]['status'] = 0;
				$num1++;
			}
		}

		$list2= Db::name('notice')->where(array('to_authorid'=>$uid,'type'=>2))->select();
		for($i=0;$i<count($list2);$i++){
			$info1= Db::name('notice_record')->where(array('noticeid'=>$list2[$i]['id'],'authorid'=>$uid))->find();
			if($info1){
				$list22[$i]['status'] = 1;
			}else{
				$list22[$i]['status'] = 0;
				$num2++;
			}
		}

		$data['num1']=$num1;
		$data['num2']=$num2;
		return $data;
	}
	//消息记录
	public function notice_list($uid,$type,$start,$limit){
		if($type==1){
			$list0= Db::name('notice')->where(array('type'=>$type))->limit($start,$limit)->order('time desc')->select();
		}else{
			$list0= Db::name('notice')->where(array('to_authorid'=>$uid,'type'=>$type))->limit($start,$limit)->order('time desc')->select();
		}

		return $list0;
	}
	//消息阅读记录
	public function notice_updata($uid,$nid){
		$data2 = array('authorid'=> $uid,'noticeid'=> $nid,'time'=> date('Y-m-d H:i:s'));
		$id = Db::name('notice_record')->insertGetId($data2);
        return $id;
    }
	//所有消息阅读记录
	public function notice_updataall($uid,$type){
		$num = 0;
		if($type==1){
			$list0= Db::name('notice')->where(array('type'=>$type))->select();
		}else{
			$list0= Db::name('notice')->where(array('to_authorid'=>$uid,'type'=>$type))->select();
		}
		for($i=0;$i<count($list0);$i++){
			$info= Db::name('notice_record')->where(array('noticeid'=>$list0[$i]['id'],'authorid'=>$uid))->find();
			if(empty($info)){
				$data2 = array('authorid'=> $uid,'noticeid'=> $list0[$i]['id'],'time'=> date('Y-m-d H:i:s'));
				Db::name('notice_record')->insertGetId($data2);
				$num++;
			}
		}
		
        return 1;
    }
	
	//读单词
	function downFile($url,$path){
		
		$word = "hello";
		$urlapi = "https://fanyi.baidu.com/gettts?lan=en&text=".$word."&spd=3&source=web";
		$path ="./upload/word/";
		$arr = parse_url($urlapi);
		// var_dump($arr);die;
		$fileName=$word;
		// $fileName='tts';
		$file=file_get_contents($urlapi);
		// var_dump($arr);var_dump($path.$fileName.'.mp3');var_dump('11');die;
		$ok = file_put_contents($path.$fileName.'.mp3',$file);
		

	}
	//读取单词
	public function read_word($word){
		$wordurl1 = str_replace('…','',$word);
		$wordurl2 = str_replace('  ',' ',$wordurl1);
		$wordurl = str_replace(' ','_',trim($wordurl2));
		$word = str_replace(' ','%20',trim($wordurl2));
		
		$url ='./upload/word/wd_'.$wordurl.'.mp3';
		$urlnew ='/upload/word/wd_'.$wordurl.'.mp3';
		
		if(file_exists($url)){
			return $urlnew;
		}else{
			$wordreturn = $this->baidufanyi(trim($word), $url);
			// var_dump($wordreturn);die;
			if($wordreturn == '语音合成成功！'){
				return $urlnew;
			}else{
				return $wordreturn;
			}
		}
	}
	//百度翻译单词语音下载
	public function baidufanyi($word, $path){
		
		// https://fanyi.baidu.com/gettts?lan=en&text=eat%20up&spd=3&source=web
		
		// var_dump($word);die;
		$urlapi = "https://fanyi.baidu.com/gettts?lan=en&text=".$word."&spd=3&source=web";
		$arr = parse_url($urlapi);
		@$file=file_get_contents($urlapi);
		if(!$file){
			return '语音合成失败！';
		}
		// var_dump($file);die;
		$ok = file_put_contents($path,$file);
		
		if($ok){
			return '语音合成成功！';
		}else{
			return '语音合成失败！';
		}
	}
	
	
	/**测试
	*
	*/
	public function ceshiinserf($data){
		$data2 = array('content'=> $data);
		$url = Db::name('ceshi')->insert($data2);
        return $url;
    }
	public function ceshifind($id){
		$info= Db::name('ceshi')->where('id',$id)->find();
        return $info;
    }
	
	public function yuyinceshi(){
		
		$file_path = './upload/luyin/wode.jpg';
		// $content = 'holle word';
		// $output_path = './upload/luyin/666.png';
		
		// $info= Db::name('ceshi')->where('id',33)->find();
		// $info=$this->object_to_array(json_decode($info['content']));
		// $info2=$info['data']['block'][0]['line'];
		// var_dump($info2);var_dump($info);die;
		// $data1 = $this->voiceIat($file_path);
		// $data2 = $this->voiceIse($file_path,$content);
		// var_dump($file_path);die;
		$data2 = $this->voiceHw($file_path);
		$this->ceshiinserf($data2);
		// $data2 = $this->voiceTts($content, $output_path);
		var_dump($data2);die;
		// $data['data1']=$data1;
        return $data2;
    }
	public function ceshi(){
		
		$URL = "/upload/luyin/20181227/21545888751.pcm";
		$info = cmf_get_image_preview_url($URL);
		// $list= Db::name('word')->select();
		// foreach($list as $vo){
			// $word[] = $vo['id'];
		// }
		// $word = json_encode($word);
		// var_dump($list);die;
		// $this->ceshiinserf($word);
		var_dump($info);die;
		$info= Db::name('ceshi')->where('id',$id)->find();
        return $info;
    }

	
  
}