		<link rel="stylesheet" href="/css/bootstrap.min.css" />
		<script type="text/javascript" src="/js/bootstrap.min.js" ></script>
		<style>
			body{
				background: url('/img/kfbg.jpg') no-repeat center fixed;
				background-size: cover;
			}
			img{
				display: block;
			}
			.clearfix{
				clear: both;
			}
			.f_header{
				width: 700px;
				margin: 150px auto 20px;
			}
			.f_header img{
				width: 100%;
			}
			.cont{
				background-color: #E8EFF5;
				width: 100%;
				padding: 50px;
			}
			.cont_ct{
				width: 60%;
				width: 750px;
				margin: 0 auto;
			}
			.f_right{
				min-width: 400px;
				min-height: 240px;
			}
			.f_right h3{
				padding: 10px;
			}
		</style>
		<div class="f_header">
			<img src="/img/kf.png" />
		</div>
		<div class="cont">
			<div class="cont_ct">
		  	<div class="pull-left">
		  		<img src="/img/kftb.png" />
		  	</div>
		  	<div class="pull-right f_right">
			  	<form class="form-horizontal" role="form" method="post">
				  	<h3 class="text-center">登录</h3>
			    	<div class="form-group">
			  	  	<label class="col-xs-3 control-label">
				    		用户名：
			  	  	</label>
				    	<div class="col-xs-9">
				    		<input name="LoginForm[username]" class="form-control" autofocus="autofocus" type="text" placeholder="用户名" />
				    	</div>
			    	</div>
			    	<div class="form-group">
			  	  	<label class="col-xs-3 control-label">
				    		密&nbsp;&nbsp;&nbsp;码：
			    		</label>
				    	<div class="col-xs-9">
				    		<input class="form-control" name="LoginForm[password]" type="password" placeholder="请输入密码" />
				    	</div>
			    	</div>
			    	<div class="form-group">
			  	  	<label class="col-xs-9 control-label"></label>
				    	<div class="col-xs-3">
				    		<input type="hidden" name="LoginForm[rememberMe]" value="0">
				    		<input class="" name="LoginForm[rememberMe]" type="checkbox" value="1"/>记住密码
				    	</div>
			    	</div>
			    	<div class="form-group">
			  	  	<label class="col-xs-3 control-label"></label>
				    	<div class="col-xs-9">
				    		<input class="hidden" name="_csrf-kefu" value="<?php echo Yii::$app->request->csrfToken?>" />
				    		<input class="btn btn-primary form-control" type="submit" value="登录" />
				    	</div>
            </div>
			    </form>
			  </div>
			  <div class="clearfix"></div>
			</div>
		</div>
