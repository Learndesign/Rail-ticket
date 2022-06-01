import datetime as dt
from dateutil.parser import parse
import pymysql as ps
import copy
import os


# 插入站和城市表
def insert_station():
    '''
    用于导入站点与城市的联系表格信息其中Station表格的字段如下:
        ST_ID   int,        --序号
        ST_Name varchar(20),--车站名
        ST_City varchar(10),--所在城市
    '''
    project_path = os.path.dirname(os.path.abspath(__file__))
    file_path = project_path+r'\train-2016-10\all-stations.txt'
    try:
        with open(file_path, 
                   encoding='utf-8',
                   mode='r'
                 ) as file:
            while True:
                s=file.readline().strip()
                # print(s)
                if not s:
                    break;
                # 以空格分开
                insert_message = s.split(',')
                # 去除换行符
                insert_message[2] = insert_message[2].strip()
                # 将ID转换为int类型
                insert_message[0] = int(insert_message[0])
                cur.execute("insert into Station values('%d', '%s', '%s');" 
                            % (
                               insert_message[0], # ST_ID
                               insert_message[1], # ST_Name
                               insert_message[2]  # ST_ST_City
                               )
                            )
        db.commit()
        file.close()        

    except IOError:
        print("文件打开失败")  
    finally:
        pass
    
def getstaytime(str):
    str2 = ""
    for i in str:
        if i >= '0' and i <= '9':
            str2+=i
        else:
            break
    print(str2, len(str2))
    return int(str2)

def gettimediff(time1, time2):
    defalut_data_now = '1949-10-1/'
    defalut_data_tomorrow = '1949-10-2/'
    time_begin = copy.deepcopy(time1) + ':00'
    time_end = copy.deepcopy(time2) + ':00'
    hour_1, minute_1 = time1.split(':') 
    hour_2, minute_2 = time2.split(':') 
    if int(hour_2) >= int(hour_1):
        return int((parse(defalut_data_now + time_end) - \
                 parse(defalut_data_now+time_begin)).total_seconds()) // 60
    else:
        return int((parse(defalut_data_tomorrow + time_end) - \
                parse(defalut_data_now+time_begin)).total_seconds()) // 60
        

def getprice(str):
    price = []
    for i in str:
        if i == '-' or i == '':
            price.append(float(0))
        else:
            price.append(float(i))
    return price


