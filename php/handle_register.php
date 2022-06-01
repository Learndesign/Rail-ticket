<!DOCTYPE html>
<?php
  include './db/connect_db.php';
?>
<html>

<body>

     <?php
     $realname   = $_POST["realname"];
     echo $realname;
     $phone     = $_POST["phone"];
     echo $phone;
     $username   = $_POST["username"];
     echo $username;
     $password   = $_POST["password"];
     echo $password;


     $col = connect_db();

     $find_query = "select U_Username from users where U_Username = '{$username}'";
     $findresult = mysqli_query($col, $find_query);
     if (!$findresult) {
          printf(mysqli_error($col));
          exit();
     }
     $row = mysqli_fetch_row($findresult);
     if ($row) {
          echo "用户已经存在";
          echo " <a href=\"sign_in.php\">点我登录</a></br>
          <a href=\"register.php\">点我注册</a>";
     } else {
          $query_insert = "INSERT INTO users VALUES('{$username}','{$realname}','{$phone}','{$password}')";

          $insert_ok = mysqli_query($col, $query_insert);
          if ($insert_ok)
               echo ("insert ok");
          else
               echo ("insert wrong,手机号或银行卡号已存在");
     }
     mysqli_close($col);
     ?>
     <script>

     </script>

     <a href="register.php">back</a>
</body>

</html>