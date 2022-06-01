use lab2_main;
show tables;
/*test*/
select
  *
from
  yuyuyd.trainroute
where
  TR_TrainID = '1095';
select
  *
from
  yuyuyd.trainseat
where
  TS_TrainID = 'G1'
  and TS_RouteID = 1;
update
  yuyuyd.trainroute
set
  TR_PriceYZ = 0
where
  TR_PriceYZ < 0;
  /**/
  /*request4*/
select
  begin_tr.TR_StationID as '到达站',
  begin_tr.TR_ArriveTime as '到达时间',
  begin_tr.TR_GoTime as '出发时间',
  sum(pass_tr.TR_PriceYZ) as '票价',
  /*TR_PriceRZ ,TR_PriceYWS,TR_PriceYWZ,TR_PriceYWX,TR_PriceRWS,TR_PriceRWX,*/
  min(pass_ts.TS_LeftYZ) as '票余额'
  /*TS_LeftRZ ,TS_LeftYWS,TS_LeftYWZ,TS_LeftYWX,TS_LeftRWS,TS_LeftRWX*/
from
  trainseat begin_ts,
  trainseat pass_ts,
  trainroute begin_tr,
  trainroute pass_tr
where
  begin_tr.TR_TrainID = begin_ts.TS_TrainID
  and begin_tr.TR_RouteID = begin_ts.TS_RouteID
  and begin_tr.TR_TrainID = 'G5'
  and begin_ts.TS_Date = '2022-04-26'
  and pass_ts.TS_Date = begin_ts.TS_Date
  and pass_ts.TS_RouteID = pass_tr.TR_RouteID
  and pass_tr.TR_TrainID = pass_ts.TS_TrainID
  and pass_tr.TR_RouteID <= begin_tr.TR_RouteID
  and pass_tr.TR_PriceYZ >= 0
  and pass_tr.TR_TrainID = begin_tr.TR_TrainID
group by
  begin_tr.TR_RouteID
order by
  begin_tr.TR_RouteID;
  /*request5*/
  /*获得ID，route*/
  /*select
         tr1.TR_trainID
  
  from
         trainroute tr1,station st1
  where
         st1.ST_City = 'A'and
         tr1.TR_StationID = st1.ST_Name and
         tr1.TR_RouteID in
         (select
                 tr2.TR_RouteID TR_RouteID
         from
                 trainroute tr2,
                 station    st2
         where
                 st2.ST_City = 'B'   and
                 tr2.TR_StationID = st2.ST_Name and
                 tr2.TR_TrainID = tr1.TR_TrainID
          );
  
  /*找到route范围的路段*/
  /*select
         TR_trainID, sum(TR_PriceYZ),min(TS_LeftYZ),sum(TR_RouteTime),min(TR_LastgoTime)
         /*车次、票价、剩余座位、行程时间、起始时间*/
  /*from
         trainseat,trainroute
  where
         TR_TrainID = TS_TrainID and
         TR_RouteID = TS_RouteID and
         TS_RouteID > 'A' and trainseat.TS_RouteID <= 'B' and TS_TrainID and
         tr_goTime >= 20
  group by
  
  order by
         sum(tr_priceYZ),sum(tr_routeTime),tr_goTime desc limit 10;*/
  /*request8*/
select
  OR_ID,
  OR_Time,
  OR_StartStation,
  OR_EndStation,
  OR_Together,
  OR_MidTrainID,
  OR_MidStartStation,
  OR_MidEedStation,
  OR_Status
from
  orderlist
where
  OR_UserName = 'name'
  and OR_Time between 'time1'
  and 'time2';
  /*取消*/
update
  orderlist
set
  OR_Status = 0
where
  OR_ID = 'id';
  /*request9*/
  /*总订单*/
select
  count(*) as total_order
from
  orderlist
where
  OR_Status = 1;
  /*总票数*/
select
  sum(or_price) as total_price
