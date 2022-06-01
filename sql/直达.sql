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
  and A.ST_City = '北京'   --出发站
  and end_tr.TR_StationID = B.ST_Name
  and B.ST_City = '郑州'   --终点站
  and begin_tr.TR_RouteID < end_tr.TR_RouteID 
  and begin_tr.TR_GoTime > '00:00' -- 出发时间
  and pass_tr.TR_TrainID = begin_tr.TR_TrainID
  and pass_tr.TR_RouteID > begin_tr.TR_RouteID
  and pass_tr.TR_RouteID <= end_tr.TR_RouteID  -- 中间站
  and first_ts.TS_TrainID = begin_tr.TR_TrainID
  and first_ts.TS_RouteID = begin_tr.TR_RouteID
  and first_ts.TS_Date = '2022-05-01'    -- 出发日期
  and pass_ts.TS_TrainID = begin_TR.TR_TrainID
  and pass_ts.TS_RouteID = pass_tr.TR_RouteID
  and pass_ts.TS_Date = first_ts.TS_Date -- 中间区间时间匹配
group by
  begin_tr.TR_TrainID,
  begin_tr.TR_GoTime
having
  min(pass_ts.TS_LeftYZ) != 0  -- 票余额不为0
order by
  price,
  all_time,
  begin_time asc
limit
  10;