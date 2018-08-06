<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
	<title>我要支付</title>
	<link rel="stylesheet" href="/css/zf.css">
	<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
        <style>
            .yktzf{
                    float: right;
                    color: orangered;
                    font-size: 1.3em;
            }
            .tejia,.hjzf,.ssyg1,.ssyg2,.wgzf,.zczf,.sgyn,.sgln,.sgsn,.wg_onemonth{
                    color: orangered;
                    font-size: 1.3em;
                    vertical-align: middle;
            }
         
            .xz3{
            	width: 93%;
            } 
             .xz3 span{
            	padding-right:3em;
            } 
             .radio_img,.check_toggle{
                background: url(/img/icon_02.png) no-repeat;
                background-size: cover;
                width: 1em;
                height: 1em;
           }
            .check_toggle,input[type="radio"]:checked+div{
				background: url(/img/icon_01.png) no-repeat;
				background-size: cover;
		   }
		    .toggle_this{
				height:1rem; width:1rem; line-height: 1rem;
			}
			.toggle_this > h2{
				float:left;width:1900%;padding-left:150%;font-size:1.28em;
			}
			.toggle_this > h2>span{
				float:right;font-weight:normal;color:orangered;
			}
			.title_style{
				color:#666;width:93%;display:block;background-color:#ddd;
			}

			
        </style>
