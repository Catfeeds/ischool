<?php
/**
* 	微信对账单类库
*/
// include_once("/data/web/ischool/mobile/web/pay/lib/WxPay.Config.php");
class Common_util_pub{	
	function trimString($value){
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr( $length = 32 ) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 	作用：生成签名
	 */
	public function getSign($Obj,$KEY){
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
                $String = $String."&key=".$KEY;
//		$String = $String."&key=".WxPayConfig::KEY;
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
	
	/**
	 * 	作用：array转xml
	 */
	function arrayToXml($arr){
            $xml = "<xml>";
            foreach ($arr as $key=>$val)
             {
                      if (is_numeric($val))
                      {
                             $xml.="<".$key.">".$val."</".$key.">"; 

                      }
                      else
                             $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
             }
             $xml.="</xml>";
             return $xml; 
         }
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml){		
                //将XML转为array        
                $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
                return $array_data;
                }

	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30)
	{		
                //初始化curl        
                $ch = curl_init();
                        //设置超时
                  //      curl_setopt($ch, CURLOP_TIMEOUT, $second);
                 //这里设置代理，如果有的话
                //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
                //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
                        //设置header
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        //要求结果为字符串且输出到屏幕上
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        //post提交方式
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                        //运行curl
                $data = curl_exec($ch);
	//	curl_close($ch);
		//返回结果
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else 
		{ 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}

	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
	function postXmlSSLCurl($xml,$url,$second=30)
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
                //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
                //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	
	/**
	 * 	作用：打印数组
	 */
	function printErr($wording='',$err='')
	{
		print_r('<pre>');
		echo $wording."</br>";
		var_dump($err);
		print_r('</pre>');
	}
}
/**
 * 请求型接口的基类
 */
class Wxpay_client_pub extends Common_util_pub 
{
	var $parameters;//请求参数，类型为关联数组
	public $response;//微信返回的响应
	public $result;//返回参数，类型为关联数组
	var $url;//接口链接
	var $curl_timeout;//curl超时时间
	
	/**
	 * 	作用：设置请求参数
	 */
	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}
	
	/**
	 * 	作用：设置标配的请求参数，生成签名，生成接口参数xml
	 */
	function createXml($APPID,$MCHID,$KEY)
	{
	   //	$this->parameters["appid"] = WxPayConfig::APPID;//公众账号ID
                $this->parameters["appid"] = $APPID;
	   //	$this->parameters["mch_id"] = WxPayConfig::MCHID;//商户号
                $this->parameters["mch_id"] =$MCHID;
                $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
                $this->parameters["sign"] = $this->getSign($this->parameters,$KEY);//签名
                return  $this->arrayToXml($this->parameters);
	}
	
	/**
	 * 	作用：post请求xml
	 */
	function postXml($APPID,$MCHID,$KEY)
	{
	        $xml = $this->createXml($APPID,$MCHID,$KEY);
		$this->response = $this->postXmlCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}
	
	/**
	 * 	作用：使用证书post请求xml
	 */
	function postXmlSSL($APPID,$MCHID,$KEY)
	{	
	    $xml = $this->createXml($APPID,$MCHID,$KEY);
		$this->response = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}

	/**
	 * 	作用：获取结果，默认不使用证书
	 */
	function getResult($APPID,$MCHID,$KEY) 
	{		
		$res = $this->postXml($APPID,$MCHID,$KEY);

		$this->result = $this->xmlToArray($this->response);
		return $this->result;
	}
}
/**
 * 对账单接口
 */
class DownloadBill_pub extends Wxpay_client_pub{
        public $result_xml;
	function __construct() 
	{
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/pay/downloadbill";
		//设置curl超时时间
//		$this->curl_timeout = WxPayConfig::CURL_TIMEOUT;		
	}

	/**
	 * 生成接口参数xml
	 */
	function createXml($APPID,$MCHID,$KEY)
	{	           
                if($this->parameters["bill_date"] == null ) 
                {
                    return false;
//                        throw new SDKRuntimeException("对账单接口中，缺少必填参数bill_date！"."<br>");
                }
//                $this->parameters["appid"] = WxPayConfig::APPID;//公众账号ID
                $this->parameters["appid"] = $APPID; 
//                $this->parameters["mch_id"] = WxPayConfig::MCHID;//商户号
                $this->parameters["mch_id"] =$MCHID;
                $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
                $this->parameters["sign"] = $this->getSign($this->parameters,$KEY);//签名
                return  $this->arrayToXml($this->parameters);
		
	}
	
	/**
	 * 	作用：获取结果，默认不使用证书
	 */
	function getResult($APPID,$MCHID,$KEY) 
	{		
		
		$info = $this->postXml($APPID,$MCHID,$KEY);
		if(substr($info, 0 , 5) == "<xml>")
		$info = $this->xmlToArray($info);
		return $info;
	}
	
	

}