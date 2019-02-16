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
namespace app\portal\model;

use think\Model;

class TransapiModel extends Model
{
    // define("CURL_TIMEOUT",   10); 
	// define("URL",            "http://api.fanyi.baidu.com/api/trans/vip/translate"); 
	// define("APP_ID",         "20181123000238242"); //替换为您的APPID
	// define("SEC_KEY",        "H6Vio0unGQWdftCivBkm");//替换为您的密钥

	
	//翻译入口
	public function translate($query)
	{
		
		$app_id = '20181123000238242';
		$sec_key = 'H6Vio0unGQWdftCivBkm';
		$url = 'http://api.fanyi.baidu.com/api/trans/vip/translate';
		$args = array(
			'q' => $query,
			'appid' => $app_id,
			'salt' => rand(10000,99999),
			'from' => 'en',
			'to' => 'zh',

		);
		$args['sign'] = $this->buildSign($query, $app_id, $args['salt'], $sec_key);
		$ret = $this->call($url, $args);
		$ret = json_decode($ret, true);
		return $ret; 
	}
	//翻译入口
	public function translate_old($query, $from, $to)
	{
		$args = array(
			'q' => $query,
			'appid' => $app_id,
			'salt' => rand(10000,99999),
			'from' => $from,
			'to' => $to,

		);
		$args['sign'] = buildSign($query, $app_id, $args['salt'], $sec_key);
		$ret = call($url, $args);
		$ret = json_decode($ret, true);
		return $ret; 
	}

	//加密
	function buildSign($query, $appID, $salt, $secKey)
	{/*{{{*/
		$str = $appID . $query . $salt . $secKey;
		$ret = md5($str);
		return $ret;
	}/*}}}*/

	//发起网络请求
	function call($url, $args=null, $method="post", $testflag = 0, $timeout = 10, $headers=array())
	{/*{{{*/
		$ret = false;
		$i = 0; 
		while($ret === false) 
		{
			if($i > 1)
				break;
			if($i > 0) 
			{
				sleep(1);
			}
			$ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
			$i++;
		}
		return $ret;
	}/*}}}*/

	function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = 10, $headers=array())
	{/*{{{*/
		$ch = curl_init();
		if($method == "post") 
		{
			$data = $this->convert($args);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_POST, 1);
		}
		else 
		{
			$data = convert($args);
			if($data) 
			{
				if(stripos($url, "?") > 0) 
				{
					$url .= "&$data";
				}
				else 
				{
					$url .= "?$data";
				}
			}
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(!empty($headers)) 
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if($withCookie)
		{
			curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
		}
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}/*}}}*/

	function convert(&$args)
	{/*{{{*/
		$data = '';
		if (is_array($args))
		{
			foreach ($args as $key=>$val)
			{
				if (is_array($val))
				{
					foreach ($val as $k=>$v)
					{
						$data .= $key.'['.$k.']='.rawurlencode($v).'&';
					}
				}
				else
				{
					$data .="$key=".rawurlencode($val)."&";
				}
			}
			return trim($data, "&");
		}
		return $args;
	}/*}}}*/
	
}