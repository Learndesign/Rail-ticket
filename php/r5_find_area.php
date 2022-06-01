<?php session_start() ?>
<!DOCTYPE html>
<?php
include './db/connect_db.php';
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>两地之间车次查询界面</title>
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

    <body>
        <div class="tm-main-content" id="top">
            <div class="tm-top-bar-bg"></div>
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

            <div class="tm-section tm-bg-img" id="tm-section-1">
                <div class="tm-bg-white ie-container-width-fix-2">
                    <div class="container ie-h-align-center-fix">
                        <div class="row">
                            <div class="col-xs-12 ml-auto mr-auto ie-container-width-fix">
                                <form action="r5_find_area.php" method="post" class="tm-search-form tm-section-pad-2">
                                    <div class="form-row tm-search-form-row">
                                        <div class="form-group tm-form-element tm-form-element-100">
                                            <i class="fa fa-map-marker fa-2x tm-form-element-icon"></i>
                                            <input name="begin_city" type="text" required class="form-control" id="inputCity1" placeholder="起始城市...">
                                        </div>
                                        <div class="form-group tm-form-element tm-form-element-100">
                                            <i class="fa fa-map-marker fa-2x tm-form-element-icon"></i>
                                            <input name="end_city" type="text" required class="form-control" id="inputCity2" placeholder="目的城市...">
                                        </div>
                                    </div>
                                    <div class="form-row tm-search-form-row">
                                        <div class="form-group tm-form-element tm-form-element-100">
                                            <i class="fa fa-calendar fa-2x tm-form-element-icon"></i>
                                            <input name="StartDate" type="date" required class="form-control" id="inputDATE" placeholder="Check In">
                                        </div>
                                        <div class="form-group tm-form-element tm-form-element-100">
                                            <i class="fa fa-calendar fa-2x tm-form-element-icon"></i>
                                            <input name="StartTime" type="time" required class="form-control" id="inputTIME" placeholder="Check In">
                                        </div>
                                    </div>
                                    <div class="form-row tm-search-form-row">
                                        <div class="form-group tm-form-element tm-form-element-2">
                                            <i class="fa fa-2x fa-bed tm-form-element-icon"></i>
                                            <select name="seattype" class="form-control tm-select" id="seat" va>
                                                <option value="YZ">座位类型1(1-7)</option>
                                                <option value="YZ">硬座</option>
                                                <option value="RZ">软座</option>
                                                <option value="YWS">硬卧上铺</option>
                                                <option value="YWZ">硬卧中铺</option>
                                                <option value="YWX">硬卧下铺</option>
                                                <option value="RWS">软卧上铺</option>
                                                <option value="RWX">软卧下铺</option>
                                            </select>
                                        </div>
                                        <div class="form-group tm-form-element tm-form-element-2">
                                            <i class="fa fa-2x fa-bed tm-form-element-icon"></i>
                                            <select name="seattype2" class="form-control tm-select" id="seat1">
                                                <option value="YZ">座位类型1(1-7)</option>
                                                <option value="YZ">硬座</option>
                                                <option value="RZ">软座</option>
                                                <option value="YWS">硬卧上铺</option>
                                                <option value="YWZ">硬卧中铺</option>
                                                <option value="YWX">硬卧下铺</option>
                                                <option value="RWS">软卧上铺</option>
                                                <option value="RWX">软卧下铺</option>
                                            </select>
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
            //返程查询实现
            if ($_POST) {
                echo '<p align="center">';
                echo "返程信息如下:";
                echo '</p>';
                $begin_city = $_POST['end_city'];
                //echo "你的默认返程出发地是:{$begin_city}<br>";
                $end_city   = $_POST['begin_city'];
                //echo "你的默认返程目的地是:{$end_city}<br>";
                $date_temp = $_POST['StartDate'];
                $date_one = date('Y-m-d', strtotime("$date_temp +1 day"));
                //echo "默认返程的出发日期是:{$date_one}<br>";
                //$date_two = date('Y-m-d', strtotime("$date_one +1 day"));    
                $begin_time = '00:00';
                $seattype = $_POST['seattype'];
                $seattype1 = $_POST['seattype2'];
                //echo "默认最早出发时间是:{$begin_time}<br>";
                echo '<table border="1" style="margin-left:auto; margin-right:auto;"><tr>
    <td>返程起点</td> <td>返程终点</td>
    <td>返程日期</td> <td>返程出发时间</td>
    <td>第一段座位类型</td> <td>第二段座位类型</td>  <td>操作</td> 
    </tr>';
                echo "<tr><form method='post' action='r5_find_area.php'>"
                    . "<td><input style='width:120px;'type='text' value='{$begin_city}'  name='begin_city' /></td> "
                    . "<td><input style='width:120px;'type='text' value='{$end_city}'  name='end_city' /></td> "
                    . "<td><input style='width:120px;'type='text' value='{$date_one}'  name='StartDate' /></td> "
                    . "<td><input style='width:120px;'type='text' value='{$begin_time}'  name='StartTime' /></td> "
                    . "<td><input style='width:120px;'type='text' value='{$seattype}'  name='seattype' /></td> "
                    . "<td><input style='width:120px;'type='text' value='{$seattype1}'  name='seattype2' /></td> "
                    . "<td><input type='submit' value='查看返程信息' /></td>" . "</tr>"
                    . "</form>";
                echo "</table>";
            }
            ?>

            <?php   //本php模块用来查询两地之间的车次信息
            //这里显示查询信息
            date_default_timezone_set("PRC");
            // echo "当前时间为：";
            $today = date("Y-m-d  h:i:s  ");
            // echo $today . '<br>';
            $SeatType = array('YS', 'YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
            if ($_POST) {
                $col = connect_db();
                echo '<p align="center">';
                $begin_city = $_POST['begin_city'];
                echo "你的出发地是:{$begin_city}  ";
                $end_city   = $_POST['end_city'];
                echo "你的目的地是:{$end_city}<br>  ";
                $date_one = $_POST['StartDate'];
                echo "出发日期是:{$date_one}<br>  ";
                $date_two = date('Y-m-d', strtotime("$date_one +1 day"));
                $begin_time = $_POST['StartTime'];;
                echo "最早出发时间是:{$begin_time}<br>";
                echo '</p>';

                $seattype = $_POST['seattype'];
                $seattype1 = $_POST['seattype2'];
                $temp1 = $seattype;
                $temp2 = $seattype1;
                //$_SESSION['trainid'] = $train_id;
                // $_SESSION['startStation'] = $start_station;  
                $query_get_train = "select
    begin_tr.TR_StationID '始发站',
    end_tr.TR_StationID '终点站',
    begin_tr.TR_TrainID '车次',
    sum(pass_tr.TR_Price{$temp1}) price,
    if(min(pass_ts.TS_Left{$temp1})<0,0,min(pass_ts.TS_Left{$temp1})) '余票',
    sum(pass_tr.TR_RouteTime) all_time,
    begin_tr.TR_GoTime begin_time
    /*车次、票价、剩余座位、行程时间、起始时间*/
    from
    station as A,
    station as B,
    trainroute as begin_tr,
    -- 首发站
    trainroute as end_tr,
    -- 终点站
    trainroute as pass_tr,
    -- 中间站
    trainseat as first_ts,
    -- 第一个区间余票
    trainseat as pass_ts -- 全部区间余票
    where
    begin_tr.TR_TrainID = end_tr.TR_TrainID
    and begin_tr.TR_StationID = A.ST_Name
    and A.ST_City = '{$begin_city}' -- 出发站
    and end_tr.TR_StationID = B.ST_Name
    and B.ST_City = '{$end_city}' -- 终点站
    and begin_tr.TR_RouteID < end_tr.TR_RouteID
    and begin_tr.TR_GoTime > '{$begin_time}' -- 出发时间
    and pass_tr.TR_TrainID = begin_tr.TR_TrainID
    and pass_tr.TR_RouteID > begin_tr.TR_RouteID
    and pass_tr.TR_RouteID <= end_tr.TR_RouteID -- 中间站
    and first_ts.TS_TrainID = begin_tr.TR_TrainID
    and first_ts.TS_RouteID = begin_tr.TR_RouteID
    and first_ts.TS_Date = '{$date_one}' -- 出发日期
    and pass_ts.TS_TrainID = begin_TR.TR_TrainID
    and pass_ts.TS_RouteID = pass_tr.TR_RouteID
    and pass_ts.TS_Date = first_ts.TS_Date -- 中间区间时间匹配
    group by
    begin_tr.TR_TrainID,
    begin_tr.TR_GoTime
    having
    min(pass_ts.TS_Left{$temp1}) != 0 -- 票余额不为0
    and price!=0
    order by
    price,
    all_time,
    begin_time asc
    limit
    10;";
                $_SESSION['train_date'] = $date_one;
                $_SESSION['time']    = $today;
                $_SESSION['SeatType'] = $seattype;
                $_SESSION['SeatType1'] = $seattype1;
                echo '<p align="center">' . '<br>' . $begin_city . '到' . $end_city . '的直达查询:<br>' . '</p>';
                $result = mysqli_query($col, $query_get_train);
                echo '<table border="1" style="margin-left:auto; margin-right:auto;"><tr><td>始发站</td><td>终点站
      </td><td>车次</td><td>票价</td><td>总时间</td><td>出发时间</td>
      <td>座位类型</td><td>余票</td></tr>';
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<form method='post' action='one_order_r5.php'>"
                        . "<tr>"
                        . "<td><input style='width:120px;'type='text' value='{$row['始发站']}'  name='startStation' readonly='readonly'/></td> "
                        . "<td><input style='width:120px;'type='text' value='{$row['终点站']}'  name='endStation' readonly='readonly'/> </td> "
                        . "<td><input style='width:120px;'type='text' value='{$row['车次']}'  name='trainid' readonly='readonly'/> </td> "
                        . "<td><input style='width:120px;'type='text' value='{$row['price']}'  name='price' readonly='readonly'/> </td> "
                        . "<td>{$row['all_time']}</td> " . "<td>{$row['begin_time']}</td> "
                        . "<td><input style='width:120px;'type='text' value='{$seattype}'  name='SeatType' readonly='readonly'/></td> "
                        . "<input type='hidden' value='{$row['余票']}'  name='left_ticket' readonly='readonly'/>  "
                        . "<td><input style='width:120px;'type='submit' value='{$row['余票']}' /></td> "
                        . "</tr>"
                        . "</form>";
                }
                echo '</table>';
                echo '<p align="center">' . '<br>' . $begin_city . '到' . $end_city . '的换乘查询:<br>' . '</p>';

                //换乘
                $query_get_train = "select
    routeA.始发站 '段1起点',
    (routeA.price + routeB.price) as price,
    routeA.price '段1票价',
    routeB.price '段2票价',
    routeA.begin_time as begin_time,
    (routeA.all_time + routeB.all_time) as all_time,
    routeA.arrrive_time '段1到达',
    routeB.begin_time '段2发车',
    (
    case
    when (
    timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00'
    and routeB.DDate = '{$date_two}'
    ) then timediff(
    routeB.begin_time,
    timediff(routeA.arrrive_time, '24:00:00')
    )
    else timediff(routeB.begin_time, routeA.arrrive_time)
    end
    ) '换乘时间',
    routeA.终点站 '段1终点',
    routeA.车次 '段1车次',
    routeB.begin_st '段2起点',
    routeB.end_st '段2终点',
    routeB.TrainID '段2车次',
    routeB.DDate datetwo,
    if(routeA.余票<routeB.last,if(routeA.余票<0,0,routeA.余票),if(routeB.last<0,0,routeB.last)) as '余票'
    from
    (
    select
    begin_tr.TR_StationID '始发站',
    end_tr.TR_StationID '终点站',
    begin_tr.TR_TrainID '车次',
    B.ST_City '城市',
    sum(pass_tr.TR_Price{$temp1}) price,
    min(pass_ts.TS_Left{$temp1}) '余票',
    sum(pass_tr.TR_RouteTime) all_time,
    begin_tr.TR_GoTime begin_time,
    end_tr.TR_ArriveTime arrrive_time,
    first_ts.TS_Date as Adate
    from
    station as A,
    station as B,
    trainroute as begin_tr,
    -- 首发站
    trainroute as end_tr,
    -- 终点站
    trainroute as pass_tr,
    -- 中间站
    trainseat as first_ts,
    -- 第一个区间余票
    trainseat as pass_ts -- 全部区间余票
    where
    begin_tr.TR_TrainID = end_tr.TR_TrainID
    and begin_tr.TR_StationID = A.ST_Name
    and A.ST_City = '{$begin_city}' -- 出发城市
    and end_tr.TR_StationID = B.ST_Name
    and begin_tr.TR_RouteID < end_tr.TR_RouteID
    and begin_tr.TR_GoTime > '{$begin_time}' -- 出发时间
    and pass_tr.TR_TrainID = begin_tr.TR_TrainID
    and pass_tr.TR_RouteID > begin_tr.TR_RouteID
    and pass_tr.TR_RouteID <= end_tr.TR_RouteID
    and first_ts.TS_TrainID = begin_tr.TR_TrainID
    and first_ts.TS_RouteID = begin_tr.TR_RouteID
    and first_ts.TS_Date = '{$date_one}' -- 出发日期
    and pass_ts.TS_TrainID = begin_TR.TR_TrainID
    and pass_ts.TS_RouteID = pass_tr.TR_RouteID
    and pass_ts.TS_Date = first_ts.TS_Date
    group by
    begin_tr.TR_TrainID,
    end_tr.TR_RouteID,
    begin_tr.TR_GoTime
    having
    min(pass_ts.TS_Left{$temp1}) > 0
    and price != 0
    ) as routeA,
    /*到达终点B的车站*/
    (
    select
    begin_tr.TR_StationID begin_st,
    end_tr.TR_StationID end_st,
    begin_tr.TR_TrainID TrainID,
    A.ST_City City,
    sum(pass_tr.TR_Price{$temp2}) price,
    min(pass_ts.TS_Left{$temp2}) last,
    sum(pass_tr.TR_RouteTime) all_time,
    begin_tr.TR_GoTime begin_time,
    first_ts.TS_Date DDate
    /*车次、票价、剩余座位、行程时间、起始时间*/
    from
    station as A,
    station as B,
    trainroute as begin_tr,
    -- 首发站
    trainroute as end_tr,
    -- 终点站
    trainroute as pass_tr,
    -- 中间站
    trainseat as first_ts,
    -- 第一个区间余票
    trainseat as pass_ts -- 全部区间余票
    where
    begin_tr.TR_TrainID = end_tr.TR_TrainID
    and begin_tr.TR_StationID = A.ST_Name
    and A.ST_Name != B.ST_Name
    and end_tr.TR_StationID = B.ST_Name
    and B.ST_City != A.ST_City
    and B.ST_City = '{$end_city}' -- 目的地
    and begin_tr.TR_RouteID < end_tr.TR_RouteID
    and begin_tr.TR_GoTime > '00:00' -- 发车时间
    and pass_tr.TR_TrainID = begin_tr.TR_TrainID
    and pass_tr.TR_RouteID > begin_tr.TR_RouteID
    and pass_tr.TR_PriceYWS >= 0
    and pass_tr.TR_RouteID <= end_tr.TR_RouteID
    and first_ts.TS_TrainID = begin_tr.TR_TrainID
    and first_ts.TS_RouteID = begin_tr.TR_RouteID
    and (
    first_ts.TS_Date = '{$date_two}'
    or first_ts.TS_Date = '{$date_one}'
    ) -- 查两天的
    and pass_ts.TS_TrainID = begin_TR.TR_TrainID
    and pass_ts.TS_RouteID = pass_tr.TR_RouteID
    and pass_ts.TS_Date = first_ts.TS_Date -- 余票数量
    group by
    begin_tr.TR_TrainID,
    begin_tr.TR_RouteID,
    begin_tr.TR_GoTime,
    first_ts.TS_Date
    having
    min(pass_ts.TS_Left{$temp2}) > 0
    and price != 0
    ) as routeB
    where
    (routeA.城市 = routeB.City)
    -- 终点站相同或者城市相同
    and routeA.车次 != routeB.TrainID -- 车次不同
    and routeB.DDate = (
    case
    when timediff(routeA.arrrive_time, routeA.begin_time) < '00:00:00' then
    '{$date_two}'
    when timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00' then
    '{$date_two}'
    else '{$date_one}'
    end
    ) -- 第二段的日期
    and(
    case
    when routeA.终点站 = routeB.begin_st then (
    case
    when (
    timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00'
    and routeB.DDate = '{$date_two}'
    ) then timediff(
    routeB.begin_time,
    timediff(routeA.arrrive_time, '24:00:00')
    )
    else timediff(routeB.begin_time, routeA.arrrive_time)
    end
    ) between '01:00:00'
    and '04:00:00'
    else (
    case
    when (
    timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00'
    and routeB.DDate = '{$date_two}'
    ) then timediff(
    routeB.begin_time,
    timediff(routeA.arrrive_time, '24:00:00')
    )
    else timediff(routeB.begin_time, routeA.arrrive_time)
    end
    ) between '02:00:00'
    and '04:00:00'
    end
    )
    group by
    routeA.车次,
    routeB.TrainID,
    begin_time
    order by
    price,
    all_time,
    begin_time asc
    limit
    10;";

                $result = mysqli_query($col, $query_get_train);
                echo '<table border="1"  style="margin-left:auto; margin-right:auto;"><tr>
    <td>段1起点</td>   <td>段1终点</td> <td>段1车次</td> <td>段1发车时间</td> <td>段1到达时间</td> <td>段1座位类型</td>
    <td>段2起点</td>   <td>段2终点</td> <td>段2车次</td> <td>段2发车时间</td> <td>段2座位类型</td>  <td>段2日期</td> 
    <td>总价格</td>    <td>段1售价</td> <td>段2售价</td>
    <td>总时间</td>  <td>换乘时间</td>  
    <td>余票</td>    
      </tr>';
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo
                    "<form method='post' action='two_orders.php'>"
                        . "<tr>"
                        . "<td><input style='width:60px;' type='text' value='{$row['段1起点']}'  name='startStation' readonly='readonly'/></td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段1终点']}'  name='endStation' readonly='readonly'/> </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段1车次']}'  name='trainid' readonly='readonly'/> </td> "
                        . "<td>{$row['begin_time']} </td> "
                        . "<td>{$row['段1到达']} </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$seattype}'  name='SeatType' readonly='readonly'/>"
                        . "<td><input style='width:60px;'  type='text' value='{$row['段2起点']}'  name='startStation2' readonly='readonly'/> </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段2终点']}'  name='endStation2' readonly='readonly'/> </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段2车次']}'  name='trainid2' readonly='readonly'/> </td> "
                        . "<td>{$row['段2发车']} </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$seattype1}'  name='SeatType2' readonly='readonly'/>"
                        . "<td><input style='width:120px;'  type='text' value='{$row['datetwo']}'  name='train_date2' readonly='readonly'/>"
                        . "<td>{$row['price']} </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段1票价']}'  name='price' readonly='readonly'/> </td> "
                        . "<td><input style='width:60px;'  type='text' value='{$row['段2票价']}'  name='price2' readonly='readonly'/> </td> "
                        . "<td>{$row['all_time']} </td> "
                        . "<td>{$row['换乘时间']} </td> "
                        . "<input type='hidden' value='{$row['余票']}'  name='left_ticket' readonly='readonly'/>  "
                        . "<td><input type='submit' value='{$row['余票']}' /> </td> "
                        . "</tr>"
                        . "</form>";
                }
                echo '</table>';
            }
            ?>
    </body>

</html>