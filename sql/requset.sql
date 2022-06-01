--需求4

--需求5

--需求6

--需求7


--需求8
   --订单查询
select or_id,or_time,or_startStation,or_endStation,or_status
from orderlist
where or_username = 'name' and 
       or_time between 'time1' and 'time2';
   --订单取消
update orderlist  set or_status = 0 
where or_id = 'id';

--需求9
  --总订单数
select count(*) as total_order
from orderlist
where or_seat = 1;
--总票价
select sum(or_price) as total_price
from orderlist
where or_seat = 1;
--最热车次排序
select or_trainId,count(*) as number
from orderlist
where or_seat = 1
group by or_trainId
order by number desc limit 10;
--用户列表
select u_username,u_realname 
from users;
--用户订单
select *
from orderlist
where or_username = '{name}';