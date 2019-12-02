<?php
/**
 * 作者:suffixbig
 * 所有訊息
$error = 驗證碼為空
$error = 驗證碼為7碼
$error = 查無此驗證碼  (重複驗證也是一樣結果)
$error = 無會員uid;
會員有登入且驗證碼正確
資料庫會有2筆修改
$sql = "UPDATE `verification_lineid` SET `uid` = '$uid', `approved` = '1', `ok_time` = NOW() WHERE `verification_lineid`.`id` =" . $sno;
$sql = "UPDATE `user` SET `is_auth` = '1' WHERE `id` =" . $uid;
$ok =完成驗證
 */
//==========================================================================
$thisDir = "."; //config.inc.php檔的相對路徑
$_file   = basename(__FILE__); //自行取得本程式名稱
require $thisDir . "/config_in_file.php"; // 載入主參數設定檔
require_once INCLUDE_PATH . "/mysql.inc.php"; // 載入資料庫函式,這一定要在config_in_file.php之後
require_once INCLUDE_PATH . "/global_suffix.php"; // 載入共用庫函式
//==========================================================================
//==========================================================================
$snv   = $_POST['snv'];
$error = "";
if ($snv) {
    if (strlen($snv) == 7) {
        $linkID = omysql(DB_NAME); //開資料庫******************************************************/
        //先查後修或新增
        $sql    = 'SELECT * FROM `verification_lineid` WHERE approved=0 AND verificationcode  =\'' . $snv . '\' ';
        $select = assoc_sql1p($sql, $linkID);
        if ($select['id']) {
            //有就修改資料
            $sno = $select['id'];
            if(isset($_COOKIE['uid'])){
            $uid = $_COOKIE['uid']; //要能取得登入UID
            }else{
            $uid =  "";   
            }
            if ($uid) {
                $sql = "UPDATE `verification_lineid` SET `uid` = '$uid', `approved` = '1', `ok_time` = NOW() WHERE `verification_lineid`.`id` =" . $sno;
                $ok  = mysql_up($sql, $linkID);
                $sql = "UPDATE `user` SET `is_auth` = '1' WHERE `id` =" . $uid;
                $ok  = mysql_up($sql, $linkID);
                if ($ok) {$sms = "完成驗證";}
            } else {
                $error = "無會員uid";
            }
        } else {
            $error = "查無此驗證碼";
        }
        cmysql($linkID); //關資料庫***************************************************************/

    } else {
        $error = "驗證碼為7碼";
    }
} else {
    $error = "驗證碼為空";
}

if ($error) {
    echo $error;
} else {
    echo $sms;
}
