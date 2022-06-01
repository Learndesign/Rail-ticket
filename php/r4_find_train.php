<?php session_start() ?>
<?php
  include './db/connect_db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>车次查询</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- load stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">  <!-- Google web font "Open Sans" -->
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">                <!-- Font Awesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css">                                      <!-- Bootstrap style -->
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
                                <form action="r4_find_train.php" method="post" class="tm-search-form tm-section-pad-2">
                                    <div class="form-row tm-search-form-row">
                                    <div class="form-group tm-form-element tm-form-element-2">
                                            <i class="fa fa-train fa-2x tm-form-element-icon"></i>
                                            <input name="TrainId" type="text" required class="form-control" id="inputtext" placeholder="输入车次:如G2832">
                                        </div>
                                        <div class="form-group tm-form-element tm-form-element-2">
                                            <i class="fa fa-calendar fa-2x tm-form-element-icon"></i>
                                            <input name="TrainDate" type="date" required class="form-control" id="inputDATE" placeholder="输入日期:如2022-5-8">
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
   <h5 align="center">结果如下</h5>
    <?php   //本php模块用来查询车次信息
    //这里显示查询信息

    /*当前时间显示 */
    date_default_timezone_set("PRC");
    $today = date("Y-m-d  h:i:s  ");
    $tt_today = date("Y-m-d ");
    /*表单处理 */
    if ($_POST) {
        $train_id   = $_POST["TrainId"];
       // echo $train_id;
        $train_date  = $_POST["TrainDate"];
       // echo $train_date;
        $col = connect_db();
        $SeatType = array('YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
        $i = 0;
        foreach($SeatType as $temp) {
            $i++;
     echo '<h6 align="center">';
     echo  $temp;
     echo '</h6>';
    $query_first_station = "   
     select
    begin_tr.TR_StationID as'到达站',
    begin_tr.TR_ArriveTime as'到达时间',
    begin_tr.TR_GoTime as '出发时间',
    sum(pass_tr.TR_Price{$temp}) as'票价',
    min(pass_ts.TS_Left{$temp}) as'票余额'
    from
    trainseat begin_ts,
    trainseat pass_ts,
    trainroute begin_tr,
    trainroute pass_tr
    where
    begin_tr.TR_TrainID = begin_ts.TS_TrainID and
    begin_tr.TR_RouteID = begin_ts.TS_RouteID and
    begin_tr.TR_TrainID = '{$train_id}' and begin_ts.TS_Date = '{$train_date}' and
    pass_ts.TS_Date = begin_ts.TS_Date and pass_ts.TS_RouteID =
    pass_tr.TR_RouteID and
    pass_tr.TR_TrainID = pass_ts.TS_TrainID and
    pass_tr.TR_RouteID <= begin_tr.TR_RouteID and
    pass_tr.TR_Price{$temp} >=0 and
    pass_tr.TR_TrainID = begin_tr.TR_TrainID
    group by
    begin_tr.TR_RouteID
    order by
    begin_tr.TR_RouteID limit 1;";
            //echo $query_first_station;
    $result = mysqli_query($col, $query_first_station);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if($row){
        $start_station = $row['到达站'];
            //$SeatType =array('YZ','RZ','YWS','YWZ','YWXX','RWS','RWZ');
            $query_get_train = "
    select
    begin_tr.TR_StationID as'到达站',
    begin_tr.TR_ArriveTime as'到达时间',
    begin_tr.TR_GoTime as '出发时间',
    if(begin_tr.TR_Price{$temp}!=0,sum(pass_tr.TR_Price{$temp}),0) as'票价',
    if(min(pass_ts.TS_Left{$temp})<0,0,min(pass_ts.TS_Left{$temp})) as'票余额'
    from
    trainseat begin_ts,
    trainseat pass_ts,
    trainroute begin_tr,
    trainroute pass_tr
    where
    begin_tr.TR_TrainID = begin_ts.TS_TrainID and
    begin_tr.TR_RouteID = begin_ts.TS_RouteID and
    begin_tr.TR_TrainID = '{$train_id}' and begin_ts.TS_Date = '{$train_date}' and
    pass_ts.TS_Date = begin_ts.TS_Date and pass_ts.TS_RouteID =
    pass_tr.TR_RouteID and
    pass_tr.TR_TrainID = pass_ts.TS_TrainID and
    pass_tr.TR_RouteID <= begin_tr.TR_RouteID and
    pass_tr.TR_Price{$temp} >=0 and
    pass_tr.TR_TrainID = begin_tr.TR_TrainID
    group by
    begin_tr.TR_RouteID
    order by
    begin_tr.TR_RouteID;";

    $result = mysqli_query($col, $query_get_train);
    $SeatType = 1;
            echo '<table border="1" style="margin-left:auto; margin-right:auto;">
                  <tr><td>到达站</td><td>到达时间
                  </td><td>出发时间</td><td>票价</td><td>票余额</td>
                  <td>票类型</td></tr>';
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo "<form method='post' action='one_order.php'>"
                    . "<tr><td> <input type='text' value='{$row['到达站']}'  name='endStation' readonly='readonly'/></td> " . "<td>{$row['到达时间']} </td> "
                    . "<td>{$row['出发时间']} </td> "
                    . "<td><input type='text' value='{$row['票价']}'  name='price' readonly='readonly'/> </td> "
                    . "<input type='hidden' value='{$row['票余额']}'  name='left_ticket' readonly='readonly'/>  "
                    . "<td><input type='submit' value='{$row['票余额']}'  /> </td> " 
                    . "<td><input type='text' value='{$temp}'  name='SeatType' readonly='readonly'/> </td>"
                    . "</tr>"
                    . "</form>";
            }
            $_SESSION['trainid'] = $train_id;
            $_SESSION['train_date'] = $train_date;
            $_SESSION['time']    = $today;
            //$_SESSION['SeatType'] = 1;
            $_SESSION['startStation'] = $start_station;
            $_SESSION['together']     = 0;
            echo '</table>';
        }
        else
        echo "<h6 align='center'>无此车次或不卖这天的票</h6>";
    }
    }
    ?>
    <form method='post' action='cancle_order.php'></form>
</body>

</html>