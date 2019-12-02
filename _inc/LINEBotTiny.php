<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/*
 * This polyfill of hash_equals() is a modified edition of https://github.com/indigophp/hash-compat/tree/43a19f42093a0cd2d11874dff9d891027fc42214
 *
 * Copyright (c) 2015 Indigo Development Team
 * Released under the MIT license
 * https://github.com/indigophp/hash-compat/blob/43a19f42093a0cd2d11874dff9d891027fc42214/LICENSE
 */
if (!function_exists('hash_equals')) {
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));

    function hash_equals($knownString, $userString)
    {
        $strlen = function ($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }

            return strlen($string);
        };

        // Compare string lengths
        if (($length = $strlen($knownString)) !== $strlen($userString)) {
            return false;
        }

        $diff = 0;

        // Calculate differences
        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($knownString[$i]) ^ ord($userString[$i]);
        }
        return $diff === 0;
    }
}

class LINEBotTiny
{
    private $channelAccessToken;
    private $channelSecret;

    public function __construct($channelAccessToken, $channelSecret)
    {
        $this->channelAccessToken = $channelAccessToken;
        $this->channelSecret      = $channelSecret;
    }

    //接收訊息
    public function parseEvents()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            error_log('Method not allowed');
            exit();
        }

        $entityBody = file_get_contents('php://input');

        if (strlen($entityBody) === 0) {
            http_response_code(400);
            error_log('Missing request body');
            exit();
        }
        //sign驗算
        if (!hash_equals($this->sign($entityBody), $_SERVER['HTTP_X_LINE_SIGNATURE'])) {
            http_response_code(400);
            error_log('Invalid signature value');
            exit();
        }

        $data = json_decode($entityBody, true);
        if (!isset($data['events'])) {
            http_response_code(400);
            error_log('Invalid request body: missing events property');
            exit();
        }
        return $data['events'];
    }

    //送出返回訊息
    public function replyMessage($message)
    {
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", $header),
                'content' => json_encode($message),
            ],
        ]);

        $response = file_get_contents('https://api.line.me/v2/bot/message/reply', false, $context);
        if (strpos($http_response_header[0], '200') === false) {
            http_response_code(500);
            error_log('Request failed: ' . $response);
        }
    }

    //作者suffixbig
    //廣撥訊息 私訊發給特定人
    //參數 $message=訊息，$db_user=用戶UID陣列
    public function multicast($message, $db_user)
    {
        //整理要發送給誰
        if (count($db_user) === 0) {
            echo 'No user login.沒有用戶登錄';
            exit(1);
        } else {
            foreach ($db_user as &$userInfo) {
                $userIds[] = $userInfo['userId'];
            }
        }
        //訊息
        $payload = [
            'to'       => $userIds,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
        ];
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );

        // 發送 發送多播消息 隨時向多個用戶發送推送消息。無法將消息發送到團體或房間。
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/bot/message/multicast');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //作者suffixbig
    //將推送消息發送給用戶，組或會議室。
    //廣撥訊息 參數 $message=訊息，$db_user=用戶UID陣列
    //目標收件人的ID。使用userId，groupId或roomId
    public function push($message, $groupId, $notificationDisabled = false)
    {
        //訊息
        $payload = [
            'to'       => $groupId,
            'messages' => [
                [
                    'type'                 => 'text',
                    'text'                 => $message,
                    'notificationDisabled' => false,
                ],
            ],
        ];
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );

        // 發送 發送多播消息 隨時向多個用戶發送推送消息。無法將消息發送到團體或房間。
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/bot/message/push');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //作者suffixbig
    //取得個人資訊-這個沒有加機器人好友的資訊都抓不到
    /*
    [userId] => Ua4ad191fd85e71fcc85919dcc15343a0
    [displayName] => david台灣碼農
    [pictureUrl] => https://profile.line-scdn.net/0hmJHbtBIWMmV1Gx99DxRNMklePAgCNTQtDSl4VFZLP1MKKXZhTXl0AlhLbABae31gHih7UQNPaQ13
    [statusMessage] => 敢亂邀我試試看-我是反邀群成員
     */
    public function getProfile2($uid)
    {
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        //GET方法
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $header),
            ],
        ]);
        $response = file_get_contents('https://api.line.me/v2/bot/profile/' . urlencode($uid), false, $context);
        return json_decode($response, true);
    }

    //抓群組中的人資訊
    /**
     * 為什麼這麼麻煩因為用https://api.line.me/v2/bot/profile/這個方法 你
     */
    //$user = $client->getProfile($roomtype, $groupId, $userId);//取得用戶資訊
    public function getProfile($roomtype, $groupId, $userId)
    {
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        //GET方法
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $header),
            ],
        ]);
        $groupId = urlencode($groupId);
        $userId = urlencode($userId);
        if ($roomtype == 2) {
            $response = file_get_contents("https://api.line.me/v2/bot/room/$groupId/member/$userId", false, $context);
        } else if ($roomtype == 1) {
            $response = file_get_contents("https://api.line.me/v2/bot/group/$groupId/member/$userId", false, $context);
        } else {
            $response = file_get_contents('https://api.line.me/v2/bot/profile/' . urlencode($userId), false, $context);//要有加機器人好友才抓的到
        }
        return json_decode($response, true);
    }

    //驗證碼
    private function sign($body)
    {
        $hash      = hash_hmac('sha256', $body, $this->channelSecret, true);
        $signature = base64_encode($hash);
        return $signature;
    }

    //統計類
    //獲取已發送的推送消息數
    public function sum_push($date = "")
    {
        if (empty($date)) {
            $date = date('Ymd');
        }
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        //GET方法
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $header),
            ],
        ]);
        $response = file_get_contents('https://api.line.me/v2/bot/message/delivery/push?date=' . $date, false, $context);
        return json_decode($response, true);
    }
    //取得好友人口統計資料
    //檢索機器人朋友的人口統計屬性。此外，您只能查看日本（JP），泰國（TH）和台灣（TW）用戶創建的LINE官方帳戶的朋友信息。
    public function sum_demographic()
    {
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        //GET方法
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $header),
            ],
        ]);
        $response = file_get_contents('https://api.line.me/v2/bot/insight/demographic', false, $context);
        return json_decode($response, true);
    }

    //獲取組成員用戶ID
    //獲取該機器人所在的組的成員的用戶ID。其中包括尚未將LINE官方帳戶添加為朋友或已阻止LINE官方帳戶的用戶的用戶ID
    public function get_group_memberuserid($groupId)
    {
        //檔頭
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelAccessToken,
        );
        //GET方法
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $header),
            ],
        ]);
        //$response = file_get_contents('https://api.line.me/v2/bot/group/'.$groupId.'/members/ids', false, $context);
        $response = file_get_contents('https://api.line.me/v2/bot/group/' . $groupId . '/members/ids');
        return $response;
    }

    //退出群組
    //https://api.line.me/v2/bot/group/{groupId}/leave

    //寫下資料庫紀錄機器人回應紀錄
    function robot_response_dblog($linkID, $groupId, $userId, $type, $text)
    {
        global $roomtype; //房間類型 你在哪一種房間內0=私訊1=群組，2會議室
        $userId = "LINE BOT"; //機器人回應
        //新增資料
        $sql = "INSERT INTO `" . DB_PREFIX . "monitor` (`sno`,`room`,`groupId`, `userId`, `messagetype`, `messagetext`, `date`, `time`, `add_datetime`, `bot`) VALUES 
        (NULL, '$roomtype','$groupId', '$userId', '$type', '$text', NOW(), NOW(), NOW(), '1');";
        $ok  = mysql_insert_ok($sql, $linkID);
        return $ok; //返回id
    }
}