from
  orderlist
where
  OR_Status = 1;
  /*最热车次*/
select
  OR_TrainID,
  count(*) as number
from
  orderlist
where
  OR_Status = 1
group by
  OR_TrainID
order by
  number desc
limit
  10;
  /*注册列表*/
select
  U_UserName,
  U_RealName,
  U_Phone,
  U_PassWord
from
  users;
  /*用户订单*/
select
  *
from
  orderlist
where
  OR_UserName = '{name}';
  /*request直达查询*/
  /*leftYZ可替换*/
select
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
  and A.ST_City = '太原'
  and end_tr.TR_StationID = B.ST_Name
  and B.ST_City = '兰州'
  and begin_tr.TR_RouteID < end_tr.TR_RouteID -- 启动
  and begin_tr.TR_GoTime > '00:00' -- 检查出发时间
  and pass_tr.TR_TrainID = begin_tr.TR_TrainID
  and pass_tr.TR_RouteID > begin_tr.TR_RouteID
  and pass_tr.TR_RouteID <= end_tr.TR_RouteID -- 中间站
  and first_ts.TS_TrainID = begin_tr.TR_TrainID
  and first_ts.TS_RouteID = begin_tr.TR_RouteID
  and first_ts.TS_Date = '2022-05-01' -- 中间区间时间保证正确性
  and pass_ts.TS_TrainID = begin_TR.TR_TrainID
  and pass_ts.TS_RouteID = pass_tr.TR_RouteID
  and pass_ts.TS_Date = first_ts.TS_Date -- 中间区间余票数量
group by
  begin_tr.TR_TrainID,
  begin_tr.TR_GoTime
having
  min(pass_ts.TS_LeftYZ) != 0
order by
  price,
  all_time,
  begin_time asc
limit
  10;
  /*request6 等价于 request5*/
  /*request7 */
  /*LeftYZ是可以替换的*/
  /*换乘时把两段都改了*/
update
  trainseat
set
  TS_LeftYZ = TS_LeftYZ - 1
where
  TS_TrainID = '1095'
  and TS_Date = 'date'
  and (
    TS_RouteID > all(
      select
        TR_RouteID
      from
        trainroute,
        station
      where
        TR_TrainID = '$train_id'
        and station.ST_Name = '北京'
        and TR_StationID = ST_Name
    )
  )
  and (
    TS_RouteID <= all(
      select
        TR_RouteID
      from
        trainroute,
        station
      where
        TR_TrainID = '$train_id'
        and station.ST_Name = 'B'
        and TR_StationID = ST_Name
    )
  );
  /*request5 换乘 思路就是找开始找结束？*/
  /*同车站换乘，不同车站换乘*/
select
  A.ST_Name
from
  station A,
  station A1,
  station B,
  station B1,
  trainroute as begin_tr,
  -- 第一段首发站
  trainroute as end_tr,
  -- 第一段终点站
  trainroute as pass_tr,
  -- 第一段中间站
  trainroute as begin_tr1,
  -- 第二段首发站
  trainroute as end_tr1,
  -- 第二段终点站
  trainroute as pass_tr1,
  -- 第二段中间站
  trainseat as first_ts,
  -- 第一段第一个区间余票
  trainseat as pass_ts,
  -- 第一段全部区间余票
  trainseat as first_ts1,
  -- 第二段第一个区间余票
  trainseat as pass_ts1 -- 第二段全部区间余票
