# 12306订票网站

## 基本信息

### 软件

1. 数据库管理系统：Mysql

2. 平台：windows

3. web：Apache

### 文件目录

1. data

   - train-2016-10                                    --实验数据
   - `data_insert.py`                              --将数据自动导入本地mysql的脚本

2. php

   - db/`connect_db.php `                       --用户所有php文件连接数据库

   - `admin.php `                                        --管理员界面
   - `cancle_order.php`                          --订单取消页面
   - `handle_main.php `                            --处理主页提交信息
   - `handle_register.php`                    --处理注册信息
   - `handle_sign_in.php `                       --处理登录信息
   - `main.php   `                                            --主页,用来处理需求7,下订单
   - `r4_find_train.php `                         --需求4,查询具体车次
   - `r5_find_area.php `                           --需求5,查询两地之间车次
   - `r6_find_back.php`                           --需求6,查询返程信息
   - `r8_find_order.php`                          --需求8,查询订单信息
   - `register.php`                                    --注册界面
   - `sign_in.php `                                      --登录界面

3.  `sql`                                                             --包括创建数据库表和完成需求的sql语句

## 使用方法

1. 使用`data_insert.py`导入数据到本地的数据库，默认会新建一个数据库；
2. 在db/`connect_db.php `中修改mysql的账户密码；
3. 打开apache服务器，在浏览器打开`login.php`启动登录页面。（也可用vscode的插件php sevesr直接打开。





















