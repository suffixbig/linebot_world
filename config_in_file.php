<?php
/*
全網站共用系統參數設定檔2016/05/17
務必放在網站根目錄下-且勿必在編輯此檔本尊勿編輯此檔分身
*/
//header("Content-Type:text/html;charset=utf-8");//全程式總編碼指定
date_default_timezone_set('Asia/Taipei');//設定系統時區
ini_set('display_errors', '1');//顯示錯誤
//error_reporting(E_ALL | ~E_WARNING| ~E_NOTICE);//顯示錯誤等級-都顯
//error_reporting(E_ERROR & ~E_NOTICE & ~E_WARNING);////變數為空不要提示正常的
define('WEB_ROOT_PATH', str_replace('\\','/',dirname(__FILE__))); //目前檔案所在絕對路徑，尾端沒協線
define('INCLUDE_PATH', WEB_ROOT_PATH . '/_inc');//設定檔所在目錄
define('DBSAVE_PATH', WEB_ROOT_PATH . '/save_jsondb/');//jsondb檔所在目錄,字尾有/線
define('LOG_PATH', WEB_ROOT_PATH . '/save_log/');//log檔所在目錄,字尾有/線
/*
讀取設定檔沒有設定檔config.ini的話就建立
設定檔會有2個設定值 $channelAccessToken = $config['Channel']['Token'];
*/
if (file_exists(__DIR__ . '/config.ini')) {
    $config = parse_ini_file("config.ini", true); // 解析配置檔
    if ($config['Channel']['Token'] == Null || $config['Channel']['Secret'] == Null) {
        error_log("config.ini 配置檔未設定完全！", 0); // 輸出錯誤
    } else {
        $channelAccessToken = $config['Channel']['Token'];
        $channelSecret = $config['Channel']['Secret'];
    }
} else {
    //建立文件
    $configFile = fopen("config.ini", "w") or die("Unable to open file!");
    $configFileContent = '; Copyright 2019 GoneTone
;
; Line Bot
; 範例 Example Bot 配置文件
; 官方文檔：https://developers.line.biz/en/reference/messaging-api/

[Channel]
; 請在雙引號內輸入您的 Line Bot "Channel access token"
Token = ""

; 請在雙引號內輸入您的 Line Bot "Channel secret"
Secret = ""
';
    fwrite($configFile, $configFileContent); // 建立文件並寫入
    fclose($configFile); // 關閉文件
    error_log("config.ini 配置檔建立成功，請編輯檔案填入資料！", 0); // 輸出錯誤
}