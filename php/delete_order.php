<?php session_start() ?>
<?php
  include './db/connect_db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>订单删除页面</title>
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
    //订单生成
    $OR_ID        = $_POST['or_id'];
    $OR_Together  = $_POST['together'];
    //   $OR_TrainID    = $_POST['trainid'];
    //   $OR_TrainDate  = $_POST['traindate'];
    //   $OR_SeatType   = $_POST['SeatType'];
    //   $OR_StartStation  = $_POST['startstation'];
    //   $OR_EndStation  = $_POST['endstation'];

    $col = connect_db();
    if ($OR_Together == '1') {
        $query = "select
        or2.OR_ID OR_ID
        from
        orderlist or1,
        orderlist or2
        where
        or1.OR_Together = 1 and
        or1.OR_ID = {$OR_ID} and
        or2.OR_ID = or1.OR_TogetherID";
        $query2 = "select
        or1.OR_ID OR_ID
        from
        orderlist or1,
        orderlist or2
        where
        or1.OR_Together = 1 and
        or1.OR_TogetherID = {$OR_ID} and
        or2.OR_ID = {$OR_ID}";
        $result = mysqli_query($col, $query);
        if ($result)
            echo "找到联票单号";
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (!$row) {
            $result = mysqli_query($col, $query2);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        $OR_ID2 = $row['OR_ID'];
        $result1 = mysqli_query($col, "select * from orderlist where OR_ID = {$OR_ID}");
        $result2 = mysqli_query($col, "select * from orderlist where OR_ID = {$OR_ID2}");
        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $OR_TrainID    = $row1['OR_TrainID'];
        $OR_TrainDate  = $row1['OR_TrainDate'];
        $OR_SeatType   = $row1['OR_SeatType'];
        $OR_StartStation  = $row1['OR_StartStation'];
        $OR_EndStation  = $row1['OR_EndStation'];
        $OR_Status = $row1  ['OR_Status'];
        $OR_TrainID2    = $row2['OR_TrainID'];
        $OR_TrainDate2  = $row2['OR_TrainDate'];
        $OR_SeatType2   = $row2['OR_SeatType'];
        $OR_StartStation2  = $row2['OR_StartStation'];
        $OR_EndStation2  = $row2['OR_EndStation'];
        $SeatType = array('YS', 'YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
        $temp1 = $SeatType[number_format($OR_SeatType)];
        $temp2 = $SeatType[number_format($OR_SeatType2)];
        if($OR_Status == 0){
            echo "<script>alert('订单已被删除！'); window.location.href='r8_find_order.php';</script>";
        }
        else{
        $ticket_update = "update trainseat
          set TS_Left{$temp1} = TS_Left{$temp1} + 1
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

        $ticket_update = "update trainseat
          set TS_Left{$temp2} = TS_Left{$temp2} + 1
          where
          TS_TrainID = '{$OR_TrainID2}'
          and
          TS_Date = '{$OR_TrainDate}'
          and (TS_RouteID > all(select TR_RouteID from trainroute,station
          where TR_TrainID = '{$OR_TrainID2}' and station.ST_Name = '{$OR_StartStation2}'
          and TR_StationID = ST_Name))
          and (TS_RouteID <= all(select TR_RouteID from trainroute,station
          where TR_TrainID = '{$OR_TrainID2}' and station.ST_Name = '{$OR_EndStation2}'
          and TR_StationID = ST_Name));";
        $result = mysqli_query($col, $ticket_update);
        $query = "update orderlist set OR_Status = 0
        where OR_ID = {$OR_ID};";
        $query1 = "update orderlist set OR_Status = 0
        where OR_ID = {$OR_ID2};";
        $result1 = mysqli_query($col, $query);
        $result1 = mysqli_query($col, $query1);
        if ($result1)
            echo "订单已取消";
        }
    } else {
        $result1 = mysqli_query($col, "select * from orderlist where OR_ID = {$OR_ID}");
        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
        $OR_TrainID    = $row1['OR_TrainID'];
        $OR_TrainDate  = $row1['OR_TrainDate'];
        $OR_SeatType   = $row1['OR_SeatType'];
        $OR_StartStation  = $row1['OR_StartStation'];
        $OR_EndStation  = $row1['OR_EndStation'];
        $OR_Status = $row1  ['OR_Status'];
        $SeatType = array('YS', 'YZ', 'RZ', 'YWS', 'YWZ', 'YWX', 'RWS', 'RWX');
        $temp1 = $SeatType[number_format($OR_SeatType)];
        if($OR_Status == 0){
            echo "<script>alert('订单已被删除！'); window.location.href='r8_find_order.php';</script>";
        }
        else{
        $ticket_update = "update trainseat
          set TS_Left{$temp1} = TS_Left{$temp1} + 1
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
        

        $query = "update orderlist set OR_Status = 0
        where OR_ID = {$OR_ID};
        ";
        $result = mysqli_query($col, $query);
        if ($result)
            echo "订单已取消";
        }
    }

    ?>
    <p><a href="main.php">回到主页</a></p>
</body>

</html>