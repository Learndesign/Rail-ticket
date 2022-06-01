<!DOCTYPE html>
<?php
  include './db/connect_db.php';
?>
<?php
$col = connect_db();

echo "<p>"; 
echo '当前总订单数:';
$all_orders_query = "select count(*) as total_order
from orderlist
where OR_Status = 1;";
$result = mysqli_query($col, $all_orders_query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
echo "{$row['total_order']}";
echo "</p>";

echo "<p>"; 
echo '<br>总票价:';
$all_price_query = "select sum(OR_Price) as total_price
from orderlist
where OR_Status = 1;";
$result = mysqli_query($col, $all_price_query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
echo "{$row['total_price']}"; 
echo "</p>";

echo "<p>";  
echo '<br>最热车次排序<br>';
$hot_trainID_query = "select OR_TrainID,count(*) as number
from orderlist
where OR_Status = 1
group by OR_TrainID
order by number desc limit 10;";
$result = mysqli_query($col, $hot_trainID_query);
echo '<table border="1"><tr>
      <td>车次</td>      <td>数量</td>
      </tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr><td>{$row['OR_TrainID']} </td> " 
    .  "<td>{$row['number']} </td> " 
    ."</tr>";
}
echo "</table>";
echo "</p>";

echo "<p>";  
echo '<br>用户列表如下:<br>';
$result = mysqli_query($col, "SELECT * FROM users");

echo '<table border="1"><tr>
<td>姓名</td> <td>用户名</td> <td>手机号</td> <td>密码</td> <td>操作</td>
</tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<form method='post' action='test.php'>"
    ."<tr><td> <input type='text' value='{$row['U_UserName']}'  name='test' readonly='readonly'/></td> " 
    . "<td>{$row['U_RealName']} </td> " 
    . "<td>{$row['U_Phone']} </td> " .  "<td>{$row['U_PassWord']} </td> " 
    ."<td><p><input type='submit' value='查看订单' /></p></td>"."</tr>"
    ."</form>";
}
echo "</table>";

echo "</p>";

echo "<p>";
if($_POST){
$user_name = $_POST['test'];
echo '用户'.$user_name;
echo '<br>用户订单信息:<br>';
$user_order_query = "
select *
from orderlist
where OR_UserName = '{$user_name}';";
$result = mysqli_query($col, $user_order_query);
echo '<table border="1"><tr>
<td>订单号</td> <td>用户名</td> <td>列车号</td> <td>列车日期</td> <td>订单时间</td>
<td>座位类型</td> <td>始发站</td> <td>结束站</td> <td>价格</td>
<td>是否联票单</td> <td>联票单号</td> <td>订单状态</td>
</tr>';
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr>"
    . "<td>{$row['OR_ID']} </td> " .  "<td>{$row['OR_UserName']} </td> " 
    . "<td>{$row['OR_TrainID']} </td> " . "<td>{$row['OR_TrainDate']} </td>" 
    . "<td>{$row['OR_Time']} </td> "  . "<td>{$row['OR_SeatType']} </td> "
    . "<td>{$row['OR_StartStation']} </td> "  . "<td>{$row['OR_EndStation']} </td> "
    . "<td>{$row['OR_Price']} </td> "  . "<td>{$row['OR_Together']} </td> "
    . "<td>{$row['OR_TogetherID']} </td> "  . "<td>{$row['OR_Status']} </td> "
    ."</tr>";
}
echo "</table>";

echo "</p>";
}
?>


    

<?php


?>