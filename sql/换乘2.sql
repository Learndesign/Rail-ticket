select
  routeA.始 发 站 '段1起点',
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
        and routeB.DDate = '2022-05-02'
      ) then timediff(
        routeB.begin_time,
        timediff(routeA.arrrive_time, '24:00:00')
      )
      else timediff(routeB.begin_time, routeA.arrrive_time)
    end
  ) 换乘时间,
  routeA.终点站 '段1终点',
  routeA.车次 '段1车次',
  routeB.begin_st '段2起点',
  routeB.end_st '段2终点',
  routeB.TrainID '段2车次'
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
      and A.ST_City = '北京'                 -- 出发城市
      and end_tr.TR_StationID = B.ST_Name
      and begin_tr.TR_RouteID < end_tr.TR_RouteID  
      and begin_tr.TR_GoTime > '00:00'       -- 出发时间
      and pass_tr.TR_TrainID = begin_tr.TR_TrainID
      and pass_tr.TR_RouteID > begin_tr.TR_RouteID
      and pass_tr.TR_RouteID <= end_tr.TR_RouteID 
      and first_ts.TS_TrainID = begin_tr.TR_TrainID
      and first_ts.TS_RouteID = begin_tr.TR_RouteID
      and first_ts.TS_Date = '2022-05-01'     -- 出发日期
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
      begin_tr.TR_TrainID = end_tr.TR_TrainID -
      and begin_tr.TR_StationID = A.ST_Name
      and A.ST_Name != B.ST_Name 
      and end_tr.TR_StationID = B.ST_Name
      and B.ST_City = '郑州'                       -- 目的地
      and begin_tr.TR_RouteID < end_tr.TR_RouteID 
      and begin_tr.TR_GoTime > '00:00'            -- 发车时间
      and pass_tr.TR_TrainID = begin_tr.TR_TrainID 
      and pass_tr.TR_RouteID > begin_tr.TR_RouteID
      and pass_tr.TR_PriceYWS >= 0
      and pass_tr.TR_RouteID <= end_tr.TR_RouteID 
      and first_ts.TS_TrainID = begin_tr.TR_TrainID
      and first_ts.TS_RouteID = begin_tr.TR_RouteID
      and (
        first_ts.TS_Date = '2022-05-02'
        or first_ts.TS_Date = '2022-05-01'
      )                                           -- 查两天的
      and pass_ts.TS_TrainID = begin_TR.TR_TrainID
      and pass_ts.TS_RouteID = pass_tr.TR_RouteID
      and pass_ts.TS_Date = first_ts.TS_Date      -- 余票数量
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
  (routeA.城 市 = routeB.City)
  -- 终点站相同或者城市相同
  and routeA.车 次 != routeB.TrainID --  车次不同
  and routeB.DDate = (
    case
      when timediff(routeA.arrrive_time, routeA.begin_time) < '00:00:00' then '2022-05-02'
      when timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00' then '2022-05-02'
      else '2022-05-01'
    end
  ) -- 第二段的日期
  and(
    case
      when routeA.终 点 站 = routeB.begin_st then (
        case
          when (
            timediff(routeB.begin_time, routeA.arrrive_time) < '00:00:00'
            and routeB.DDate = '2022-05-02'
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
            and routeB.DDate = '2022-05-02'
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
  routeA.车 次,
  routeB.TrainID,
  begin_time
order by
  price,
  all_time,
  begin_time asc
limit
  10;