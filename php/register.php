
<!DOCTYPE html>
<html>

<!-- <head>
  <meta charset="UTF-8" />
  <title>注册界面</title>
  <style>
    #header {
      background-color: white;
      color: black;
      text-align: center;
    }

    #nav {
      line-height: 30px;
      background-color: #eeeeee;
      height: 300px;
      width: 100px;
      float: left;
      padding: 5px;
    }

    #section {
      width: 350px;
      float: left;
      padding: 10px;
    }

    #footer {
      background-color: white;
      color: green;
      clear: both;
      text-align: center;
      padding: 5px;
    }
  </style>
  <script></script>
</head>

<body>
  <div id="header">
    <h1>12306注册界面</h1>
  </div>

  <div id="nav">
    <p>选项</p>
  </div>

  <div id="section">
    <a href="sign_in.php">已有账号？点击登录</a>
    <form method="post" action="handle_register.php">
      <p>
        姓名:
        <input type="text" id="realname" name="realname" size="20" placeholder="不超过20个字" required />
      </p>
      <p>
        手机号：<input type="text" pattern=".{11}" title="请输入正确的手机号号" id="phone" name="phone" size="20" placeholder="11位手机号"
          required />
      </p>
      <p>
        用户名：<input type="text" pattern=".{1,20}" title="请输入正确的用户名" id="username" name="username" size="20"
          placeholder="不超过20个数字字母" required />
      </p>
      <p>
        密码：<input type="password" pattern=".{1,20}" title="请输入正确的密码" id="password" name="password" size="20"
          placeholder="不超过20个数字字母" required />
      </p>
      <p><input type="submit" /></p>
    </form>
  </div>
  <script></script> -->
  <head>
		<meta charset="utf-8">
		<title>注册界面</title>
    <style>
      #footer {
        /* background-color: white; */
        color: green;
        clear: both;
        text-align: center;
        padding: 5px;
        position:absolute;
        left:50%;
        bottom:0;
      }
    </style>

		<link rel="stylesheet" href="./css/reset.css"/>
		<link rel="stylesheet" href="./css/common.css"/>
		<link rel="stylesheet" href="./css/font-awesome.min.css"/>
	</head>
  
	<body class="body-register">
		<div>
			<div class="content">
				<div class="logo"></div>
				<div class="login_box">	
					<div class="login_form">
						<div class="login_title">
							注册
            </div>
              <form action="handle_register.php" method="post">
                <div class="ececk_warning"><span>姓名</span></div>
                <div class="form_text_ipt">
                  <input type="text" id="realname" name="realname" size="20" placeholder="姓名/不超过20个字" required />
                </div>
                <div class="ececk_warning"><span>手机号</span></div>
                <div class="form_text_ipt">
                  <input type="text" pattern=".{11}" title="请输入正确的手机号" id="phone" name="phone" size="20" placeholder="手机号/11位手机号" required />
                </div>
                <div class="ececk_warning"><span>用户名</span></div>
                <div class="form_text_ipt">
                  <input type="text" pattern=".{1,20}" title="请输入正确的用户名" id="username" name="username" size="20" placeholder="用户名/不超过20个数字字母" required />
                </div>
                <div class="ececk_warning"><span>密码</span></div>
                <div class="form_text_ipt">
                  <input type="password" pattern=".{1,20}" title="请输入正确的密码" id="password" name="password" size="20"placeholder="密码/不超过20个数字字母" required />
                </div>
                <div class="form_btn">
                  <button type="submit">注册</button>
                </div>
                <div class="form_reg_btn">
                  <span>已有帐号？</span><a href="sign_in.php">马上登录</a>
                </div>
              </form>
              <div class="other_login">
                <!-- no need -->
              </div>
					</div>
				</div>
			</div>
      <div style="text-align:center;"></div>
    </div>  
</body>


</html>