</head>
	<body>
	<!-- <header class="bar bar-nav">
          <h1 class="title" style="color: white">
            <a style="color: white;position: absolute;left: 10px" href="<?php echo URL_PATH?>/zfend/pay">&lt;</a>
            充值
          </h1>
      </header> -->
	<div class="top">
		<a href="<?php echo URL_PATH?>/zfend/pay"><div class="left"> </div></a>
		<h1 style="padding-right:10%;">功能支付</h1>
		<div class="clearfix"></div>
	</div>
	      <?php if($ykt ==0 || $ykt ==9 || $ykt==6 ||$ykt==10){?>
		<div class="yw">
		   <?php if($ykt==10): ?>
			<p class="yw_1">请选择您需订购的业务:</p>   
			<?php else:?>   
			<p class="yw_1">请选择您需订购的业务</p>                
			<p>可多选，若多选可享受套餐优惠</p>
            <?php endif;?>          
		</div>
		   <?php }?>
		<div class="xz">
			<ul>
                    <?php if($ykt ==0 || $ykt ==9 || $ykt==6){ ?>
				<li class="xz1" id="cos0">
					<div class="check">
						<input id="ischange" type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
				
						<span class="danj" style="float: right;"></span>
				
						<h3>平安通知				
						</h3>
						<p>本业务家长可实时收到学生进出校及进出宿舍的通知</p>
					</div>
				</li>
				<li class="xz1" id="cos1">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" value="1" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
				
						<span class="danj" style="float: right;"></span>
			
						<h3>家校沟通
					
						</h3>
						<p>本业务家长可与老师随时在线沟通（通过文字，图片，语音，视频等多种方式了解学生在校情况）</p>
					<!-- 	<p>本业务可使家长和老师实时在线沟通</p> -->
					</div>
				</li>
				
				<li class="xz1" id="cos2">
					<div class="check">
						<?php if($ykt==6){?>
						<input type="checkbox" name="xz0"  disabled class="check0" />
						<div class="check_img" style="visibility: hidden;"></div>
						<?php }else{ ?>
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
						<?php }?>
						
					</div>
					<div class="xz2">
				
						<span  class="danj" style="float: right;"></span>
					
						<h3>亲情电话 
							<?php if($ykt==6){ ?>
								 	<span style="color:orangered;font-weight:normal;">(暂未开通)</span>					      
						       <?php }?>

						 </h3>
						<p>本业务学生可使用学生证无限量免费和家长打电话，号码不分移动、联通、电信</p>
			<!-- 			<p>本业务可使学生用学生证无限量与家长打电话</p> -->
					</div>
				</li>
				<?php if($ykt==6){?>
				<li class="xz1" id="cos3">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
				
						   <span class="danj" style="float: right;"></span>													
						   <h3>校园服务</h3>						
						   <span style="color:red;font-size:1em;margin-left:-2%;">（初中部正常使用，小学部暂未开通）</span>		
						   <p>本卡可作餐卡、水卡、购物卡使用，能即时微信充值</p>
					
					
					</div>
				</li>


				<?php }else{ ?>
				<li class="xz1" id="cos3">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
				
						      <span class="danj" style="float: right;"></span>													
							  <h3>餐卡服务</h3>						
						   		
						   <p>本业务可对餐卡充值和收到餐卡消费信息</p>
					
					
					</div>
				</li>
				<?php } ?>
				

                       <?php }elseif ($ykt==2 || $ykt==1 || $ykt==5  || $ykt==7 || $ykt==8) { ?>
					<?php if($ykt ==2 ){ ?>
                	<li  class="xz2 xz1 yw_1 title_style"><h3>一卡通免费服务业务：</h3></li>
					<?php }?>
                	<li class="xz1" id="cos5">
						<div class="xz2 xz3">
							<span   style="float: right;"></span>
							<h3>平安通知</h3>							
						    <?php if($ykt==7 || $ykt==8){ ?>
							<p>本业务家长可实时收到学生进出校的通知</p>	
						    <?php }else{ ?>
						    <p>本业务家长可实时收到学生进出校及进出宿舍的通知</p>
							<?php }?>
								
						</div>
					</li>
					<?php if($ykt ==2){ ?>
					<li class="xz2 xz1 yw_1 title_style"><h3>一卡通有偿服务业务（根据需要自愿开通）：</h3></li>
                	<?php } ?>
                	<li class="xz1" id="cos6">
						<div class="xz2 xz3">
							<span   style="float: right;"></span>
							<h3>家校沟通</h3>
							<p>本业务家长可与老师随时在线沟通（通过文字，图片，语音，视频等多种方式了解学生在校情况）</p>
						</div>
					</li>
					<li class="xz1" id="cos7">
						<div class="xz2 xz3">
							<span   style="float: right;"></span>
							<h3>亲情电话</h3>							
							<p>本业务学生可使用学生证无限量免费和家长打电话，号码不分移动、联通、电信</p>
						</div>
					</li>
					<li id="cos8" style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
						<div class="xz2 xz3">
							<span   style="float: right;"></span>
							<h3>校园消费
								 <?php if($ykt==5||$ykt==7 || $ykt==8){ ?>
								 	<span style="color:orangered;font-weight:normal;">(暂未开通)</span>
								 <?php }?>
								
							</h3>
						     <?php if($ykt==6){?>
						        <span style="color:red;font-size:1em;margin-left:-2%;">（初中部正常使用，小学部暂未开通）</span>
							 <?php }?>
							<?php if( $ykt==2 || $ykt==5 || $ykt==6 || $ykt==7 || $ykt==8){ ?>
							<p>本卡可作餐卡、水卡、购物卡使用，能即时微信充值</p>
							<?php }else if($ykt==1){ ?>
							<p>本卡可作餐卡、水卡、购物卡使用，能即时查询消费信息</p>
							<?php } ?>
						</div>
					</li>
					  <?php if($ykt==2){ ?>
					     <div style="background-color:#eee;display:block;height:15px;" ></div>
					    <!--  <li class="checkboxclas" id="cos9"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio" checked  name="xz0" class="check0" id="oneyear" />
	                                 
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="tejia" style="float: right;"></span>
									<h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">至2018年8月31日</span></p>
	                        </div>
	                      </li> -->
	                      <li class=" checkboxclas" id="cos10"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio"  name="xz0" class="check0" id="yxni"/>
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="wgzf" style="float: right;"></span>
	                                <h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">12个月</span></p>
	                        </div>
	                      </li>
	                      <li class=" checkboxclas" id="cos10"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio"  name="xz0" class="check0" id="ygyu"/>
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="wg_onemonth" style="float: right;"></span>
	                                <h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">1个月</span></p>
	                        </div>
	                      </li>
	                	<!-- <li class="xz1" id="cos10">
							<div class="check">
								<input type="radio" checked name="xz0" class="check0" />
								<div class="radio_img"></div>
							</div>
							<div class="xz2">
								<span  class="wgzf" style="float: right;"></span>
								<h3>一卡通服务资费</h3>							
								<p>有效期：<span style="color:orangered;">12个月</span></p>							
								
							</div>
						</li> -->
					    <?php }else if($ykt==1 ){?>	
					<!--     <li class="xz1 ssygzf" id="cos9">
							<div class="check">
								<input type="radio" checked name="xz0" class="check0" id="oneyear" />
								<div class="radio_img"></div>
							</div>
							<div class="xz2">
								<span  class="tejia" style="float: right;"></span>
								<h3>一卡通服务资费</h3>
								<span style="color:orangered;font-size:1.0rem;font-weight:bold;">(活动截止到3月14日，限时3天特惠) </span>						
								<p>有效期：<span style="color:orangered;">至:2018年8月31日</span></p>	
							</div>
						</li> -->
					    <li class="xz1 ssygzf" id="cos10">
							<div class="check">
								<input type="radio"  name="xz0" class="check0" id="oneyear" />
								<div class="radio_img"></div>
							</div>
							<div class="xz2">
								<span  class="wgzf" style="float: right;"></span>
								<h3>一卡通服务资费</h3>						
								<p>有效期：<span style="color:orangered;">至:2018年8月31日</span></p>	
							</div>
						</li>	
						<li class="xz1 ssygzf" id="cos11">
							<div class="check">
								<input type="radio" name="xz0" class="check0" id="twoyear"/>
								<div class="radio_img"></div>
							</div>
							<div class="xz2">
								<span  class="hjzf" style="float: right;"></span>
								<h3>一卡通服务资费</h3>
								<p>有效期：<span style="color:orangered;">至:2019年8月31日</span></p>
								
								
							</div>
						</li>
						<?php }else if($ykt==7 || $ykt==5 || $ykt==6 ){?>
					      <!--  <li style="padding-left:17;color:#666;width:100%;" class="xz2 xz1 yw_1"><h3>一卡通服务资费：</h3></li> -->
						   <li class="checkboxclas" id="cos13"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio" checked  name="xz0" class="check0" id="oneyear" />
	                                 
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="ssyg1" style="float: right;"></span>
									<h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">至2018年8月31日</span></p>
	                        </div>
	                      </li>
	                      <li class=" checkboxclas" id="cos18"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio"  name="xz0" class="check0" id="twoyear"/>
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="ssyg2" style="float: right;"></span>
	                                <h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">至2019年8月31日</span></p>
	                        </div>
	                      </li>
	                      <?php }else if($ykt==8){ ?>
							<li class="checkboxclas" id="cos13"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio" checked  name="xz0" class="check0" id="oneyear" />
	                                 
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="tejia" style="float: right;"></span>
									<h3>一卡通服务资费<span style="color:orangered;">(限时优惠)</span></h3>
	                                <p>有效期：<span style="color:orangered;">至2018年8月31日</span></p>
	                        </div>
	                      </li>
	                      <li class=" checkboxclas" id="cos18"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
	                        <div class="check">
	                                <input type="radio"  name="xz0" class="check0" id="yxni"/>
	                                <div class="radio_img"></div>
	                        </div>
	                        <div class="xz2">
	                                <span  class="wgzf" style="float: right;"></span>
	                                <h3>一卡通服务资费</h3>
	                                <p>有效期：<span style="color:orangered;">12个月</span></p>
	                        </div>
	                      </li>
	                      <?php }?>

                      <?php }else if($ykt ==3){ ?>
                              <li style="padding-left:17;color:#666;" class="xz2 xz1 yw_1"><h3>一卡通服务业务：</h3></li> 
                              <li class="xz1" id="cos10">
                                 <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>平安通知</h3>
                                        <p>本业务家长可实时收到学生进出校及进出宿舍的通知</p>
                                 </div>
                              </li>
                              <li class="xz1" id="cos11">
                                <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>家校沟通</h3>
                                        <p>本业务家长可与老师随时在线沟通（通过文字，图片，语音，视频等多种方式了解学生在校情况）</p>
                                </div>
                              </li>
                              <li class="xz1" id="cos12">
                                <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>亲情电话</h3>
                                        <p>本业务学生可使用学生证无限量免费和家长打电话，号码不分移动、联通、电信</p>
                                </div>
                              </li>
                              <li class="xz1" id="cos13">
                                <div class="check">
                                        <input type="radio" checked name="xz0" class="check0" />
                                        <div class="radio_img"></div>
                                </div>
                                <div class="xz2">
                                        <span  class="zczf" style="float: right;"></span>
                                        <h3>一卡通服务资费</h3>
                                        <p>有效期：<span style="color:orangered;">一学年</span></p>
                                </div>
                             </li>
                     <?php }else if($ykt==4){ ?>
                     	      <li style="padding-left:17;color:#666;width:100%;background-color:#ddd;" class="xz2 xz1 yw_1"><h3>一卡通服务业务：</h3></li> 
                              <li class="xz1" id="cos10"  style="border-bottom:none;" >
                                 <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>平安通知</h3>
                                        <p>本业务家长可实时收到学生进出校及进出宿舍的通知</p>
                                 </div>
                              </li>
                              <li class="xz1" id="cos11" style="border-bottom:none;">
                                <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>家校沟通</h3>
                                        <p>本业务可使家长和老师实时在线沟通</p>
                                </div>
                              </li>
                              <li class="xz1" id="cos12" style="border-bottom:none;">
                                <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>亲情电话</h3>
                                        <p>本业务可使学生用学生证无限量与家长打电话</p>
                                </div>
                              </li>
                              <li class="xz1" id="cos16" >
                                <div class="xz2 xz3">
                                        <span   style="float: right;"></span>
                                        <h3>餐卡服务</h3>
                                        <p>本业务可对餐卡充值和收到餐卡消费信息</p>
                                </div>
                              </li>

                              <li style="padding-left:17;color:#666;width:100%;background-color:#ddd;" class="xz2 xz1 yw_1"><h3>一卡通服务资费：</h3></li> 
                              <li class=" checkboxclas" id="cos10"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
		                        <div class="check">
		                                <input type="radio"  name="xz0" class="check0" id="yxni"/>
		                                <div class="radio_img"></div>
		                        </div>
		                        <div class="xz2">
		                                <span  class="wgzf" style="float: right;"></span>
		                                <p>有效期：<span style="color:orangered;font-weight:bold;">12个月</span></p>
		                        </div>
		                      </li>
		                      <li class=" checkboxclas" id="cos10"  style="padding: 0.6em 0;padding-left: 2em;border-bottom: 1px solid #ccc; ">
		                        <div class="check">
		                                <input type="radio"  name="xz0" class="check0" id="ygyu"/>
		                                <div class="radio_img"></div>
		                        </div>
		                        <div class="xz2">
		                                <span  class="wg_onemonth" style="float: right;"></span>
		                                <p>有效期：<span style="color:orangered;font-weight:bold;">1个月</span></p>
		                        </div>
		                      </li>
                              <!-- <li class="xz1 checkboxclas" id="cos13" style="border-bottom:none;">
                                <div class="check">
                                        <input type="radio" checked  name="xz0" class="check0" id="oneyear" />
                                         
                                        <div class="radio_img"></div>
                                </div>
                                <div class="xz2">
                                        <span  class="sgyn" style="float: right;"></span>
                                        <p>有效期：<span style="color:orangered;">至2018年8月31日</span></p>
                                </div>
                             </li>
                             <li class="xz1 checkboxclas" id="cos18" style="border-bottom:none;">
                                <div class="check">
                                        <input type="radio"  name="xz0" class="check0" id="twoyear"/>
                                        <div class="radio_img"></div>
                                </div>
                                <div class="xz2">
                                        <span  class="sgln" style="float: right;"></span>
                                        
                                        <p>有效期：<span style="color:orangered;">至2019年8月31日</span></p>
                                </div>
                             </li>
                             <li class="xz1 checkboxclas" id="cos19">
                                <div class="check">
                                        <input type="radio"  name="xz0" class="check0" id="threeyear"/>
                                        <div class="radio_img"></div>
                                </div>
                                <div class="xz2">
                                        <span  class="sgsn" style="float: right;"></span>
                                    
                                        <p>有效期：<span style="color:orangered;">至2020年8月31日</span></p>
                                </div>
                             </li> -->
                          
                               
                           
                   <?php }else if($ykt==10) { ?>
                <div class="jx_box">
	                <li class="xz2 xz1 yw_1 title_style">        	
	                	<div class="check_img toggle_this ">
	                		<h2>家校互动:<span class="ykt_total" style="font-weight:normal;"></span> </h2>

	                	</div>
	                	
	                </li>
				    <li class="xz1" id="cos0">
						<div class="check">
							<input type="checkbox" name="xz0" class="check0" value="1"  />
							<div class="check_img"></div>
						</div>
						<div class="xz2">
					
							<span class="danj" style="float: right;"></span>
				
							<h3>家校沟通
						
							</h3>
							<p>可与老师随时在线沟通（通过文字、图片、语音、视频等多种方式了解学生在校情况），不限移动、联通、电信。每月3元</p>
						</div>
					</li>
			   </div>
			<!--    <div class="yw" style="background-color: #eee;"></div> -->
			   <div class="ykt_box " >
				<li  class="xz2 xz1 yw_1 title_style">
					<div class="check_img toggle_this check_toggle">
						<h2>一卡通业务:<span id="ykt_remind">(该项全选10元/月，100元/年)</span></h2>
					
					</div>
				
				</li>
				<li class="xz1" id="cos1">
					<div class="check">
						<input id="ischange" type="checkbox" name="xz0" class="check0"  />
						<div class="check_img"></div>
					</div>
					<div class="xz2">
				
						<span class="danj" style="float: right;"></span>
				
						<h3>平安通知</h3>
						<p>家长可实时收到学生进出校及进出宿舍的通知。每月3元，一年30元。</p>
					</div>
				</li>			
				<li class="xz1" id="cos2">
					<div class="check">		
						<input type="checkbox" name="xz0" class="check0"  />
						<div class="check_img"></div>								
					</div>
					<div class="xz2">
				
						<span  class="danj" style="float: right;"></span>
					
						<h3>亲情电话</h3>
						<p>学生可使用学生证无限量免费和家长打电话，号码不分移动、联通、电信。每月5元，一年50元。</p>
					</div>
				</li>
				<li class="xz1" id="cos3">
					<div class="check">
						<input type="checkbox" name="xz0" class="check0" />
						<div class="check_img"></div>
					</div>
					<div class="xz2">				
					   <span class="danj" style="float: right;"></span>													
					   <h3>校园消费</h3>							
					   <p>可作餐卡、水卡、购物卡等使用，能及时微信充值。家长能及时收到消费信息和余额。每月5元，一年50元</p>										
					</div>
				</li>	
			<!-- 	</div>	 -->		
                   <?php }?>
             
			</ul>
		</div>
		<div class="sj">
            <?php if($ykt ==0 || $ykt ==9 || $ykt==6 || $ykt==10){?>
            <?php if($ykt ==0 || $ykt==10){?>
			<button type="button" class="btn1" style="padding:1px 4px;">
				12个月
				<input type="radio" name="time" checked="checked" class="timec"  id="yxni"/>
			</button>
			<button type="button" class="btn1">
				6个月
				<input type="radio" name="time"  class="timec"  id="yxqi"/>
			</button>
			<?php }else if($ykt ==9){ ?>
			<button type="button" class="btn1" style="padding:1px 4px;width:38%;">
				至2018年8月31日
			    <input type="radio" name="time"  class="timec"  id="specialday"/>
			</button>
			<button type="button" class="btn1" style="padding:1px 4px;">
				免费体验
				<input type="radio" name="time" checked="checked" class="timec free"  id="onemonth"/>
			</button>
			<?php }elseif($ykt ==6){ ?>
			<button type="button" class="btn1" style="padding:1px 4px;width:38%;">
				至2018年8月31日
			    <input type="radio" name="time" checked  class="timec"  id="specialday"/>
			</button>
			<button type="button" class="btn1" style="display:none;">
				6个月
				<input type="radio" name="time"  class="timec"  id="yxqi"/>
			</button>
			<?php }?>
			<button type="button" class="btn1">
				1个月
				<input type="radio" name="time"  class="timec"  id="ygyu"/>
			</button>
      
            <?php }?>
		   
		</div>
              <?php if($ykt ==0 || $ykt ==9 || $ykt==6 ||$ykt==10){?>
		<div class="ts">
                   
			已选套餐： <span id="tc"></span>
			<p class="tsxj">							
				<?php if($ykt ==0){?>
				套餐每月价格 <span id="ygm"></span> 元，无优惠;<br />
				套餐学期原价 <span id="yhj"></span> 元，优惠 <span id="byh"></span> 元；<br />
			
				套餐学年原价 <span id="nyhj"></span> 元，优惠 <span id="nyh"></span> 元。
				<?php }else if($ykt ==9 || $ykt==6){ ?>
				套餐每月价格 <span id="ygm"></span> 元;<br />
				套餐至2018年8月31日原价 <span id="nyhj"></span> 元，优惠 <span id="nyh"></span> 元。
				<?php }?>
			</p>
			<p class="price">优惠价：<span id="nowp"></span></p>
                
		</div>
             <?php }?>
		<div class="footer">
			<a id="zongjia">确认支付</a>
		</div>
		<input type="hidden" id="yktzf" value="<?php echo $ykt?>">
	<input type="hidden" id="path" value="<?php echo URL_PATH?>">
	<input type="hidden" id="trade_name" value="<?php echo $nxinxi?>">
	<input type="hidden" id="openid" value="<?php echo \yii::$app->view->params['openid']?>">
	<input type="hidden" id="endtime" value="<?php echo $endtime?>">
	<input type="hidden" id="sid" value="<?php echo $sid?>">
	<script type="text/javascript">
		$(".free").click(function(){
			$(".check input[type='checkbox']").attr({'checked':'checked'});
		})
		function mapAllParam(type){
			var sid=$("#sid").val();
			var param = {};
			var one=$("input[type='checkbox']").eq(0).is(':checked');//四中套餐的选中状态
			var two=$("input[type='checkbox']").eq(1).is(':checked');
			var three=$("input[type='checkbox']").eq(2).is(':checked');
			var four=$("input[type='checkbox']").eq(3).is(':checked');
			var xqxn = $('.btn1 input[type="radio"]:checked').attr("id");//判断是学期还是学年还是一个月
            var ykt=$("#yktzf").val(); 
			zfzl=((one==true?"|pa":"|npa")+(two==true?"-jx":"-njx")+(three==true?"-qq":"-nqq")+(four==true?"-ck-":"-nck-")+(xqxn));//支付种类
            if(ykt==0 || ykt==9 || ykt==6){
            	total = Number($("#nowp").html().substr(1,5));
                // total = parseInt($("#nowp").html().substr(1,5)); 
            }else if(ykt==2 ){
            	var year=$('.checkboxclas>.check>input[type="radio"]:checked').attr("id");
            	zfzl="|pa-jx-qq-ck-"+year;
                // zfzl="|pa-jx-qq-ck-oneyear";//支付种类
                total=Number($('.checkboxclas>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));
                // total = Number($(".wgzf").html().substr(1,5));//舞钢一卡通资费总价 
            }else if(ykt==3){
                zfzl="|pa-jx-qq-nck-yxni";//支付种类
                total = Number($(".zczf").html().substr(1,5));//柘城县学苑中学一卡通资费总价
            }else if(ykt==4 || ykt==7 || ykt==5  || ykt==8){
            	var year=$('.checkboxclas>.check>input[type="radio"]:checked').attr("id");
            	zfzl="|pa-jx-qq-ck-"+year;
            	total=Number($('.checkboxclas>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));                       	
            }else if(ykt==1){
            	var year=$('.ssygzf>.check>input[type="radio"]:checked').attr("id");
            	zfzl="|pa-jx-qq-ck-"+year;
            	total=Number($('.ssygzf>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));
            }else if(ykt==10){
            	total = Number($("#nowp").html().substr(1,5));
            	zfzl=((two==true?"|pa":"|npa")+(one==true?"-jx":"-njx")+(three==true?"-qq":"-nqq")+(four==true?"-ck-":"-nck-")+(xqxn));//支付种类
            }          
            // total = 0.01;//总价   
			param.zfzl = zfzl;
			param.total = total;
			param.trade_name = $("#trade_name").val();
			param.openid = $("#openid").val();
			if(sid=='56775'){
				alert("暂未开通");
				return false;
			}

			return param;
		}
		$(function(){ 		                  
                   // $(".yktzf").eq(0).html("&yen;"+100);
                    $(".wgzf").eq(0).html("&yen;"+100);
                    $(".zczf").eq(0).html("&yen;"+90);
                    //商水一高资费
                    $(".ssyg1").eq(0).html("&yen;"+70);
                    $(".ssyg2").eq(0).html("&yen;"+160);
                    //三高资费
                    $(".sgyn").eq(0).html("&yen;"+80);
                    $(".sgln").eq(0).html("&yen;"+170);
                    $(".sgsn").eq(0).html("&yen;"+260);
                    //获嘉一中
                    $(".hjzf").eq(0).html("&yen;"+190);
                     //新区实验学校限时特价
                    $(".tejia").eq(0).html("&yen;"+50);
                    //舞钢一个月
                    $(".wg_onemonth").eq(0).html("&yen;"+10);
		            var url = $("#path").val()+"/zfend/redirectpay";
                    var ykt=$("#yktzf").val(); 
                    var endtime=$("#endtime").val();
                    var sid=$("#sid").val();
                    var ts = Math.round(new Date().getTime()/1000).toString();                    
		            $("#zongjia").on("click",function(){
                            if(ykt ==0 || ykt ==9 || ykt==6 || ykt ==10){
                               total = Number($("#nowp").html().substr(1,5));
                               // total = parseInt($("#nowp").html().substr(1,5)); 
                            }else if(ykt ==2){
                               total=Number($('.checkboxclas>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));
                               // total = Number($(".wgzf").html().substr(1,5)); 
                            }else if(ykt==3){
                               total = Number($(".zczf").html().substr(1,5)); 
                            }else if(ykt==4 || ykt==7 || ykt==5  || ykt==8){
                            	total=Number($('.checkboxclas>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));                             
                            }else if(ykt ==1){
							    total=Number($('.ssygzf>.check>input[type="radio"]:checked').parent().siblings().find('span').html().substr(1,5));
                            }
			  
//                              
							if(total <= 0 || (!total)){
								alert("请选择您要购买的服务！");
								return false;
							}
							if(sid=='56684'){
								alert("该校此服务本学年免费！");
								return false;
							}
						
							if(endtime>parseInt(ts) && sid=='56758'){
								if(!window.confirm('本学期您的服务已经开通，请确认是否续缴？')){
									return false;
								}
											
							}
							$.getJSON(url,mapAllParam("JSAPI"),function(data){
								if(data.retcode==0){
									document.write("正在进入支付页面请您稍等待……");
									            window.clear;						
									window.location = data.url;
								}else{
									alert(data.retmsg);
								}
							});
					});
               });
	</script>
	<script>
		var $zsz = <?php echo json_encode($tc) ?>;
		var $pass = <?php echo json_encode($pass) ?>;
		var $sid = <?php echo json_encode($sid) ?>;
		console.log($pass);
		for(var i in $zsz){
			for(var j in $zsz[i]){
				if ($zsz[i].hasOwnProperty(j)) { //filter,只输出man的私有属性
					console.log(j,":",$zsz[i][j]);
					$zsz[i][j] = $zsz[i][j]*1;
				};
			}
		}
//		$zsz.each(function(k,v){
//			console.log(v);
//		});
	</script>
	 <?php if($ykt==10){?>
	 	<script type="text/javascript" src="/js/zfend.js" ></script>
	 <?php }else{?>
		<script type="text/javascript" src="/js/index.js" ></script>
	 <?php }?>
	</body>

</html>


