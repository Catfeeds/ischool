<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>正梵掌上学校重置密码</title>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/mystyle.css" />
		<script type="text/javascript" src="js/jquery-1.12.3.js" ></script>
		<script type="text/javascript" src="js/bootstrap.min.js" ></script>
		<style>
		  .lg_nav a{
		  	font-size: 16px;
		  	padding-right: 20px;
		  }
			.zc_li{
				margin: 15px auto;
			}
			.lg_cont{
				width: 100%;
				padding-top: 50px;
			}
                        .lg_cont>h5{
                                text-align:center;
                        }
			.cz_mm{
				background-color: white;
				width: 90%;
				margin: 50px auto 0px;
				text-align: center;
				padding-bottom: 5px;
			}
			#chongzhi{
				margin: 30px 30px;
			}
			.cz_mm>h4{
				background-color: #ccc;
				line-height: 40px;
				color: #444;
			}
		</style>
	</head>
	<body>
    <div class="lg_cont">
       <h5><?=$message?></h5>
     	<div class="cz_mm">
     		<h4>重置密码</h4>
     		<form id="chongzhi" class="tab-pane" action="/utils/czmm" method="post" onsubmit="return checkpwd()">
          <div class="input-group zc_li">
            <span class="input-group-addon">
              <span class="	glyphicon glyphicon-lock"></span>
            </span>
            <input class="form-control" type="password" placeholder="请输入新密码"  name="newPwd" id="newPwd" />
          </div>
          <div class="input-group zc_li">
            <span class="input-group-addon">
              <span class="	glyphicon glyphicon-lock"></span>
            </span>
            <input class="form-control"  type="password" name="Confirm_pwd" id="Confirm_pwd" placeholder="请输入确认密码" />
          </div>
          <div>
          	<input class="btn btn-primary form-control dl" type="submit" value="保存" />
          </div>
          <input type="hidden" name="openid" value="<?=$openid?>">
          </form>
     	</div>
    </div>
	</body>
</html>

<script>
    function checkpwd(){
        var pwd = $("#pwd").val();
        var newpwd = $("#newPwd").val();
        var Confirm_pwd = $("#Confirm_pwd").val();
        if (pwd == "" || newpwd =="" || Confirm_pwd ==""){
            alert("请确认信息填写完整！");
            return false;
        }
        if (newpwd != Confirm_pwd){
            alert("确认密码填写不正确！");
            return false;
        }
        return true;
    }
</script>
