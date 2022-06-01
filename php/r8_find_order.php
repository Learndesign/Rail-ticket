<?php session_start() ?>
<?php
include './db/connect_db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>订单查询</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap style -->
    <link rel="stylesheet" href="css/tooplate-style.css">
</head>

<body>
    <?php
    if (!$_SESSION) {
        echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
    }
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
        <div class="tm-section tm-bg-img" id="tm-section-6">
            <div class="tm-bg-white ie-container-width-fix-2">
                <div class="container ie-h-align-center-fix">
                    <div class="row">
                        <div class="col-xs-12 ml-auto mr-auto ie-container-width-fix">
                            <form action="r8_find_order.php" method="post" class="tm-search-form tm-section-pad-2">
                                <div class="form-row tm-search-form-row">
                                    <div class="form-group tm-form-element tm-form-element-2">
                                        <i class="fa fa-calendar fa-2x tm-form-element-icon"></i>
                                        <input name="BeginDay" type="date" required class="form-control" id="inputDATE" placeholder="Check In">
                                    </div>
                                    <div class="form-group tm-form-element tm-form-element-2">
                                        <i class="fa fa-calendar fa-2x tm-form-element-icon"></i>
                                        <input name="EndDay" type="date" required class="form-control" id="inputDATE" placeholder="Check In">
                                    </div>
                                    <div class="form-group tm-form-element tm-form-element-2">
                                        <button type="submit" class="btn btn-primary tm-btn-search">查询</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php
        //本模块用来处理指定日期的订单查询
        if ($_POST) {
            $col = connect_db();
            $date_one = $_POST['BeginDay'];
            $date_two = $_POST['EndDay'];
            if (!$date_one || !$date_two) {
                echo "<script>alert('请输输入完整的日期区间'); window.location.href='r8_find_order.php';</script>";
            }
            echo '<h5 align="center">';
            echo "{$_SESSION['name']},你在{$date_one}到{$date_two}之间的订单如下:<br>";
            echo '</h5>';
            $find_order_query = "
    select
    *
    from
    orderlist
    where
    OR_UserName = '{$_SESSION['name']}' and
    OR_Time between '{$date_one}' and '{$date_two}';";
            //printf("< %s br/>", $find_order_query);
            $result = mysqli_query($col, $find_order_query);

            echo '<table border="1" style="margin-left:auto; margin-right:auto;"><tr>
<td>订单号</td> <td>用户名</td> <td>列车号</td> <td>列车日期</td> <td>订单时间</td>
<td>座位类型</td> <td>始发站</td> <td>结束站</td> <td>价格</td>
<td>是否联票单</td> <td>联票单号</td> <td>订单状态</td>  <td>操作</td>
</tr>';
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo  "<form method='post' action='delete_order.php'>"
                    . "<tr>"
                    . "<td><input type='text' value='{$row['OR_ID']}'  name='or_id' readonly='readonly'/></td> "
                    . "<td>{$row['OR_UserName']} </td> "
                    . "<td>{$row['OR_TrainID']} </td> " . "<td>{$row['OR_TrainDate']} </td>"
                    . "<td>{$row['OR_Time']} </td> "  . "<td>{$row['OR_SeatType']} </td> "
                    . "<td>{$row['OR_StartStation']} </td> "  . "<td>{$row['OR_EndStation']} </td> "
                    . "<td>{$row['OR_Price']} </td> "
                    . "<td><input type='text' value='{$row['OR_Together']}'  name='together' readonly='readonly'/></td> "
                    . "<td>{$row['OR_TogetherID']} </td> "  . "<td>{$row['OR_Status']} </td> "
                    . "<td><input type='submit' value='取消订单' /></td>"
                    . "</tr>"
                    . "</form>";
            }
            echo "</table>";
        }
        ?>




</body>

</html>