where
  begin_tr.TR_TrainID = end_tr.TR_TrainID
  and begin_tr.TR_StationID = A.ST_Name
  and A.ST_City = '北京' -- 第一段的起始地点
  and end_tr.TR_TrainID = begin_tr.TR_TrainID
  and end_tr.TR_RouteID > begin_tr.TR_RouteID
  and end_tr.TR_StationID in (
    select
  )
  and end_tr1.TR_StationID = B1.ST_Name
  and B1.ST_City = '上海' -- 第二段结束地点
  and begin_tr.TR_RouteID < end_tr.TR_RouteID -- 启动
  and begin_tr.TR_GoTime > '00:00' -- 检查出发时间
  and pass_tr.TR_TrainID = begin_tr.TR_TrainID
  and pass_tr.TR_RouteID > begin_tr.TR_RouteID
  and pass_tr.TR_RouteID <= end_tr.TR_RouteID -- 中间站
  and first_ts.TS_TrainID = begin_tr.TR_TrainID
  and first_ts.TS_RouteID = begin_tr.TR_RouteID
  and first_ts.TS_Date = '2022-05-01' -- 中间区间时间保证正确性
  and pass_ts.TS_TrainID = begin_TR.TR_TrainID
  and pass_ts.TS_RouteID = pass_tr.TR_RouteID
  and pass_ts.TS_Date = first_ts.TS_Date -- 中间区间余票数量
;
  /*查询A为起点的列车*/
select
  tA.TR_TrainID,
  -- 始发站
  tA.TR_TrainID,
  -- 车次
  tB.TR_StationID,
  -- 终点站
  sB.ST_City,
  -- 终点站城市
  sum(pass_tr.TR_PriceYZ) price,
  -- 价格
  min(pass_ts.TS_LeftYZ) '余票',
  -- 余票
  tA.TR_GoTime begin_time,
  -- 出发时间
  tB.TR_ArriveTime -- 到达时间
from
  station sA,
  station sB,
  trainroute tA,
  trainroute tB,
  trainroute pass_tr,
  trainseat as first_ts,
  -- 第一个区间余票
  trainseat as pass_ts -- 全部区间余票
where
  tA.TR_StationID = sA.ST_Name
  and sA.ST_City = '太原'
  and tB.TR_TrainID = tA.TR_TrainID
  and tB.TR_RouteID > tA.TR_RouteID
  and sB.ST_Name = tB.TR_StationID
  and tB.TR_TrainID = '1095';
  /**/
  /*查询B为终点的列车*/
select
  tA.TR_TrainID,
  tB.TR_StationID,
  sB.ST_City
from
  station sA,
  station sB,
  trainroute tA,
  trainroute tB
where
  tA.TR_StationID = sA.ST_Name
  and sA.ST_City = '兰州'
  and tB.TR_TrainID = tA.TR_TrainID
  and sA.ST_City != sB.ST_City
  and tB.TR_RouteID <= tA.TR_RouteID
  and sB.ST_Name = tB.TR_StationID
  and tB.TR_TrainID = '1095'
group by
  tB.TR_StationID;
  /*起点A出发能到达的*/
select
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
  and A.ST_City = '北京'
  and end_tr.TR_StationID = B.ST_Name
  and begin_tr.TR_RouteID < end_tr.TR_RouteID -- 启动
  and begin_tr.TR_GoTime > '00:00' -- 检查出发时间
  and pass_tr.TR_TrainID = begin_tr.TR_TrainID
  and pass_tr.TR_RouteID > begin_tr.TR_RouteID
  and pass_tr.TR_RouteID <= end_tr.TR_RouteID -- 中间站
  and first_ts.TS_TrainID = begin_tr.TR_TrainID
  and first_ts.TS_RouteID = begin_tr.TR_RouteID
  and first_ts.TS_Date = '2022-05-01' -- 中间区间时间保证正确性
  and pass_ts.TS_TrainID = begin_TR.TR_TrainID
  and pass_ts.TS_RouteID = pass_tr.TR_RouteID
  and pass_ts.TS_Date = first_ts.TS_Date -- 中间区间余票数量
group by
  begin_tr.TR_TrainID,
  begin_tr.TR_GoTime
having
  min(pass_ts.TS_LeftYZ) != 0
order by
  price,
  all_time,
  begin_time asc
limit
  10;