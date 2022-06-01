<?php
    function connect_db(){
        $col = mysqli_connect("localhost", "root", "123456");
        if ($col)
        //   printf("Connect successfully!.<br/>");
      
        $query_use = "use test";
        $use_train = mysqli_query($col, $query_use);
        if ($use_train)
            // printf("Use lab2 successfully!.<br/>");
        return $col;
    }
?>