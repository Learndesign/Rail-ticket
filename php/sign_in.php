<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>登录界面</title>
  <link rel="stylesheet" href="./css/login.css">

</head>

<body class="body-login">
  <div class="top-bar-bg-1"></div>
  <div id="header">
    <h1 align="center"><img src="img/logo.png" alt="logo" height="50px">12306登录</h1>
  </div>


  <!-- <div id="section">
    <a href="register.php">没有账号？点击注册</a>
    <form method="post" action="handle_sign_in.php">
      <p>用户名：<input type="text" pattern=".{1,20}"   title="请输入正确的用户名" id="username" name="username" size="20" placeholder="不超过20个数字字母" required /></p>
      <p>密码：<input type="password" pattern=".{1,20}" title="请输入正确的密码" id="password" name="password" size="20" placeholder="不超过20个数字字母" required /></p>
      <p><input type="submit" value="登录" /></p>
    </form>
  </div> -->
  <div class="login_frame">
    <p id="image_logo"><img src="./img/fly.png"></p>
    <form method="post" action="handle_sign_in.php">
      <p><label class="label_input">用户名</label><input type="text" class="text_field" pattern=".{1,20}" title="请输入正确的用户名" id="username" name="username" size="20" placeholder="不超过20个数字字母" required /></p>
      <p><label class="label_input">密码</label><input type="password" class="text_field" pattern=".{1,20}" title="请输入正确的密码" id="password" name="password" size="20" placeholder="不超过20个数字字母" required /></p>
      <div id="login_control">
        <input type="submit" value="登录" id="btn_login" />
        <a href="register.php"><input type="button" value="注册" id="btn_zhuce" /></a>
      </div>
    </form>
  </div>

  <div class="footer">
    <p>数据库研讨课2022春季</p>
  </div>
</body>

</html>