<?php session_start() ?>
<?php
  include './db/connect_db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>返程查询</title>
</head>

<body>
    <?php
    if (!$_SESSION) {
        echo "<script>alert('您还没有登录'); window.location.href='sign_in.php';</script>";
    } else {
        echo "欢迎使用本订票网站，{$_SESSION['name']}";
    }
    ?>
    <form method="post" action="r6_find_back.php">
        <p><input type="text" placeholder="出发地" name="begin_city"></p>
        <p><input type="text" placeholder="目的地" name="end_city"></p>
        <p><input type="text" placeholder="出发日期" name="StartDate"></p>
        <p><input type="text" placeholder="出发时间" name="StartTime"></p>
        <p><input type="submit" value="查询"></p>
    </form>
    <p><a href="main.php">回到主页</a></p>
    <p><a href="r5_find_area.php">本页面查询不带返程,带返程的请查询点我</a></p>
    <?php   //本php模块用来查询两地之间的车次信息
    //这里显示查询信息
    date_default_timezone_set("PRC");
    echo "当前时间为：";
    $today = date("Y-m-d  h:i:s  ");
    echo $today . '<br>';
    if ($_POST) {
        $col = connect_db();

        $begin_city = $_POST['begin_city'];
        echo "你的出发地是:{$begin_city}<br>";
        $end_city   = $_POST['end_city'];
        echo "你的目的地是:{$end_city}<br>";
        $date_one = $_POST['StartDate'];
        echo "出发日期是:{$date_one}<br>";
        $date_two = date('Y-m-d', strtotime("$date_one +1 day"));
        $begin_time = $_POST['StartTime'];;
        echo "最早出发时间是:{$begin_time}<br>";
        $query_get_train = "select
    begin_tr.TR_StationID '始发站',
    end_tr.TR_StationID '终点站',
    begin_tr.TR_TrainID '车次',
    sum(pass_tr.TR_PriceYZ) price,
    min(pass_ts.TS_LeftYZ) '余票',
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
    min(pass_ts.TS_LeftYZ) != 0 -- 票余额不为0
    and price!=0
    order by
    price,
    all_time,
    begin_time asc
    limit
    10;";

        echo '<br>' . $begin_city . '到' . $end_city . '的直达查询:<br>';
        $result = mysqli_query($col, $query_get_train);
        echo '<table border="1"><tr><td>始发站</td><td>终点站
      </td><td>车次</td><td>票价</td><td>余票</td><td>总时间</td><td>出发时间</td></tr>';
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr><td> {$row['始发站']}</td> " . "<td>{$row['终点站']} </td> "
                . "<td>{$row['车次']} </td> " . "<td>{$row['price']} </td> "
                . "<td><a href='main.php'>{$row['余票']} </a></td> "
                . "<td>{$row['all_time']}</td> " . "<td>{$row['begin_time']}</td> "
                . "</tr>";
        }
        echo '</table>';
        echo '<br>' . $begin_city . '到' . $end_city . '的换乘查询:<br>';

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
    if(routeA.余票<routeB.last,routeA.余票,routeB.last) as '余票'
    from
    (
    select
    begin_tr.TR_StationID '始发站',
    end_tr.TR_StationID '终点站',
    begin_tr.TR_TrainID '车次',
    B.ST_City '城市',
    sum(pass_tr.TR_PriceYZ) price,
    min(pass_ts.TS_LeftYZ) '余票',
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
    min(pass_ts.TS_LeftYZ) != 0
    and price != 0
    ) as routeA,
    /*到达终点B的车站*/
    (
    select
    begin_tr.TR_StationID begin_st,
    end_tr.TR_StationID end_st,
    begin_tr.TR_TrainID TrainID,
    A.ST_City City,
    sum(pass_tr.TR_PriceYWS) price,
    min(pass_ts.TS_LeftYWS) last,
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
    min(pass_ts.TS_LeftYWS) != 0
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
        echo '<table border="1"><tr>
    <td>段1起点</td>   <td>总价格</td> <td>段1售价</td> <td>段2售价</td>
    <td>起始时间</td>  <td>总时间</td>  <td>段1发车时间</td> <td>段2发车时间</td> <td>换乘时间</td>
    <td>段1终点</td>   <td>段1车次</td> <td>段2起点</td> <td>段2终点</td>  <td>段2车次</td>
    <td>余票</td>
      </tr>';
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
       <td> {$row['段1起点']}</td> " . "<td>{$row['price']} </td> "
                . "<td>{$row['段1票价']} </td> " . "<td>{$row['段2票价']} </td> "
                . "<td>{$row['begin_time']} </td> " . "<td>{$row['all_time']} </td> "
                . "<td>{$row['段1到达']} </td> "  . "<td>{$row['段2发车']} </td> " . "<td>{$row['换乘时间']} </td> "
                . "<td>{$row['段1终点']} </td> "  . "<td>{$row['段1车次']} </td> " . "<td>{$row['段2起点']} </td> "
                . "<td>{$row['段2终点']} </td> "  . "<td>{$row['段2车次']} </td> "
                . "<td>{$row['余票']} </td> "   . "<td><a href='main.php'>点我订票</a></td>"
                . "</tr>";
        }
        echo '</table>';
    }
    ?>
</body>

</html>