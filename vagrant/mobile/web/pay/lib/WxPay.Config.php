<?php
/**
* 	配置账号信息
*/
//$state = $_GET['payInfo'];
//$newstate = explode("|",$state);
//if($newstate[7] == "ckcz"){
//	if($newstate[6] == "56650")
//	{
//		class WxpayConfig
//		{
//			const APPID = 'wx8c6755d40004036d';
//			const MCHID = '1366091602';
//			const KEY = 'yai1ga134d4t2q1kaife8i1it6ikwaqi';
//			const APPSECRET = 'bb0e9a8a2a7cb366b57d2db1b66e24fc';
//			const SSLCERT_PATH = 'apiclient_cert56651.pem';
//			const SSLKEY_PATH = 'apiclient_key56651.pem';
//			const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
//			const CURL_PROXY_PORT = 0;//8080;
//			const REPORT_LEVENL = 1;
//
//		/**
//		 * 支付完成后的微信的通知url
//		 */
//		const APP_PAY_URL ="http://mobile.jxqwt.cn/pay/";
//
//		/**
//		 * 处理本地业务逻辑url
//		 */
//		const PAY_CALLBACK_URL = "http://mobile.jxqwt.cn/zhifu/paynotify";
//		/**
//		 * 总支付成功跳转url
//		 */
//		const REDIRECT_URLZ = "http://mobile.jxqwt.cn/zfend/pay/";
//		}
//	}
//	else
//	{
//		class WxpayConfig
//		{
//			const APPID = 'wx8c6755d40004036d';
//			const MCHID = '1316694901';
//			const KEY = '13598022688guoyaojun13598022688g';
//			const APPSECRET = 'bb0e9a8a2a7cb366b57d2db1b66e24fc';
//			const SSLCERT_PATH = 'apiclient_cert.pem';
//			const SSLKEY_PATH = 'apiclient_key.pem';
//			const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
//			const CURL_PROXY_PORT = 0;//8080;
//			const REPORT_LEVENL = 1;
//
//			/**
//			 * 支付完成后的微信的通知url
//			 */
//			const APP_PAY_URL = "http://mobile.jxqwt.cn/ischool/pay/";
//
//			/**
//			 * 处理本地业务逻辑url
//			 */
//			const PAY_CALLBACK_URL = "http://mobile.jxqwt.cn/zhifu/paynotify";
//			/**
//			 * 总支付成功跳转url
//			 */
//			const REDIRECT_URLZ = "http://mobile.jxqwt.cn/zfend/pay/";
//		}
//	}
//}else{
	class WxpayConfig
	{

		//=======【基本信息设置】=====================================
		//
		/**
		 * TODO: 修改这里配置为您自己申请的商户信息
		 * 微信公众号信息配置
		 *
		 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
		 *
		 * MCHID：商户号（必须配置，开户邮件中可查看）
		 *
		 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
		 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
		 *
		 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
		 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
		 * @var string
		 */
		const APPID = 'wx8c6755d40004036d';
		const MCHID = '1480239942';
		const KEY = 'ff4c0f4f0f3bae31cac8a75050c9c5f2';
		const APPSECRET = 'bb0e9a8a2a7cb366b57d2db1b66e24fc';

		//=======【证书路径设置】=====================================
		/**
		 * TODO：设置商户证书路径
		 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
		 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
		 * @var path
		 */
		const SSLCERT_PATH = 'apiclient_cert.pem';
		const SSLKEY_PATH = 'apiclient_key.pem';

		//=======【curl代理设置】===================================
		/**
		 * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
		 * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
		 * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
		 * @var unknown_type
		 */
		const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
		const CURL_PROXY_PORT = 0;//8080;

		//=======【上报信息配置】===================================
		/**
		 * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
		 * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
		 * 开启错误上报。
		 * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
		 * @var int
		 */
		const REPORT_LEVENL = 1;

		/**
		 * 支付完成后的微信的通知url
		 */
		const APP_PAY_URL = "http://mobile.jxqwt.cn/pay/";

		/**
		 * 处理本地业务逻辑url
		 */
		const PAY_CALLBACK_URL = "http://mobile.jxqwt.cn/zhifu/paynotify";

		/**
		 * 成功跳转url
		 */
		const REDIRECT_URL = "http://mobile.jxqwt.cn/zhifu/gopay/";
		/**
		 * 家校沟通成功跳转url
		 */
		const REDIRECT_URLJX = "http://mobile.jxqwt.cn/zhifu/gopayjx/";
		/**
		 * 总支付成功跳转url
		 */
		const REDIRECT_URLZ = "http://mobile.jxqwt.cn/zfend/pay/";
	}
//}



