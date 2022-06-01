<?php session_start() ?>
<?php
include './db/connect_db.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>管理员界面</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap style -->
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="./css/common.css" />
    <link rel="stylesheet" href="./css/font-awesome.min.css" />
</head>

<body class="body-admin">
  <?php
  if (!$_SESSION) {
    echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
  } else if ($_SESSION['name'] != 'admin') {
    echo "<script>alert('您不是管理员'); window.location.href='main.php';</script>";
  } else {
    echo "欢迎你，管理员{$_SESSION['name']}";
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




    <?php
    $col = connect_db();

    echo '<p align="center">';
    echo '当前总订单数:';
    $all_orders_query = "select count(*) as total_order
from orderlist
where OR_Status = 1;";
    $result = mysqli_query($col, $all_orders_query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo "{$row['total_order']}";


    echo '<br>总票价:';
    $all_price_query = "select sum(OR_Price) as total_price
from orderlist
where OR_Status = 1;";
    $result = mysqli_query($col, $all_price_query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo "{$row['total_price']}";
    echo '</p>';
    echo '<p align="center">';
    echo '<br>最热车次排序<br>';
    echo '</p>';
    $hot_trainID_query = "select OR_TrainID,count(*) as number
from orderlist
where OR_Status = 1
group by OR_TrainID
order by number desc limit 10;";
    $result = mysqli_query($col, $hot_trainID_query);
    echo '<table border="3" style="margin-left:auto; margin-right:auto;"><tr>
      <td>车次</td>      <td>数量</td>
      </tr>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo "<tr><td>{$row['OR_TrainID']} </td> "
        .  "<td>{$row['number']} </td> "
        . "</tr>";
    }
    echo "</table>";

    echo '<p align="center">';
    echo '<br>用户列表如下:<br>';
    echo '</p>';
    $result = mysqli_query($col, "SELECT * FROM users");

    echo '<table border="1" style="margin-left:auto; margin-right:auto;"><tr>
<td>用户名</td> <td>姓名</td> <td>手机号</td> <td>密码</td> <td>操作</td>
</tr>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo "<form method='post' action='admin.php'>"
        . "<tr><td> <input type='text' value='{$row['U_UserName']}'  name='test' readonly='readonly'/></td> "
        . "<td>{$row['U_RealName']} </td> "
        . "<td>{$row['U_Phone']} </td> " .  "<td>{$row['U_PassWord']} </td> "
        . "<td><input type='submit' value='查看订单' /></td>" . "</tr>"
        . "</form>";
    }
    echo "</table>";



    if ($_POST) {
      $user_name = $_POST['test'];
      echo '<p align="center">';
      echo '用户' . $user_name;
      echo '订单信息:';
      echo '</p>';
      $user_order_query = "
select *
from orderlist
where OR_UserName = '{$user_name}';";
      $result = mysqli_query($col, $user_order_query);
      echo '<table border="1" style="margin-left:auto; margin-right:auto;"><tr>
<td>订单号</td> <td>用户名</td> <td>列车号</td> <td>列车日期</td> <td>订单时间</td>
<td>座位类型</td> <td>始发站</td> <td>结束站</td> <td>价格</td>
<td>是否联票单</td> <td>联票单号</td> <td>订单状态</td>
</tr>';
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "<tr>"
          . "<td>{$row['OR_ID']} </td> " .  "<td>{$row['OR_UserName']} </td> "
          . "<td>{$row['OR_TrainID']} </td> " . "<td>{$row['OR_TrainDate']} </td>"
          . "<td>{$row['OR_Time']} </td> "  . "<td>{$row['OR_SeatType']} </td> "
          . "<td>{$row['OR_StartStation']} </td> "  . "<td>{$row['OR_EndStation']} </td> "
          . "<td>{$row['OR_Price']} </td> "  . "<td>{$row['OR_Together']} </td> "
          . "<td>{$row['OR_TogetherID']} </td> "  . "<td>{$row['OR_Status']} </td> "
          . "</tr>";
      }
      echo "</table>";

  
    }
    ?>

    <?php
    ?>

</body>

</html>