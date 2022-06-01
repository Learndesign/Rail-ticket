<?php session_start() ?>
<?php
include './db/connect_db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>订单取消页面</title>
</head>

<body>
    <?php
    if (!$_SESSION) {
        echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
    } else {
        echo "欢迎使用本订票网站，{$_SESSION['name']}";
    }
    ?>
    <?php
    //订单生成
    $col = connect_db();
    $OR_UserName   = $_SESSION['name'];
    echo '用户名'.$OR_UserName . '<br>';
    $OR_TrainID    = $_POST['trainid'];
    echo '车次号'.$OR_TrainID . '<br>';
    $OR_TrainDate  = $_SESSION['train_date'];
    echo '车次日期'.$OR_TrainDate . '<br>';
    $OR_Time       = $_SESSION['time'];
    echo '订单时间'.$OR_Time . '<br>';

    $SeatType = array('YS', 'YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
    echo $_POST['SeatType'];
    switch ($_POST['SeatType']) {
        case 'YZ':
            $OR_SeatType   = 1;
            break;
        case 'RZ':
            $OR_SeatType   = 2;
            break;
        case 'YWS':
            $OR_SeatType   = 3;
            break;
        case 'YWZ':
            $OR_SeatType   = 4;
            break;
        case 'YWX':
            $OR_SeatType   = 5;
            break;
        case 'RWS':
            $OR_SeatType   = 6;
            break;
        case 'RWX':
            $OR_SeatType   = 7;
            break;
        default: 1;
    }
    echo '座位类型'.$OR_SeatType . '<br>';
    $OR_StartStation  = $_POST['startStation'];
    echo '起始站'.$OR_StartStation . '<br>';
    $OR_EndStation    = $_POST['endStation'];
    echo '终点站'.$OR_EndStation . '<br>';
    $OR_Price         = $_POST['price'];
    echo '价格'.$OR_Price . '<br>';

    $left_ticket = $_POST['left_ticket'];
    if($OR_Price == 0)
      echo "<script>alert('该站不售票!'); window.location.href='r4_find_train.php';</script>";
    else if($left_ticket <=0)
      echo "<script>alert('已无余票!'); window.location.href='r5_find_area.php';</script>";
   else
    $insert_query = "insert into orderlist 
      (OR_UserName, OR_TrainID, OR_TrainDate, OR_Time, OR_SeatType,
       OR_StartStation, OR_EndStation, OR_Price, OR_Together, OR_TogetherID, OR_Status) 
      values ('{$OR_UserName}', '{$OR_TrainID}', '{$OR_TrainDate}', '{$OR_Time}', {$OR_SeatType},
      '{$OR_StartStation}', '{$OR_EndStation}', {$OR_Price}, 0, 0, 1);";
    //echo "<br>" . $insert_query;
    $result = mysqli_query($col, $insert_query);
    if ($result)
        echo '订单已经生成';
    $total_price = 5 + number_format($OR_Price, 2);
    echo "你需要支付{$total_price}元！(包含订单费5元)";

    $get_id_query = "select OR_ID from orderlist where  OR_Time = '{$OR_Time}'";
    $result = mysqli_query($col, $get_id_query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $delete_orid = $row['OR_ID'];

    echo '<table border="1"><tr><td>订单号</td><td>是否联票单</td></tr>';
    echo "<form method='post' action='delete_order.php'>"
        . "<tr><td> <input type='text' value='{$delete_orid}'  name='or_id' readonly='readonly'/></td> "
        . "<td><input type='text' value='0'  name='together' readonly='readonly'/> </td> "
        . "<input type='hidden' value='{$OR_TrainID}'  name='trainid' readonly='readonly'/> "
        . "<input type='hidden' value='{$OR_TrainDate}'  name='traindate' readonly='readonly'/> "
        . "<input type='hidden' value='{$OR_StartStation}'  name='startstation' readonly='readonly'/>"
        . "<input type='hidden' value='{$OR_EndStation}'  name='endstation' readonly='readonly'/> "
        . "<input type='hidden' value='{$OR_SeatType}'  name='SeatType' readonly='readonly'/> "
        . "<td><input type='submit' value='点击取消订单' /> </td> " . "</tr>"
        . "</form>";
    $SeatType = array('YS', 'YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
    $temp1 = $SeatType[number_format($OR_SeatType)];
   // echo"$temp1";
    $ticket_update = "update trainseat
    set TS_Left{$temp1} = TS_Left{$temp1} - 1
    where
    TS_TrainID = '{$OR_TrainID}'
    and
    TS_Date = '{$OR_TrainDate}'
    and (TS_RouteID > all(select TR_RouteID from trainroute,station
    where TR_TrainID = '{$OR_TrainID}' and station.ST_Name = '{$OR_StartStation}'
    and TR_StationID = ST_Name))
    and (TS_RouteID <= all(select TR_RouteID from trainroute,station
    where TR_TrainID = '{$OR_TrainID}' and station.ST_Name = '{$OR_EndStation}'
    and TR_StationID = ST_Name));";
    $result = mysqli_query($col, $ticket_update);
    ?>
    <p><a href="main.php">回到主页</a></p>
    <p><a href="r5_find_area.php">返回票查询</a></p>
</body>

</html>