# 导入车次信息即TrainRout表的详细信息
def insert_trainroute():
    '''
        导入TrainRoute表:
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
        TR_PriceRZ  float,           --出发站的票价不允许为负数，价格为负数和0用来阻止终点和起点
        TR_PriceYWS float,
        TR_PriceYWZ float,
        TR_PriceYWX float,
        TR_PriceRWS float,
        TR_PriceRWX float,   
        
        导入TrainSeat表:
        TS_TrainID    varchar(10) not null,         --车次
        TS_RouteID    int not null,               --路段编号
        TS_Date       date not null,              --车次日期

        TS_LeftYZ  int not null,                --路段剩余座位  
        TS_LeftRZ  int not null,
        TS_LeftYWS int not null,
        TS_LeftYWZ int not null,
        TS_LeftYWX int not null,
        TS_LeftRWS int not null,
        TS_LeftRWX int not null,        
    '''
    train_type = ['0','c','d','g','k','t','y','z']
    project_path = os.path.dirname(os.path.abspath(__file__)) + \
                   r'\train-2016-10'
    result_sql = os.path.dirname(os.path.abspath(__file__)) + \
                   r'\test'
    for T_type in train_type:
        train_file_path = project_path + '\\' + T_type + '\\'
        train_result_sql = result_sql + '\\' + T_type + '\\'
        files= os.listdir(train_file_path) #得到文件夹下的所有文件名称列表
        
        for every_file in files:
            print("正在导入：%s" % every_file)
            try:
                with open(train_file_path + every_file, 
                          encoding='utf-8',
                          mode= 'r'
                          ) as file :
                    # 第一行没有用
                    s = file.readline().strip()
                    begin = 0
                    end = 0
                    pre_line = s
                    init_price = []
                    pre_price = []
                    pre_price_list = []
                    # 上一行的价格
                    pre_price_line = [0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0]
                    while True:
                        # print("prev:", pre_line)
                        s = file.readline().strip()
                        if not s:
                            begin = 0
                            break
                        new_line = s.split(',')
                        for i in range(0, len(new_line)):
                            # 去除空格
                            new_line[i] = new_line[i].strip()
                        TR_RouteID = int(new_line[0])
                        TR_TrainID = str(copy.deepcopy(str(every_file).split('.')[0]))
                        TR_StationID = copy.deepcopy(new_line[1])
                        if not begin:
                            TR_LastgoTime = copy.deepcopy(new_line[3])
                            TR_ArriveTime = copy.deepcopy(new_line[3])
                            TR_GoTime = copy.deepcopy(new_line[3])
                            TR_StopTime = 0
                        else:
                            TR_LastgoTime = copy.deepcopy(pre_line[3])
                            TR_ArriveTime = copy.deepcopy(new_line[2])
                            # TR_GoTime = copy.deepcopy(new_line[3])
                            # print(new_line[4][0:-1])
                            if new_line[3] == '-':
                                TR_GoTime = copy.deepcopy(new_line[2])
                                TR_StopTime = 0
                            else:
                                TR_GoTime = copy.deepcopy(new_line[3])
                                if new_line[4]=='':
                                    TR_StopTime = 0
                                else:
                                    TR_StopTime = int(new_line[4][0:-1])
                            # TR_StopTime = getstaytime(new_line[4])
                            # print(TR_StopTime)
                            
                        # print(TR_LastgoTime, TR_ArriveTime, TR_GoTime, TR_StopTime)
                        # print(every_file)
                        if new_line[5] == '-':
                            new_line[5] = '0'
                        TR_UseTime = int(new_line[5])
                        if not begin:
                            TR_RouteTime = 0
                        else:
                            # TR_RouteTime = int(new_line[5]) - int(pre_line[5])
                            TR_RouteTime = gettimediff(pre_line[3], new_line[2])
                        # 初始化所有座位的价格
                        YRZ = new_line[7].split('/')
                        if len(YRZ) != 2:
                            while len(YRZ) != 2:
                                YRZ.append('-') 
                        YWSZX = new_line[8].split('/')
                        if len(YWSZX) != 3:
                            while len(YWSZX) != 3:
                                YWSZX.append('-') 
                        RWSZX = new_line[9].split('/')
                        if len(RWSZX) != 2:
                            while len(RWSZX) != 2:
                                RWSZX.append('-') 
                        new_price_list = YRZ + YWSZX + RWSZX
                        # 当前这一行的价格
                        price_line = getprice(new_price_list)
                        
                        # print(price_line)
                        new_price = []
                        for i in range(0, len(price_line)):
                            if price_line[i] != 0:
                                new_price.append(float(price_line[i]-pre_price_line[i]))
                            else:
                                new_price.append(float(0))
                            if price_line[i] != 0:
                                pre_price_line[i] = price_line[i]
                        
                        if not begin:
                            TR_PriceYZ = float(0.0)
                            TR_PriceRZ = float(0.0)
                            TR_PriceYWS = float(0.0)
                            TR_PriceYWZ = float(0.0)
                            TR_PriceYWX = float(0.0)
                            TR_PriceRWS = float(0.0)
                            TR_PriceRWX = float(0.0)
                        else:
                            (TR_PriceYZ,
                            TR_PriceRZ,
                            TR_PriceYWS,
                            TR_PriceYWZ,
                            TR_PriceYWX,
                            TR_PriceRWS,
                            TR_PriceRWX) = new_price
                        cur.execute("insert into TrainRoute values('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d','%f', '%f', '%f','%f', '%f', '%f','%f')"\
                                      % (TR_RouteID, str(TR_TrainID), str(TR_StationID),\
                                         str(TR_LastgoTime), str(TR_ArriveTime), str(TR_GoTime),\
                                         TR_StopTime, TR_UseTime, TR_RouteTime,\
                                         TR_PriceYZ, TR_PriceRZ, TR_PriceYWS,\
                                         TR_PriceYWZ, TR_PriceYWX, TR_PriceRWS,\
                                         TR_PriceRWX
                                        )
                                        )   
                        today=dt.date.today()
                        seat = [5, 5, 5, 5, 5, 5, 5]
                        for i in range(30):
                            insert = ("insert into TrainSeat values('%s', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d');" 
                                    % (
                                        TR_TrainID,
                                        TR_RouteID,
                                        str(today + dt.timedelta(days=i)),
                                        seat[0], seat[1], seat[2], seat[3], seat[4], seat[5], seat[6]
                                    )
                                )
                            # print(insert)
                            cur.execute(insert)
                        
                        db.commit()
                                                                                          
                        pre_line = copy.deepcopy(new_line)        
                        begin = 1            
                # with open(train_result_sql + every_file.split('.')[0] + '.sql', encoding='utf-8', mode='w') as result:
                #     for i in db_insert:
                #         result.write(i+'\n')
                # result.close()
            except IOError:
                print("failed to open file: %s ", (every_file))  
            finally:
                pass
              
            file.close() 
        
