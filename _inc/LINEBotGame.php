<?php
//20170322
/*
 * 適用PHP7.0 以上
 */
//以下特別寫的************************************************************************************ */

/*現在寫到這裡面
require_once INCLUDE_PATH . "/LINEBotTiny.php"; //linedot處理函式
//寫下資料庫紀錄機器人回應紀錄
function robot_response_dblog($linkID,$groupId,$userId,$type,$text)
{
$userId="LINE BOT";
//新增資料
$sql = "INSERT INTO `".DB_PREFIX."monitor` (`sno`, `groupId`, `userId`, `messagetype`, `messagetext`, `date`, `time`, `add_datetime`, `bot`) VALUES
(NULL, '$groupId', '$userId', '$type', '$text', NOW(), NOW(), NOW(), '1');";
$ok  = mysql_insert_ok($sql, $linkID);
return $ok['id'];//成功會返回id
}
 */
class gamemoney
{
    public function __construct()
    {}

    //加減籌碼
    //$api_gamemoney->addandsubtractchips($linkID,$userId,2,$coolcoin);//加減籌碼
    public function addandsubtractchips($linkID, $line_id, $type, $coolcoin)
    {
        if (mb_strlen($line_id) == 33 && is_numeric($coolcoin)) {
            $currency = "fsc";
            $note     = "";
            if ($type == 1) {
                $itemdesc = $currency . "換成籌碼";
            } elseif ($type == 2) {
                $itemdesc = "admin指令加減籌碼";
                $note     = "admin指令加減籌碼";
            } elseif ($type == 3) {
                $itemdesc     = "中彩金";
                $target_table = "coolcoin_wins_" . strtolower($currency);
            } elseif ($type == 4) {
                $itemdesc     = "玩遊戲贏錢";
                $target_table = "coolcoin_wins_" . strtolower($currency);
            } elseif ($type == 5) {
                $itemdesc     = "玩遊戲輸錢";
                $target_table = "coolcoin_wins_" . strtolower($currency);
            } elseif ($type == 6) {
                $itemdesc     = "投注";
                $target_table = "coolcoin_wins_" . strtolower($currency);
            } elseif ($type == 7) {
                $itemdesc     = "扣稅";
                $target_table = "coolcoin_wins_" . strtolower($currency);
            } elseif ($type == 8) {
                $itemdesc = "出金";
            } elseif ($type == 9) {
                $itemdesc = "活動-新會員註冊送死碼幣";
                $note     = "活動"; //備註
            } elseif ($type == 10) {
                $itemdesc = "活動-別人輸入你推薦碼加幣FSG";
                $note     = "活動"; //備註
            } elseif ($type == 11) {
                $itemdesc = "FSG兌換FSC-新會員活動";
                $note     = "活動"; //備註
            } elseif ($type == 12) {
                $itemdesc = "FSG兌換FSC-老手帶新手活動";
                $note     = "活動"; //備註
            } elseif ($type == 13) {
                $target_table = "log_returns";
                $itemdesc     = "返水活動";
                $note         = "活動"; //備註
            } else {
                $itemdesc = $type . "號原因加幣";
                $note     = "加幣原因未創建";
            }

            //新增資料
            $sql = "INSERT INTO `coolcoin_buys_fsc` (`id`, `uid`, `line_id`, `coolcoin`, `itemdesc`, `itemdesc_id`, `target_table`, `target_id`, `add_time`, `delete_time`, `note`, `jdb`, `txhash`, `status`)
            VALUES (NULL, NULL, '$line_id', '$coolcoin', '$itemdesc', '$type', NULL, '0', NOW(), NULL, '$note', '1', NULL, '0');";
            //echo $sql;
            $ok = mysql_insert_ok($sql, $linkID);
            //成功會返回陣列
            //$ok['ok']=1;
            //$ok['id']=mysqli_insert_id($linkID);//成功返回id
            return $ok; //成功會返回id
        } else {
            return 0;
        }
    }
    //重我方資料庫查餘額
    public function balance_me($linkID, $userId)
    {
        $select_str = 'sum(cast(coolcoin as decimal(18,5))) as a'; //這樣浮點數就沒問題了
        $where_str  = "`line_id`='" . $userId . "'";
        $dbname     = 'coolcoin_buys_fsc';
        $sql        = "SELECT $select_str FROM `$dbname` WHERE $where_str ";
        $coolcoin   = row_sql1p($sql, $s = 0, $linkID);
        //避免為NULL處理 S--------
        $total_balance = (float) $coolcoin;
        //避免為NULL處理 END--------
        return $total_balance; //返回總餘額
    }

