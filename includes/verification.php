<?php
/**
 * line 給驗證碼程式
 * 程式作用打 給我驗證碼 就會給一個驗證碼 snv_9831688 (此驗證碼每個人絕對不重複)
 * 一個LINE帳號有驗證成功之後就不能再驗證，會出現
 * 您的LINE帳號已經綁定會員:qazwsx，除非刪除此會員帳號，否則不能再次驗證
 * UPDATE `verification_lineid` SET `uid` = '766', `approved` = '1', `ok_time` = NOW() WHERE `verification_lineid`.`id` = 3;//驗證成功的更新SQL方法
 */
//php產生隨機序號 參數你要幾碼
function random_string9($length = 10)
{
    $characters       = '0123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//檢查驗證碼重複
function check_verificationcode($verificationcode, $linkID)
{
    $sql  = 'SELECT * FROM `verification_lineid` WHERE verificationcode =\'' . $verificationcode . '\' AND approved=0'; //未驗證過
    $data = assoc_sql1p($sql, $linkID);
    if ($data['verificationcode']) {
        return true;
    } else {
        return false;
    }

}

$phone = "";
$pwd   = "";
if ($roomtype == 0) {
    $sql                 = "SELECT * FROM `verification_lineid` WHERE  `line_id` = '$userId' AND approved=1"; //驗證通過的人
    $authenticatedperson = assoc_sql1p($sql, $linkID);
    if (isset($authenticatedperson['uid'])) {
        $sql                  = "SELECT * FROM `user` WHERE  `id` = '" . $authenticatedperson['uid'] . "' AND is_auth=1"; //驗證通過的人
        $authenticatedperson2 = assoc_sql1p($sql, $linkID);
        $phone                = $authenticatedperson2['phone']; //查出手機
        $pwd                  = $authenticatedperson2['pwd']; //查出密碼
    }
}

if ($roomtype == 0 and $message['text'] == "給我驗證碼") {
    //查資料
    //有就撈
    //沒有就新增
    //查資料 有就撈 沒有就新增
    $sms = "無法預期的錯誤";
//先查後修或新增
    $sql    = 'SELECT * FROM `verification_lineid` WHERE `line_id` =\'' . $userId . '\' ';
    $select = assoc_sql1p($sql, $linkID);
    if ($select['verificationcode']) {
//有就撈
        if ($select['approved'] == 0) {
            $sms = $select['verificationcode'];
        } else {
            $sql     = 'SELECT * FROM `user` WHERE `id` =\'' . $select['uid'] . '\' ';
            $select2 = assoc_sql1p($sql, $linkID);
            $sms     = "您的LINE帳號已經綁定會員:" . $select2['user'] . "，除非刪除此會員帳號，否則不能再次驗證";
        }
    } else {
//沒有就新增
        $off = 0;
        //會一值跑，直到驗證碼不重複
        while ($off < 1) {
            $verificationcode = random_string9(7); //隨機驗證碼
            //查驗證碼不能重複
            if (check_verificationcode($verificationcode, $linkID) == false) {
                //資料寫入投票紀錄
                $sql = "INSERT INTO `verification_lineid` (`id`, `line_id`, `verificationcode`, `approved`, `add_time`, `ok_time`) VALUES ";
                $sql .= "(NULL, '$userId', '$verificationcode', '0', now(), NULL);";
                $ok  = mysql_insert_ok($sql, $linkID);
                $sms = $verificationcode;
                $off = 1;
            }
        }
    }

    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages'   => array(
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $sms, // 回復訊息
            ),
        ),
    ));

} elseif ($roomtype == 0 and strlen($phone)>5 and $message['text'] == $phone) {
    $sms = "您的密碼是:" . $pwd;
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages'   => array(
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $sms, // 回復訊息
            ),
        ),
    ));
} elseif ($roomtype == 0 and $message['text'] == "忘記密碼") {
    $sms = "輸入你註冊時的的手機號碼，就會顯示你的密碼";
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages'   => array(
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $sms, // 回復訊息
            ),
        ),
    ));
} elseif ($roomtype == 0 and $message['text'] == "常見問題") {
    $sms = "常見問題,收集中";
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages'   => array(
            array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $sms, // 回復訊息
            ),
        ),
    ));
}
