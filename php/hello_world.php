<?php
    date_default_timezone_set("PRC");
    echo "当前时间为：";
    $today = date("Y-m-d  h:i:s");
    echo $today . '<br>';

    $SeatType =array('YZ','RZ','YWS','YWZ','YWXX','RWS','RWX');
    foreach($SeatType as $temp)
    {
        echo  "Price{$temp}\n";
    }
?>