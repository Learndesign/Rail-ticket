<?php session_start() ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap style -->
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="./css/reset.css" />
    <link rel="stylesheet" href="./css/common.css" />
    <link rel="stylesheet" href="./css/font-awesome.min.css" />
</head>

<body class="body-handle-sign">
    <?php
    if (!$_SESSION) {
        echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
    }
    ?>
    <?php
    date_default_timezone_set("PRC");
    // echo "当前时间为：";
    $today = date("Y-m-d  h:i:s");
    // echo $today;
    ?>
    <div class="tm-main-content" id="top">
        <div class="tm-top-bar-bg-1"></div>
        <div class="tm-top-bar" id="tm-top-bar">
            <!-- Top Navbar -->
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-expand-lg narbar-light">
                        <a class="navbar-brand mr-auto" href="#">
                            <img src="img/logo.png" alt="logo" height="50px">
                            12306
                        </a>
                        <div id="mainNav" class="collapse navbar-collapse tm-bg-white">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="main.php">首页<span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="admin.php">管理员选项</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="r8_find_order.php">用户：
                                        <?php
                                        echo "{$_SESSION['name']}"
                                        ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="log_out.php">退出登录</a>
                                </li>
                                <li class="nav-item">
                                    <div class="nav-link" id="datetime">
                                        <script>
                                            setInterval("document.getElementById('datetime').innerHTML=new Date().toLocaleString();", 1000);
                                        </script>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <div  id="tm-section-6">
            <div class="content">
                <div class="logo"></div>
                <div class="login_box">
                    <div class="login_form">
                        <p id="image_logo"><img src="./img/fly.png"></p>
                        <div class="login_title">
                            选项
                        </div>
                        <form action="r4_find_train.php" method="post">
                            <div class="form_btn-1">
                                <button type="submit">查询具体车次</button>
                            </div>
                        </form>
                        <form action="r5_find_area.php" method="post">
                            <div class="form_btn-1">
                                <button type="submit">查询两地之间车次</button>
                            </div>
                        </form>
                        <form action="r8_find_order.php" method="post">
                            <div class="form_btn-1">
                                <button type="submit">我的订单详情</button>
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

                <?php   //本模块完成订单查询相关功能

                ?>
</body>

</html>