    //開始遊戲-寫下答案紀錄//遊戲預設結束時間為2小時
    public function gamestart($linkID, $groupId, $userId, $gameid, $ok_answer, $hour = 2)
    {
        global $roomtype; //房間類型 你在哪一種房間內0=私訊1=群組，2會議室
        //遊戲預設結束時間為2小時
        //新增資料
        $sql = "INSERT INTO `bot_game` (`sno`,`room`,`groupId`, `userId`, `date`, `add_datetime`,`end_datetime`, `gameid`, `s`, `ok_answer`, `end`)
                    VALUES (NULL,'$roomtype','$groupId', '$userId', NOW(), NOW(), DATE_add(now(), INTERVAL $hour hour), '$gameid', '0', '$ok_answer','1');";
        $ok = mysql_insert_ok($sql, $linkID);
        //成功會返回陣列
        return $ok; //成功會返回id
    }

    /**
     * 得到幾顆骰子骰1次的結果
     */
    public function game_suibao($s)
    {
        $str = "";
        for ($i = 0; $i < $s; $i++) {
            $num = mt_rand(1, 6);
            $str .= $num;
        }
        return intval($str);
    }

    /**
     * 得到3個骰子數
     */
    public function getRandNumber()
    {
        $num   = mt_rand(1, 9);
        $array = array_diff(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), [$num]);
        shuffle($array);
        $subarr = array_slice($array, 0, 3); //再取 3 個數字
        $str    = implode('', array_merge([$num], $subarr));
        return intval($str);
    }

    //關閉指定遊戲
    public function close_thegame($sno, $linkID)
    {
        $sno = (int) $sno;
        $sql = "UPDATE `bot_game` SET `end` = '0' WHERE `bot_game`.`sno` =" . $sno;
        $ok  = mysql_up($sql, $linkID);
        return $ok;
    }
    //提前關閉遊戲
    public function close_thegame_advance($sno, $linkID)
    {
        $sno = (int) $sno;
        $sql = "UPDATE `bot_game` SET `end` = '0' WHERE `bot_game`.`sno` =" . $sno;
        $ok  = mysql_up($sql, $linkID);
        return $ok;
    }

    //檢查目前進行中遊戲 參數群組$groupId
    public function checking_gamestart($linkID, $groupId)
    {
        //查詢資料 遊戲結束時間未到且end=1的
        $sql  = "SELECT * FROM `bot_game` WHERE `groupId` = '$groupId' AND  now() < `end_datetime` AND end = 1 ORDER BY `bot_game`.`add_datetime` DESC";
        $data = assoc_sql1p($sql, $linkID);
        //成功會返回陣列
        return $data; //成功會返回id
    }
    //檢查目前進行中遊戲 參數使用者ID$groupId
    public function checking_gamestart_me($linkID, $userId)
    {
        //查詢資料 遊戲結束時間未到且end=1的
        $sql  = "SELECT * FROM `bot_game` WHERE `userId` = '$userId' AND  now() < `end_datetime` AND end = 1 ORDER BY `bot_game`.`add_datetime` DESC";
        $data = assoc_sql1p($sql, $linkID);
        //成功會返回陣列
        return $data; //成功會返回id
    }

    //寫入遊戲歷程紀錄 同時 猜的次數+1
    //$api_gamemoney->up_gamehistory_json_s($linkID,$sno,$gamehistory_json);//寫入遊戲歷程紀錄,同時猜的次數+1
    public function up_gamehistory_json_s($linkID, $sno, $gamehistory_json)
    {
        $gamehistory_json2=json_encode($gamehistory_json,JSON_UNESCAPED_UNICODE);//避免json中文亂碼
        $sql = "UPDATE `bot_game` SET `gamehistory_json` = '" . $gamehistory_json2 . "',`s` = `s`+1 WHERE `bot_game`.`sno` =" . $sno; //猜的次數+1
        $ok  = mysql_up($sql, $linkID);
    }
    //修改猜的次數 參數$linkID=連線ID,$userId=用戶ID,$gameid=遊戲ID,$sno=遊戲資料SNO,$number=本輪猜的答案,$yes=猜對是1錯是0
    //api_gamemoney->numberofguesses($linkID,$userId,$gameid,$sno,$number,1);
    public function numberofguesses($linkID, $userId, $gameid, $sno, $number, $yes)
    {
        $sno = (int) $sno;
        //猜的次數+1
        $sql = "UPDATE `bot_game` SET `s` = `s`+1 WHERE `bot_game`.`sno` =" . $sno; //猜的次數+1
        $ok  = mysql_up($sql, $linkID);
        //有獲勝者出線的話
        //紀錄遊戲最後獲勝者
        if ($yes == 1 && $userId) {
            $sql = "UPDATE `bot_game` SET `winningplayer_uid` = '" . $userId . "' WHERE `bot_game`.`sno` =" . $sno; //猜的次數+1
            $ok  = mysql_up($sql, $linkID);
        }
        return $ok;
    }

    //查匯率
    public function get_currency($linkID)
    {
        //匯率換算資料 S***********************************************************/
        $sql        = "SELECT * FROM sdabi_otc.oc_currency";
        $query_rows = assoc_sql($sql, $linkID);
        for ($i = 0; $i < count($query_rows); $i++) {
            $a                    = $query_rows[$i];
            $currency[$a['code']] = $a['value']; //對台幣的匯率
        }
        //匯率換算資料 END**********************************************************/
        return $currency;
    }
}
