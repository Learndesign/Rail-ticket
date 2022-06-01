<?php session_start() ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>主页面</title>
</head>

<body>
    <?php
    if (!$_SESSION) {
        echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
    } else {
        echo "下线成功,{$_SESSION['name']}<br>";
    }
    ?>
    <?php
    date_default_timezone_set("PRC");
    echo "当前时间为：";
    $today = date("Y-m-d  h:i:s ");
    echo $today.'<br>';
    ?>

    <?php   //本模块完成订单查询相关功能
     session_destroy();
    ?>
    <a href="sign_in.php">点我登录</a>
</body>

</html>