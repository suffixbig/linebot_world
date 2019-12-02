<?php
//20170322
/*
 * 適用PHP7.0 以上
 */
define('DB_HOST',$config['mysql']['DB_HOST'].":".$config['mysql']['DB_PORT']);
define('DB_USERNAME',$config['mysql']['DB_USERNAME']);
define('DB_PASSWORD',$config['mysql']['DB_PASSWORD']);
define('DB_NAME',$config['mysql']['DB_DATABASE']);
define('DB_PREFIX',$config['mysql']['DB_PREFIX']);//資料表名前綴
//開資料庫-預設開啟資料庫
function omysql($table_db,$dbhost=DB_HOST,$dbuser=DB_USERNAME,$dbpass=DB_PASSWORD) {
	$error_level = error_reporting(0); //修改錯誤訊息等級
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $table_db);
	error_reporting($error_level);
	
    $mysqli->query("SET NAMES 'utf8'"); //以下資料用utf8碼與資料庫連線 必須有此行
    //$mysqli->query("set time_zone = '+8:00'"); //時區指定
    if (mysqli_connect_errno($mysqli)) {
        echo "連接 MySQL 失敗: " . mysqli_connect_error();
    }
    return $mysqli;
}

//關資料庫  
function cmysql($link) {
    //$mysqli->close();
    mysqli_close($link);
}

//以IN查資料 給陣列變為要入SQL的字-----這段不一樣注意注意
function idgroup($array=array()) {
    if (empty($array))
        return "IN(0)"; //沒資料不查

    if (is_array($array)) {
        $IDGroup = " IN (";
        for ($ii = 0; $ii < count($array); $ii++) {
            if (!$ii)
                $IDGroup = $IDGroup . "'" . $array[$ii] . "'";
            else
                $IDGroup .= ",'" . $array[$ii] . "'";
        }
        $IDGroup .= ")";
        return $IDGroup;
    }else {
        return "IN(0)";
    }
}

//不連結資料庫--以數字建立二維陣列
function srow_sql($sql, $linkID = "") {
    if ($linkID)
        $result = mysqli_query($linkID,$sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    else
        $result = mysqli_query($sql)or die("error - 1 : " . $sql); //帶入查詢語法
//查詢結果抓出一筆存入array[]陣列
    while ($row = mysqli_fetch_row($result)) {
        $array[] = $row;
    }
    $result->close(); //釋放記憶體
    return $array;
}

//不連結資料庫-以欄名建立二維陣列
function assoc_sql($sql, $link) {
    $result = mysqli_query($link, $sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
//查詢結果抓出一筆存入array[]陣列
    while ($row = mysqli_fetch_assoc($result)) {
        $array[] = $row;
    }
    $result->close(); //釋放記憶體
    return $array;
}

//不連結資料庫-以攔名建立一維陣列(單行)
function assoc_sql1p($sql, $linkID = "") {
    if ($linkID)
        $str = mysqli_query($linkID,$sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;	
    else
        $str = mysqli_query($sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    $row = mysqli_fetch_assoc($str); //抄下符合名稱那一排的資料
    return $row; //結果參數存回主程式 若找無資料會返回""
}

//不連結資料庫-以數字建立一維陣列(單行)
function row_sql($sql, $linkID = "") {
    if ($linkID)
        $str = mysqli_query($linkID,$sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    else
        $str = mysqli_query($sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    $row = mysqli_fetch_row($str); //抄下符合名稱那一排的資料
    return $row; //結果參數存回主程式 若找無資料會返回""
}

//不連結資料庫-只取一個答案
function row_sql1p($sql, $s = 0, $linkID = "") {
    if ($linkID)
        $str = mysqli_query($linkID,$sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    else
        $str = mysqli_query($sql)or die("error 單行查詢錯誤: " . $sql); //帶入查詢語法;
    $row = mysqli_fetch_row($str); //抄下符合名稱那一排的資料
    return $row[$s]; //結果參數存回主程式 若找無資料會返回""
}

//修改資料
function mysql_up($sql, $linkID) {
        $str = mysqli_query($linkID,$sql)or die("error -1: " . $sql); //帶入查詢語法;
        return $str;
}

//新增資料 //成功會是1 失敗會返回空
function mysql_insert($sql, $linkID) {
        $str = mysqli_query($linkID,$sql); //帶入查詢語法;
        return $str; //成功會是1 失敗會返回空
}

//新增資料 失敗會返回錯誤訊息//成功返回id
function mysql_insert_i($sql, $linkID) {
        $str = mysqli_query($linkID,$sql); //帶入查詢語法;
        if(empty($str)){
            $str =mysqli_error($linkID);//錯誤訊息
        }
        return $str; 
}

//新增資料 失敗會返回錯誤訊息//成功返回id 以數組形式
function mysql_insert_ok($sql, $linkID) {
    $str = mysqli_query($linkID,$sql); //帶入查詢語法;
    if(empty($str)){
        $ok['ok']=0;
        $ok['sms']=mysqli_error($linkID);//錯誤訊息
    }else{
        $ok['ok']=1;
        $ok['id']=mysqli_insert_id($linkID);//成功返回id
    }
    return $ok; 
}