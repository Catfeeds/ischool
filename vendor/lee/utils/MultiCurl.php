<?php
namespace lee\utils;
   /*
   Authored by Josh Fraser (www.joshfraser.com)
   Released under Apache License 2.0

   Maintained by Alexander Makarov, http://rmcreative.ru/

   $Id$
   */
   /**
    * Class that represent a single curl request
    */
   class RollingCurlRequest {
     public $url = false;
     public $method = 'GET';
     public $post_data = null;
     public $headers = null;
     public $options = null;
     public $info = null;
     public $callback;
     public $recursion = false;
     public $other_data = null;

     /**
      * @param string $url
      * @param string $method
      * @param  $post_data
      * @param  $headers
      * @param  $options
      * @return void
      */
     function __construct($url, $options = null, $info = null, $method = "GET", $post_data = null, $headers = null  ) {
       $this->url = $url;
       $this->method = $method;
       $this->post_data = $post_data;
       $this->headers = $headers;
       $this->options = $options;
       $this->info = $info;
     }

     /**
      * @return void
      */
     public function __destruct() {
       unset($this->url, $this->method, $this->post_data, $this->headers, $this->options);
     }
   }

   /**
    * RollingCurl custom exception
    */

   /**
    * Class that holds a rolling queue of curl requests.
    *
    * @throws RollingCurlException
    */
   class RollingCurl {
     /**
      * @var int
      *
      * Window size is the max number of simultaneous connections allowed.
      *
      * REMEMBER TO RESPECT THE SERVERS:
      * Sending too many requests at one time can easily be perceived
      * as a DOS attack. Increase this window_size if you are making requests
      * to multiple servers or have permission from the receving server admins.
      */
     public $window_size = 5;

     //private $master = 'NULL';
     //保存连接数量
     public $current_size =0;
     /**
      * @var float
      *
      * Timeout is the timeout used for curl_multi_select.
      */
     private $timeout = 10;

     /**
      * @huhaibo
      * return the fail request
      */
     private $the_fails = array();

     /**
      * @var array
      *
      * Set your base options that you want to be used with EVERY request.
      */
     protected $options = array(
       CURLOPT_SSL_VERIFYPEER => 0,
       CURLOPT_RETURNTRANSFER => 1,
       CURLOPT_VERBOSE => 0,
       CURLOPT_TIMEOUT=> 20,
       CURLOPT_DNS_CACHE_TIMEOUT => 3600,
       CURLOPT_CONNECTTIMEOUT => 10,
       CURLOPT_ENCODING => 'gzip,deflate',
       CURLOPT_FOLLOWLOCATION => 1,
       CURLOPT_MAXREDIRS => 2,
       CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0',
       //CURLOPT_HEADER => 1
     );

     /**
      * @var array
      */
     private $headers = array(

       'Connection: Keep-Alive',
       'Keep-Alive: 300',
       'Expect:'
     );

     /**
      * @var Request[]
      *
      * The request queue
      */
     private $requests = array();

     /**
      * @var RequestMap[]
      *
      * Maps handles to request indexes
      */
     private $requestMap = array();

     /**
      * @param  $callback
      * Callback function to be applied to each result.
      *
      * Can be specified as 'my_callback_function'
      * or array($object, 'my_callback_method').
      *
      * Function should take three parameters: $response, $info, $request.
      * $response is response body, $info is additional curl info.
      * $request is the original request
      *
      * @return void
      */
     function __construct($callback = null) {
       $this->callback = $callback;
     }

     /**
      * @param string $name
      * @return mixed
      */
     public function __get($name) {
       return (isset($this->{$name})) ? $this->{$name} : null;
     }

     /**
      * @param string $name
      * @param mixed $value
      * @return bool
      */
     public function __set($name, $value) {
       // append the base options & headers
       if ($name == "options" || $name == "headers") {
         $this->{$name} = $value + $this->{$name};
       } else {
         $this->{$name} = $value;
       }
       return true;
     }

     /**
      * Add a request to the request queue
      *
      * @param Request $request
      * @return bool
      */
     public function add($request) {
       $this->requests[] = $request;
       return true;
     }

     /**
      * Create new Request and add it to the request queue
      *
      * @param string $url
      * @param string $method
      * @param  $post_data
      * @param  $headers
      * @param  $options
      * @return bool
      */
     public function request($url, $method = "GET", $post_data = null, $headers = null, $options = null) {
       $this->requests[] = new RollingCurlRequest($url, $method, $post_data, $headers, $options);
       return true;
     }

     /**
      * Perform GET request
      *
      * @param string $url
      * @param  $headers
      * @param  $options
      * @return bool
      */
     public function get($url, $headers = null, $options = null) {
       return $this->request($url, "GET", null, $headers, $options);
     }

     /**
      * Perform POST request
      *
      * @param string $url
      * @param  $post_data
      * @param  $headers
      * @param  $options
      * @return bool
      */
     public function post($url, $post_data = null, $headers = null, $options = null) {
       return $this->request($url, "POST", $post_data, $headers, $options);
     }

     /**
      * Execute processing
      *
      * @param int $window_size Max number of simultaneous connections
      * @return string|bool
      */
     public function execute($window_size = null) {
       // rolling curl window must always be greater than 1
       if (sizeof($this->requests) == 1) {
         return $this->single_curl();
       } else {
         // start the rolling curl. window_size is the max number of simultaneous connections
         return $this->rolling_curl($window_size);
       }
     }

     /**
      * Performs a single curl request
      *
      * @access private
      * @return string
      */
     private function single_curl() {
       $ch = curl_init();
       $request = array_shift($this->requests);
       //获取选项及header
       $options = $this->get_options($request);
       curl_setopt_array($ch, $options);
       $output = curl_exec($ch);
       $info = curl_getinfo($ch);
       //处理错误
       if (curl_error($ch))
         $info['error'] = curl_error($ch);

       // it's not neccesary to set a callback for one-off requests
//       if ($request->callback) {
//         $callback = $request->callback;
//         if (is_callable($callback)) {
//           call_user_func($callback, $output, $info, $request);
//         }
//       }
//       else
//         return $output;
       $this->callback($output, $info, $request);
       return $this->the_fails;
     }

     /**
      * Performs multiple curl requests
      *
      * @access private
      * @throws RollingCurlException
      * @param int $window_size Max number of simultaneous connections
      * @return bool
      */
     private function rolling_curl($window_size = null) {
       set_time_limit(0);
       if ($window_size)
         $this->window_size = $window_size;

       // make sure the rolling window isn't greater than the # of urls
       if (sizeof($this->requests) < $this->window_size)
         $this->window_size = sizeof($this->requests);

//       if ($this->window_size < 2) {
//         throw new Exception("Window size must be greater than 1");
//       }

       $master = curl_multi_init();

       //首次执行填满请求
       for ($i = 0; $i < $this->window_size; $i++) {
         $ch = curl_init();

         $options = $this->get_options($this->requests[$i]);

         curl_setopt_array($ch, $options);
         curl_multi_add_handle($master, $ch);

         $key = (int) $ch;
         //ch重用队列
         $chs[$key] = $ch;
         //请求map，后续根据返回信息的ch获取原始请求信息
         $this->requestMap[$key] = $i;
         $this->current_size++;
       }

       do {
         //执行句柄内所有连接，包括后来新加入的连接
         do {
           //running变量返回正在处理的curl数量，0表示当前没有正在执行的curl
           $execrun = curl_multi_exec($master, $running);
         } while ($execrun == CURLM_CALL_MULTI_PERFORM); // 7.20.0后弃用

         if ($execrun != CURLM_OK)
           echo "ERROR!\n " . curl_multi_strerror($execrun);

         //阻塞一会等待有数据可读，返回可读数量，失败为-1，避免一直循环占用CPU
         if ($running)
           curl_multi_select($master, $this->timeout);

         //读取返回的连接，并加入新的连接
         while ($done = curl_multi_info_read($master)) {

           //获取完成的句柄
           $ch = $done['handle'];
           //获取返回的请求信息
           $info = curl_getinfo($ch);
           //获取返回内容
           $output = curl_multi_getcontent($ch);
           //处理错误信息
           //if (curl_error($ch))
           if ($done['result'] != CURLE_OK)
             $info['error'] = curl_error($ch);

           //根据请求映射是哪个请求返回的信息,即请求数组中第i个请求
           $key = (int) $ch;
           $request = $this->requests[$this->requestMap[$key]];
           //发送返回信息到回调函数
//           $callback = $request->callback;
//           if (is_callable($callback)) {
//             //移除请求信息和请求映射
//             addWeixinLog ( '-dfdffdf--------------------------','');
//             unset($this->requests[$this->requestMap[$key]]);
//             unset($this->requestMap[$key]);
             //$this->current_size--;
             $this->callback($output, $info, $request);
             unset($this->requests[$this->requestMap[$key]]);
             unset($this->requestMap[$key]);
             $this->current_size--;
             //回调函数
             //call_user_func($callback, $output, $info, $request);
           //}
           //删除完成的句柄
           curl_multi_remove_handle($master, $done['handle']);

           //判断队列内的连接是否用完
           if (isset($this->requests[$i])) {
             //重用之前完成的ch
             $ch = $chs[$key];
             //var_dump($ch);
             $options = $this->get_options($this->requests[$i]);
             curl_setopt_array($ch, $options);
             //增加新的连接
             curl_multi_add_handle($master, $ch);

             //添加到request Maps，用于返回信息时根据handle找到相应连接
             $key = (int) $ch;
             $this->requestMap[$key] = $i;
             $this->current_size++;
             $i++;
           }
         }

       } while ($this->current_size) ;
       curl_multi_close($master);
       return $this->the_fails;
     }

     public function callback($output, $info, $request){

       $final = json_decode($output);
       $errcode = $final->errcode;

       if($errcode != 0 && $errcode != 43004 && $errcode != 40003){
         $post_data = json_decode($request->post_data);
         if($errcode == 45015 || $errcode == 45047){  //长时间未交互，重发
           $this->the_fails[]= $post_data->touser;
         }else{
           \yii::trace( $output,'openid--'.$post_data->touser);
         }
       }

       return true;
     }

     //返回是否还有活动连接
     public function state() {
       return curl_multi_select($this->master, $this->timeout);
     }

     /**
      * Helper function to set up a new request by setting the appropriate options
      *
      * @access private
      * @param Request $request
      * @return array
      */
     private function get_options($request) {
       //获取类内选项设置
       $options = $this->__get('options');
       if (ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')) {
         $options[CURLOPT_FOLLOWLOCATION] = 1;
         $options[CURLOPT_MAXREDIRS] = 5;
       }

       //附加类内设置到请求选项中
       if ($request->options) {
         $options = $request->options + $options;
       }

       //获取类内head设置
       $headers = $this->__get('headers');

       //附加header
       if ($request->headers) {
         $headers = $request->headers + $headers;
       }

       // set the request URL
       $options[CURLOPT_URL] = $request->url;

       // posting data w/ this request?
       if ($request->post_data) {
         $options[CURLOPT_POST] = 1;
         $options[CURLOPT_POSTFIELDS] = $request->post_data;
       }
       if ($headers) {
         $options[CURLOPT_HEADER] = 0;
         $options[CURLOPT_HTTPHEADER] = $headers;
       }

       return $options;
     }

     /**
      * @return void
      */
     public function __destruct() {
       unset($this->window_size, $this->callback, $this->options, $this->headers, $this->requests);
     }

     Function test() {

       var_dump($this->requests);
     }
   }
