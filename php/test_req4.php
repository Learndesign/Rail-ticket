<?php
  include './db/connect_db.php';
?>

<?php   //本php模块用来查询车次信息
    //这里显示查询信息
    date_default_timezone_set("PRC");
    echo "当前时间为：";
    $today = date("Y-m-d D h:i:s A ");
    echo $today.'<br>';
    $col = connect_db();

    // $train_id   = $_POST["TrainId"];
    //     echo $train_id;
    // $train_date  = $_POST["TrainDate"];
    //     echo $train_date;   
    
    $query_get_train = "
    select
    begin_tr.TR_StationID as'到达站',
    begin_tr.TR_ArriveTime as'到达时间',
    begin_tr.TR_GoTime as '出发时间',
    sum(pass_tr.TR_PriceYZ) as'票价',
    min(pass_ts.TS_LeftYZ) as'票余额'
    from
    trainseat begin_ts,
    trainseat pass_ts,
    trainroute begin_tr,
    trainroute pass_tr
    where
    begin_tr.TR_TrainID = begin_ts.TS_TrainID and
    begin_tr.TR_RouteID = begin_ts.TS_RouteID and
    begin_tr.TR_TrainID = '1095' and begin_ts.TS_Date = '2022-05-08' and
    pass_ts.TS_Date = begin_ts.TS_Date and pass_ts.TS_RouteID =
    pass_tr.TR_RouteID and
    pass_tr.TR_TrainID = pass_ts.TS_TrainID and
    pass_tr.TR_RouteID <= begin_tr.TR_RouteID and
    pass_tr.TR_PriceYZ >=0 and
    pass_tr.TR_TrainID = begin_tr.TR_TrainID
    group by
    begin_tr.TR_RouteID
    order by
    begin_tr.TR_RouteID;";

    $result = mysqli_query($col, $query_get_train);
    echo '<table border="1"><tr><
    td>到达站</td><td>到达时间
      </td><td>出发时间</td><td>票价</td><td>票余额</td></tr>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr><td> {$row['到达站']}</td> " . "<td>{$row['到达时间']} </td> " 
    . "<td>{$row['出发时间']} </td> " .  "<td>{$row['票价']} </td> " 
    . "<td><input type='submit' value='{$row['票余额']}' /><a href='main.php'>" 
    ."</tr>";
    }
    echo '</table>'
?>
<form> 
    
</form>
