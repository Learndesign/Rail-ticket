
create table users                     -- 用户表
(
    u_realname char(20) not null,      -- 真实名字
    u_phone  char(11) unique not null, -- 电话号码
    u_username char(20) unique,        -- 用户名
    u_password char(20) not null,      -- 密码
    primary key (u_username)           -- 主键为用户名
);


create table orderlist
(--订单表
      or_id int,--id
      or_username varchar(20) not null,--用户名
      or_trainId  varchar(10) not null,--列车号
      or_trainDate date not null,      --日期
      or_time datetime not null,       --订单下单时间
      or_seattype int not null,            --座位类型
      /*
      1.硬座2.软座
      3.硬卧上4.硬卧中5.硬卧下
      6.软卧上7.软卧下
      */
      or_startStation varchar(10) not null,--出发站
      or_endStation   varchar(10) not null,--终点站
      or_price  float not null,            --票价
      or_together_id int not null,            --是否联票单
      or_status int not null,              --订单状态
      /*
      0.取消
      1.预定
      */
      primary key (or_id),
      foreign key(or_username) references users(u_username)
);

create table station
(   --车站表
    st_id   int,        --序号
    st_name varchar(20),--车站名
    st_city varchar(10),--所在城市
    primary key (st_name)
);
create table train
(
    t_trainID varchar(10),        --车次
    t_beginStation varchar(20),   --出发站
    primary key(t_trainID),
    foreign key(t_beginStation) references station(st_name)
);
create table train_route
(             --列车表
    tr_trainID varchar(10),      --车次
    tr_route   int,              --路段编号

    tr_startStation varchar(20), --路径初始站
    tr_endStation   varchar(20), --路径结束站
    
    tr_lastgoTime time, --初始站出发日期
    tr_arriveTime time, --结束站到达时间
    tr_goTime     time, --结束站出发时间
    tr_stopTime   int,  --停留时间(用来算发车时间)

    tr_useTime    int,  --始发站到此站历时
    tr_routeTime  int,  --本段历时(不卖票的站也有发时和到时)

    tr_priceYZ  float,   --路径价格(允许为0和负数，用来路径相加)
    tr_priceRZ  float,    /*出发站的票价不允许为负数，价格为负数和0用来阻止终点和起点*/
    tr_priceYWS float,
    tr_priceYWZ float,
    tr_priceYWX float,
    tr_priceRWS float,
    tr_priceRWX float,
    primary key (tr_trainID,tr_route),
    foreign key (tr_trainID)     references train(t_trainID),
    foreign key (tr_startStation) references station(st_name),
    foreign key (tr_endStation)   references station(st_name)
);


  
create table train_seat              --座位信息表
(               
    ts_trainID  varchar(10),         --车次
    ts_route      int,               --路段编号
    ts_date       date,              --车次日期

    ts_leftYZ  float,                --路段剩余座位  
    ts_leftRZ  float,
    ts_leftYWS float,
    ts_leftYWZ float,
    ts_leftYWX float,
    ts_leftRWS float,
    ts_leftRWX float,
    primary key(ts_trainID,ts_route,ts_date),
    foreign key(ts_trainID,ts_route)REFERENCES train_route(tr_trainID,tr_route)
);  



create DATABASE lab2_main;

DROP DATABASE lab2_main;
use lab2_main;

create table Users(                          --用户表
    U_UserName varchar(20) unique,           --用户名    
    U_RealName varchar(20) not null,         --真实名字
    U_Phone  char(11) unique not null,       --电话号码
    U_PassWord varchar(20) not null,         --密码
    primary key (U_UserName)                 --主键
)default charset = utf8;

create table Station(   --车站表
    ST_ID   int,        --序号
    ST_Name varchar(20),--车站名
    ST_City varchar(10),--所在城市
    primary key (ST_Name)
)default charset = utf8;

create table TrainRoute(         --列车表
    TR_RouteID int,              --路段编号
    TR_TrainID varchar(10),      --车次 
    TR_StationID varchar(20),    --该站信息     
    TR_LastgoTime time,          --初始站出发日期
    TR_ArriveTime time,          --结束站到达时间
    TR_GoTime     time,          --结束站出发时间
    TR_StopTime   int,           --停留时间(用来算发车时间)

    TR_UseTime    int,           --始发站到此站历时
    TR_RouteTime  int,           --本段历时(不卖票的站也有发时和到时)

    TR_PriceYZ  float,           --路径价格(允许为0和负数，用来路径相加)
    TR_PriceRZ  float,           /*出发站的票价不允许为负数，价格为负数和0用来阻止终点和起点*/
    TR_PriceYWS float,
    TR_PriceYWZ float,
    TR_PriceYWX float,
    TR_PriceRWS float,
    TR_PriceRWX float,
    primary key (TR_TrainID, Tr_RouteID),
    foreign key (TR_StationID) references Station(ST_Name)
)default charset = utf8;

create table OrderList(                 
    OR_ID    int,                       --id
    OR_UserName varchar(20) not null,   --用户名
    OR_TrainID  varchar(10) not null,   --车次
    OR_TrainDate date not null,         --日期
    OR_Time datetime not null,          --订单下单时间
    OR_SeatType int not null,           --座位类型
    /*
    1.硬座2.软座
    3.硬卧上4.硬卧中5.硬卧下
    6.软卧上7.软卧下
    */
    OR_StartStation varchar(20) not null,--出发站
    OR_EndStation   varchar(20) not null,--终点站
    OR_Price  float not null,            --票价
    -- 联票中转
    OR_Together int not null,            --是否联票单
    OR_TogetherID int                    --联票订单号
    OR_Status int not null,              --订单状态
    /*
    0.取消
    1.预定
    */
    primary key (OR_ID),
    foreign key(OR_UserName) references Users(U_UserName)
)default charset = utf8;


create table TrainSeat              --座位信息表
(               
    TS_TrainID    varchar(10),         --车次
    TS_RouteID    int,               --路段编号
    TS_Date       date,              --车次日期

    TS_LeftYZ  int,                  --路段剩余座位  
    TS_LeftRZ  int,
    TS_LeftYWS int,
    TS_LeftYWZ int,
    TS_LeftYWX int,
    TS_LeftRWS int,
    TS_LeftRWX int,
    primary key(TS_TrainID,TS_RouteID,TS_Date),
    foreign key(TS_TrainID,TS_RouteID)REFERENCES TrainRoute(TR_TrainID,TR_RouteID)
)default charset = utf8;