def insert_trainseat():
    '''
        插入每站的座位信息
        TS_TrainID    varchar(10),         --车次
        TS_RouteID    int,               --路段编号
        TS_Date       date,              --车次日期

        TS_LeftYZ  int,                --路段剩余座位  
        TS_LeftRZ  int,
        TS_LeftYWS int,
        TS_LeftYWZ int,
        TS_LeftYWX int,
        TS_LeftRWS int,
        TS_LeftRWX int,        
    '''
    train_type = ['0','c','d','g','k','t','y','z']
    project_path = os.path.dirname(os.path.abspath(__file__)) + \
                   r'\train-2016-10'
    result_sql = os.path.dirname(os.path.abspath(__file__)) + \
                   r'\test'
    for T_type in train_type:
        train_file_path = project_path + '\\' + T_type + '\\'
        files= os.listdir(train_file_path) #得到文件夹下的所有文件名称列表
        
        for every_file in files:
            print("正在导入：%s" % every_file )
            try:
                with open(train_file_path + every_file, 
                          encoding='utf-8',
                          mode= 'r'
                          ) as file :
                    s = file.readline().strip()
                    while True:
                        # print("prev:", pre_line)
                        s = file.readline().strip()
                        if not s:
                            break
                        new_line = s.split(',')
                        for i in range(0, len(new_line)):
                            # 去除空格
                            new_line[i] = new_line[i].strip()
                        YRZ = new_line[7].split('/')
                        if len(YRZ) != 2:
                            while len(YRZ) != 2:
                                YRZ.append('-') 
                        YWSZX = new_line[8].split('/')
                        if len(YWSZX) != 3:
                            while len(YWSZX) != 3:
                                YWSZX.append('-') 
                        RWSZX = new_line[9].split('/')
                        if len(RWSZX) != 2:
                            while len(RWSZX) != 2:
                                RWSZX.append('-') 
                        new_price_list = YRZ + YWSZX + RWSZX                        
                        TR_TrainID = str(copy.deepcopy(str(every_file).split('.')[0]))
                        TR_RouteID = int(new_line[0])      
                        today=dt.date.today()
                        seat = [5, 5, 5, 5, 5, 5, 5]
                        for i in range(30):
                            insert = ("insert into TrainSeat values('%s', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d');" 
                                    % (
                                        TR_TrainID,
                                        TR_RouteID,
                                        str(today + dt.timedelta(days=i)),
                                        seat[0], seat[1], seat[2], seat[3], seat[4], seat[5], seat[6]
                                    )
                                )
                            # print(insert)
                            cur.execute(insert)
                        db.commit() 
                    
            except IOError:
                print("failed to open file %s", every_file)
            finally:
                pass
               

