<?php

/**
 * 作者:suffixbig
 * 作用:打關鍵字
 * 打『訂閱』就加入UID
 * 打『bye』就退出
 * 更新日期:2019-09-25
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
//echo "LINE 機器人 打訂閱就加入你的UID打bye就退出";
//print_r($config);
//exit;
//==========================================================================

/*
if (time() > strtotime('2020-12-31 20:00')) {
    echo "選前10天 不能公佈民調";
    exit;
}

//讀取訂閱清單
if (!file_exists($dbFilePath)) {
    file_put_contents($dbFilePath, json_encode(['user' => []]));
}
$db = json_decode(file_get_contents($dbFilePath), true);

$bodyMsg = file_get_contents('php://input');
file_put_contents(LOG_PATH . 'log.txt', date('Y-m-d H:i:s') . 'Recive: ' . $bodyMsg); //把送過來的值寫下檔案紀錄
$obj = json_decode($bodyMsg, true);
file_put_contents(LOG_PATH . 'log.txt', print_r($db, true));
 */
$api_gamemoney     = new gamemoney(); //遊戲和錢處理用
$client            = new LINEBotTiny($channelAccessToken, $channelSecret);
$clientparseEvents = $client->parseEvents(); //接收POST
$token_name        = $config['token']['token_name']; //籌碼名稱
//不過沒有傳送LINEBOT的檔頭過來的話直接HTTP ERROR 405
if (isset($clientparseEvents)) {
    foreach ($clientparseEvents as $event) {
        $userId  = $event['source']['userId']; //User ID用戶識別碼,的格式為33個字元的英數字字串
        $groupId = ""; //LINE群識別碼,的格式為33個字元的英數字字串//不在群中就沒有
        if ($event['source']['type'] == 'group') {
            $groupId  = $event['source']['groupId']; //不在群中不會有
            $roomtype = 1; //群組
        } elseif ($event['source']['type'] == 'room') {
            //$roomtype=0;你在哪一種房間內0=私訊1=群組，2會議室
            $roomtype = 2; //會議室
        } elseif ($event['source']['type'] == 'user') {
            $roomtype = 0; //你在哪一種房間內0=私訊1
        } else {
            $roomtype = 0;
        }
        //測試時JSON存為LOG=============================================
        //服務器最外圍須建立/data/log目錄 屬性777
        $json = json_encode($event);
        //測試時JSON存為LOG=============================================
        /*
        $file = LOG_PATH . date('Y-m-d') . "_jsonlog.txt"; //log檔名
        $text = date('Y-m-d H:i:s') . "\n" . $json . "\n-------------\n"; //log內容
        $ok   = error_log($text, 3, $file); //留下LOG檔
        */
        //=============================================================
        switch ($event['type']) {
            case 'message':
                $message = $event['message'];
                switch ($message['type']) {
                    case 'text':
                        $linkID = omysql(DB_NAME); //開資料庫******************************************************/
                        require_once 'includes/text.php'; // 返回文字功能
                        require_once 'includes/verification.php'; // 給我驗證碼功能
                        cmysql($linkID); //關資料庫***************************************************************/
                        break;
                    default:
                        //error_log("Unsupporeted message type: " . $message['type']);
                        break;
                }
                break;
            case 'postback':
                $linkID   = omysql(DB_NAME); //開資料庫******************************************************/
                $postback = $event['postback'];
                require_once 'includes/postback.php'; //用戶發送純資料類型格式,例如日期選擇
                //{"data":"abc","params":{"date":"2019-10-25"}} 資料接收格式
                cmysql($linkID); //關資料庫***************************************************************/
                break;
            case 'follow': // 加為好友觸發
                $client->replyMessage(array(
                    'replyToken' => $event['replyToken'],
                    'messages'   => array(
                        array(
                            'type' => 'text',
                            'text' => "您好，歡迎加入 " . $config['Channel']['botname'] . "\n所有的機器人指令可以打 help 查詢",
                        ),
                    ),
                ));
                break;
            case 'join': // 加入群組觸發
                $client->replyMessage(array(
                    'replyToken' => $event['replyToken'],
                    'messages'   => array(
                        array(
                            'type' => 'text',
                            'text' => "您好，歡迎加入 " . $config['Channel']['botname'] . "\n所有的機器人指令可以打 help 查詢",
                        ),
                    ),
                ));
                break;
            default:
                error_log("Unsupporeted event type: " . $event['type']);
                break;
        }
    };
} else {
    echo "LINE BOT 機器人 server";
}
