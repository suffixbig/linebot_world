<?php
/**
 * 作者:suffixbig
 * 作用:
 * test_game.php  摸擬開始遊戲
 * test_game2.php 摸擬答題
 */
//==========================================================================
$thisDir = "."; //config.inc.php檔的相對路徑
$_file   = basename(__FILE__); //自行取得本程式名稱
require $thisDir . "/config_in_file.php"; // 載入主參數設定檔
require_once INCLUDE_PATH . "/mysql.inc.php"; // 載入資料庫函式,這一定要在config_in_file.php之後
require_once INCLUDE_PATH . "/global_suffix.php"; // 載入共用庫函式
require_once INCLUDE_PATH . "/LINEBotTiny.php"; //linedot處理函式
require_once INCLUDE_PATH . "/LINEBotGame.php"; //lineGame處理函式
setcookie("activity20181331", random_string(10)); //防止惡搞
//==========================================================================
$dbFilePath = DBSAVE_PATH . 'line-db.json'; // user info database file path 用存來放UID的檔案
//==========================================================================

//==========================================================================
$client        = new LINEBotTiny($channelAccessToken, $channelSecret);
$api_gamemoney = new gamemoney(); //遊戲和錢處理用

$userId  = "Ua4ad191fd85e71fcc85919dcc15343a0";
$groupId = 'C0ac8f1cb43a4de7976357beb45a8852f';

$event['replyToken'] = "sdfdsfsdfsdfsdfdsfsdfdsfs";
$roomtype = 0;
$linkID = omysql(DB_NAME); //開資料庫******************************************************/

//$message['text'] = "票卷";


$message['text'] = "給我驗證碼";
require_once 'includes/verification.php'; // 給我驗證碼功能
cmysql($linkID); //關資料庫***************************************************************/
print_r($sms);