Users = '''
create table Users(                           
    U_UserName varchar(20) unique,                
    U_RealName varchar(20) not null,         
    U_Phone  char(11) unique not null,       
    U_PassWord varchar(20) not null,          
    primary key (U_UserName)                  
)default charset = utf8;
'''
Station = '''
create table Station(    
    ST_ID   int not null,        
    ST_Name varchar(20) not null unique, 
    ST_City varchar(10) not null, 
    primary key (ST_Name)
)default charset = utf8;
'''

TrainRoute = '''
create table TrainRoute(          
    TR_RouteID int not null,               
    TR_TrainID varchar(10) not null,      
    TR_StationID varchar(20) not null,   
    TR_LastgoTime time not null,          
    TR_ArriveTime time not null,           
    TR_GoTime     time not null,           
    TR_StopTime   int not null,           

    TR_UseTime    int not null,           
    TR_RouteTime  int not null,            

    TR_PriceYZ  float not null,            
    TR_PriceRZ  float not null,            
    TR_PriceYWS float not null,
    TR_PriceYWZ float not null,
    TR_PriceYWX float not null,
    TR_PriceRWS float not null,
    TR_PriceRWX float not null,
    primary key (TR_TrainID, Tr_RouteID),
    foreign key (TR_StationID) references Station(ST_Name)
)default charset = utf8;
'''

OrderList = '''
create table OrderList(                 
    OR_ID    int,                       
    OR_UserName varchar(20) not null,    
    OR_TrainID  varchar(10) not null,   
    OR_TrainDate date not null,         
    OR_Time datetime not null,         
    OR_SeatType int not null,         
    /*
    1.硬座2.软座
    3.硬卧上4.硬卧中5.硬卧下
    6.软卧上7.软卧下
    */
    OR_StartStation varchar(20) not null, 
    OR_EndStation   varchar(20) not null, 
    OR_Price  float not null,            
    -- 联票中转
    OR_Together int not null,            
    OR_TogetherID int ,    
    OR_Status int not null,              
    /*
    0.取消
    1.预定
    */
    primary key (OR_ID),
)default charset = utf8;
'''

TrainSeat = '''
create table TrainSeat               
(               
    TS_TrainID    varchar(10) not null,         
    TS_RouteID    int not null,               
    TS_Date       date not null,          

    TS_LeftYZ  int not null,               
    TS_LeftRZ  int not null,
    TS_LeftYWS int not null,
    TS_LeftYWZ int not null,
    TS_LeftYWX int not null,
    TS_LeftRWS int not null,
    TS_LeftRWX int not null,
    primary key(TS_TrainID,TS_RouteID,TS_Date),
    foreign key(TS_TrainID,TS_RouteID)REFERENCES TrainRoute(TR_TrainID,TR_RouteID)
)default charset = utf8;

'''
print(">>> open mysql")

# time_1 = '23:05'
# time_2 = '00:19'
# # print("use time:%d" % gettimediff(time_1, time_2))
# print(gettimediff(time_1, time_2))
# while True:
#     pass

passwd = str(input(">> 请输入数据库密码: "))
use_db = str(input(">> 将创建你输入的database: "))
# 连接数据库
print("连接数据库...")
try:
    db = ps.connect(host = "localhost",
                    user = "root",
                    passwd= passwd,
                    charset= "utf8",
                    )
except:
    print("失败！")
    exit()
print("成功")
# 创建游标对象
cur = db.cursor()
print("创建数据库%s..." % use_db)
try:
    cur.execute("create database %s;" % use_db)
    cur.execute("use %s;" % use_db)
    db.commit()
except:
    print("失败！")
    exit()
    
print("创建表Users, Station, TrainRoute, OrderList, TrainSeat...")
try:
    cur.execute(Users)
    cur.execute(Station)
    cur.execute(TrainRoute)
    cur.execute(OrderList)
    cur.execute(TrainSeat)
    db.commit()
except:
    print("失败！")
    exit()
    
print("成功")


# 导入站点城市信息
print("begin insert the table station")
insert_station()

# 导入车次信息
print("begin insert the message of Train and TrainSeat")
insert_trainroute();

# print("begin insert the message of TrainSeat")
# insert_trainseat()

print("完成数据导入！")
cur.close()
db.close()
