<?php
include_once 'lib.php';
//include_once 'Transfer.php';
include_once 'gsconfig.php';
$dbc_file='..\..\..\application\database.php';
if(!file_exists($dbc_file)){
    $dbconfig=include_once $dbc_file;
    $link = mysqli_connect($dbconfig['hostname'],$dbconfig['username'],$dbconfig['password'],$dbconfig['database']);
    if (!$link) {
        printf("Can't connect to MySQL Server. Errorcode: %s ", mysqli_connect_error());
        exit;
    }else{
        $result = mysqli_query($link, 'SELECT *  FROM lmq_stock_account WHERE status=1 and type=1 and broker<>-1 ');
        $login_arr=array();
        $num=0;
        while($row = mysqli_fetch_assoc($result)){
            if($num==0){$option['def']=$row['id'];}
            $login_arr[$row['id']]['LID']=$row['lid'];
            $login_arr[$row['id']]['user']=$row['user'];
            $login_arr[$row['id']]['password']=$row['pwd'];
            $login_arr[$row['id']]['broker']=$row['broker'];
            $login_arr[$row['id']]['clienver']=$row['clienver'];
            $num++;
        }
        mysqli_close($link);
    }
}else{
    $option['def']=9;
    $login_arr['9']=array(
        'LID'=>'650012900',      #初始证券账户
        'user'=>'test',        #交易通用户名
        'password'=>'admin001',   #交易同密码
        'broker'=>'81',         #证券编号
        'clienver'=>'6.50'      #证券版本号
    );

};

$green= new \Transfer($option,$login_arr);
$green->run();


