<?php
/*
2014/05/14
suffixbig
*/

//php二維陣列排序 第一個參數放陣列，第二個參數放要排序的欄位名
//升降羃flag有 asc 或 desc
function array_csort($arr,$keys,$type) {  
if(is_array($arr) && $keys)
{
	$keysvalue=array(); 
	$i = 0; 
	foreach($arr as $key=>$val) {  
	$val[$keys] = str_replace("-","",$val[$keys]); 
	$val[$keys] = str_replace(" ","",$val[$keys]); 
	$val[$keys] = str_replace(":","",$val[$keys]); 
	$keysvalue[] =$val[$keys];     }   
	asort($keysvalue); //key值排序  
	reset($keysvalue); //指針重新指向數組第一個  
	foreach($keysvalue as $key=>$vals) {
   $keysort[] = $key;   } 
     $new_array = array();  
	 if($type != "asc"){  
	 for($ii=count($keysort)-1; $ii>=0; $ii--)
	  {  
	   $new_array[] = $arr[$keysort[$ii]];  
	 }  
		 
	}else{  for($ii=0; $ii<count($keysort); $ii++){  $new_array[] = $arr[$keysort[$ii]];  }  }  
	return $new_array;  
 }else{ return false;}
}  

//查參數裏面是否純數字 否就中斷
function is_num($str){
	if(is_numeric($str)){
	return $str;
	}else{
	echo "參數非純數字";exit;	
	}
}

//查參數裏面是否有空格-有空格就中斷-不能讓SQL裏面有空格
 function check_mysql($str){
 	if(is_array($str)){
		foreach($str as $key =>$v){
			if(preg_match("@chr\(@i",$str)){
			echo "參數內含非法字元";exit;
			}else if(preg_match("@char\(@i",$str)){
			echo "參數內含非法字元";exit;
			}else if(substr_count($v," ")>0){
 			echo "參數內有非法字元空格";exit;}	
		}	
	}else{
		if(preg_match("@chr\(@i",$str)){
		echo "參數內含非法字元";exit;
		}else if(preg_match("@char\(@i",$str)){
		echo "參數內含非法字元";exit;
		}else if(substr_count($str," ")>0){
 		echo "參數內有非法字元空格";exit;}
	}
	return $str;
 }

/*解決中文檔名下載問題*/
function d_ie_firefox($my_file){
	$my_file=str_replace(" ","_",$my_file);//解決空格問題-把空格換掉
	$my_file=str_replace("#","",$my_file);//解決空格問題
	$my_file=str_replace("'","",$my_file);//解決空格問題
	$my_file=str_replace('"',"",$my_file);//解決空格問題
	$my_file=str_replace('+',"",$my_file);//解決空格問題
	
	if(preg_match("/Firefox/",$_SERVER['HTTP_USER_AGENT']))
	return $my_file;
	return str_replace('+','%20',urlencode($my_file));
}
//MYSQL資料庫收詢不能讓一般人使用%字元
function search_check( $str ) {
   // 判斷magic_quotes_gpc是否打開
   if (MAGIC_QUOTES_GPC) {
   }else{
	 $str = addslashes($str);// 沒開進行過濾  
   }
   $str = str_replace("_", "\_", $str);     // 把 '_'過濾掉
   $str = str_replace("%", "\%", $str);     // 把 '%'過濾掉
   return $str;
}
//收詢結果變色處理 參數1變數 2參數字串 
function ct_search_text($str,$s)
{
$s1="<span class=\"search_text_r\">";
$s2="</span>";
	if($s && $str)
	{
	$str2=str_replace($s,$s1.$s.$s2,$str);	
	}else{
	$str2=$str;
	}
return $str2;
} 


/*字串處理區 開始==============================================================================================*/

//切utf8碼中文字串 參數 $rBuf字串 $no字數 參數3可省 後面加上刪節符號... 或不加
 function cut_string($rBuf,$no,$dot = '...')
 {
    $len=mb_strlen($rBuf,'utf8');
	$tmpBuf=$rBuf;
	if($no>0)
	{
		if($len>$no)
		{
		$tmpBuf=mb_substr($rBuf,0,$no,'utf8').$dot;
		}
	}
    return $tmpBuf;
 }
 
//切utf8碼中文字串 包含去除HTML 參數 $rBuf字串 $no字數 參數3可省 後面加上刪節符號... 或不加
function cut_string_html($rBuf,$no,$dot = '...')
{ 
//去HTML，換行，和空格，簡介裁100字
$patterns=array("'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 標籤
                "'/\s+/'",                   	   // 去掉換行字元
				'@'.chr(10).'@i',                  // 去掉換行字元-實驗過這樣才能去掉掉換行
				'@ @i',                  		   // 去掉空白
				"@,@i",						  	   // 去除,號
				"@\)@i",						   // 去除)號
				"@\(@i",						   // 去除(號
				"@（@i",						  	   // 去除（號
				"@）@i",						  	   // 去除）號
				"@'@i",						   // 去除'號
				'@"@i',						   // 去除"號
				);
$str=preg_replace($patterns,"",$rBuf);//去換行和空格等....
return cut_string($str,$no,$dot);
} 
 
//字串處理'一律加反斜線。	非get,post,cookie傳來的字串一定要加反斜線才能入資料庫
function c_addslashes($string){
return addslashes($string);
}

/**
 * 自訂的addslashes函式，可以處理字串或字串陣列
 * @param string|array $string 要進行addslashes處理的字串，可以是單純字串或字串陣列
 * @param int $force 強制進行addslashes處理，不管MAGIC_QUOTES_GPC功能有沒有打開 1=是 0=否
 * @return string|array 處理好的字串或字串陣列
 */
function ct_addslashes($string, $force = 0) {
	//!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());//此行已加在config.ini
	//沒開或$force=1都處理
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = addslashes($val);//把陣列處理完
			}
		} else {
			$string = addslashes($string);//非陣列處理完
		}
	}
	return $string;
}


/**
 * 將HTML符號轉換成相對應的HTML Entity，轉換的符號包括&"<>
 * 可以接受單純字串或字串陣列
 * @param string $string 欲處理的字串
 * @return string 處理好的字串
 */
function ct_htmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = ct_htmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}


/**
 * 顯示alert訊息並跳轉頁面
 * @param string $message 要顯示的訊息，可以放入\n來換行
 * @param string $url 要跳轉的頁面URL
 * @param integer $flag 回上一頁還是跳轉到$url指定的頁面
 */
function ct_goback($message, $url, $flag) {
    global $CT_CONFIG;
    echo "<!DOCTYPE HTML>\n";
	echo "<HTML>\n";
    echo "<HEAD>\n";
    echo "<meta charset=\"".$CT_CONFIG['charset']."\">\n";
    echo "</HEAD>\n";
    echo "<script type=\"text/javascript\">\n";
    if ( trim($message) != "" )
        echo "alert(\"".$message."\");\n";
    switch ($flag) {
        case 0 : echo "history.back();\n";
        break;
        case 1 : echo "document.location.replace(\"".$url."\");\n";
        break;
    }
    echo "</script>\n";
	echo "<body>\n";
	echo "</body>\n";
	echo "</HTML>\n";
}

/*字串處理區 END==============================================================================================*/

/*陣列涵式區 開始==============================================================================================*/

//一維陣列  取單值 參數1陣列 2.欄位名 (一唯陣列用)
function arrayx01($array,$name=0)
{
if(is_array($array))
	{
	foreach ($array as $key=>$value)
		{
		if($key==$name) 
		$arrayB=$value;
		}
		return $arrayB;
	}else {return false;}
}

//將二唯陣列轉一維陣列 以陣列中單一欄位建立新1維陣列 參數1陣列 2.欄位 (二唯陣列用)
function arrayx02($array,$name=0)
{
if(is_array($array))
	{
	   
	for($i=0; $i< count($array);$i++)
	{
	   if(is_array($array[$i]))
	   {
	   		foreach ($array[$i] as $key=>$value)
		   		{
		      		if($key==$name)
		      		{$arrayB[]=$value; }
		   		}
		}   				
	}
	return $arrayB;
	
	}else {return false;}
}

/*陣列涵式區 END==============================================================================================*/




//判斷文字是否UTF8編碼
function  is_utf8($str)  {
    $i=0;
    $len  =  strlen($str);

    for($i=0;$i<$len;$i++)  {
        $sbit  =  ord(substr($str,$i,1));
        if($sbit  <  128)  {
            //本字節為英文字符，不與理會
        }elseif($sbit  >  191  &&  $sbit  <  224)  {
            //第一字節為落於192~223的utf8的中文字(表示該中文為由2個字節所組成utf8中文字)，找下一個中文字
            $i++;
        }elseif($sbit  >  223  &&  $sbit  <  240)  {
            //第一字節為落於223~239的utf8的中文字(表示該中文為由3個字節所組成的utf8中文字)，找下一個中文字
            $i+=2;
        }elseif($sbit  >  239  &&  $sbit  <  248)  {
            //第一字節為落於240~247的utf8的中文字(表示該中文為由4個字節所組成的utf8中文字)，找下一個中文字
            $i+=3;
        }else{
            //第一字節為非的utf8的中文字
            return  0;
        }
    }
    //檢查完整個字串都沒問體，代表這個字串是utf8中文字
    return  1;
}

//字串轉HTML16編碼輸出
//參數1-輸入編碼,參數2變數,參數3為英文是否轉換1為轉預設不轉
function nochaoscode($encode, $str, $isbail = false) {
    $str = iconv($encode, "utf-16", $str);
    for ($i = 0; $i < strlen($str); $i++,$i++) {
        $code = ord($str{$i}) * 256 + ord($str{$i + 1});
        if ($code < 128 and !$isbail) {
            $output .= chr($code);
        } else if ($code != 65279) {
            $output .= "&#".$code.";";
        }
    }
    return $output;
}

//代入時間-和現在時間相比差多久
//小於3600秒以分為單位，小於86400以小時為單位，大於等於86400秒以天為單位
function timego($date){

$a=time()-strtotime($date);
if($a > 2592000){
$b=floor($a/2592000);
return $b."月前";
}else if($a > 604800){
$b=floor($a/604800);
return $b."週前";
}else if($a > 86400){
$b=floor($a/86400);
return $b."天前";
}else if($a > 3600){
$b=floor($a/3600);
return $b."小時前";
}else if($a > 60){
$b=floor($a/60);
return $b."分鐘前";
}else if($a > 1){
$b=floor($a);
return $b."秒前";
}else{
return "0秒前";
}


}
/* 將秒數轉成  x分鐘x秒 這樣的字串
 * @param int $seconds 秒數
 * @return string 未超過1小時 顯示x分鐘x秒 超過1小時 顯示X小時X分
 */
        function convertSeconds($seconds) {
            $seconds = (int)abs($seconds);
            
            $point_string="";
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds%3600)/60);
            $seconds %= 60;
            
			if($hours!=0)
               $point_string .= $hours.'小時';
           
            //有超過1小時就不顯示分秒
			if($hours<1)
			{

			if($minutes != 0)
               $point_string .= $minutes.'分鐘';
            else if($point_string!="")
               $point_string .= '0分鐘';


			//有超過分就不顯秒
			if($minutes <1)
			{

			if($seconds != 0)
               $point_string .= $seconds.'秒';
            else if($point_string!="")
               $point_string .= '0秒';
			}  
			
			}	
					   
            if($point_string=="")
               $point_string = "0秒";						   			   
            return $point_string;
}



//排序函式-有下排序條件頁數就歸零
function ppp2($sale1,$title){
global $pageURL2,$sale; //全域變數宣告 $sale1=變數  $title=項目文字
	$_file1=$pageURL2;
	if($sale==$sale1)
	{
		$_A='<a class="list_desc_on" title="日前依'.$title.'遞減排序" href='.$_file1.'sale='.$sale1.'B>'.$title.'</a><span class="order_flag">▼</span>';
	}else if ($sale==$sale1."B"){
		$_A='<a class="list_asc_on" title="日前依'.$title.'遞增排序" href='.$_file1.'sale='.$sale1.'>'.$title.'</a><span class="order_flag">▲</span>';
	}else{
		$_A='<a class="list_desc" title="依'.$title.'遞增排序" href='.$_file1.'sale='.$sale1.'B>'.$title.'</a>';//預設
	}
return $_A;
}

//分頁選單用的-修正過20130727============================================================================
//參數 $type=0 頁數全顯，type=1頁數大於40個會只顯示頭尾10號和中間
function PageCount($type=0){
//全域變數宣告，總頁數，目前頁，網址參數
global $ALL_PAGE,$iPage,$pageURL;

if($type)
 {
	//當頁碼過大時 顯示前10 後10和中間20 最多40個頁碼
	if($ALL_PAGE>=40){
	$y=10;
	$yc=floor($ALL_PAGE/2)-5;
	$yend=($ALL_PAGE-10);
	}
 }
	for($i=1;$i<=$ALL_PAGE;$i++)
	{
		$selected=($i==$iPage)?"selected":"";  
        $scr.="<option value=".$pageURL."page=".$i."  ".$selected." >".$i."</option>";
			if($y){
				if($i==$y){
					$scr.="<option value=\"#\" >...</option>";	
					$i=$yc;
				}
				if($i>$yc)
				$zz++;
				if($zz==10){
					$scr.="<option value=\"#\" >...</option>";	
					$i=$yend;
				}
			} 
	}
return $scr;			 
}
//以下為分頁用的參數2013/07/27--------------------------------------------------------
//參數$ALL_PAGE＝總分頁數,$iPage=目前,$PageCount 是否關掉快速跳頁頁,$kk=1一頁最多顯示多少號碼一定要奇數不能偶數
//global參數 $pageURL=本頁網址
function page_1234($ALL_PAGE,$iPage,$pageCount=1,$kk=11)
{
global $pageURL;	
	if(empty($ALL_PAGE))
	{
	$data_txt="<div  style=\"position: absolute;top: 10px;left:50%;\" >抱歉!查無資料</div>";
	}
 //總頁數大於1頁才顯示
 if($ALL_PAGE>1)
 {
//跳頁鈕
	if($pageCount){
    $jump_menu ='<select name="select2"   onchange="window.location.href=this.options[this.selectedIndex].value;">';
    $jump_menu .=PageCount(1);//跳頁
    $jump_menu .='</select>';
	}
	
//有資料開始	
	$data_txt ="<DIV id='pagenum' class='blueott' >";
	if($kk%2){
	//一頁最多顯示多少號碼如果是偶數就+1改為奇數
	}else{$kk=$kk+1;}
	$kl= intval(floor(($kk-1)/2));//一頁最多顯示多少號碼一定要奇數不能偶數
	$kr=$kl;
	if(empty($iPage))
	$iPage=1;//預設在第1頁
//分頁功能開始
	if($ALL_PAGE <= $kk){
	//總頁數小於-一頁要顯示的號碼
	$k_start=1;//起始數
	$k_end=$ALL_PAGE;//結尾數
	}else if($iPage >=($ALL_PAGE-$kr)){
	//目前頁-座落在頁尾範圍
	$k_start=$ALL_PAGE-$kk+1;
	$k_end=$ALL_PAGE;//結尾數
	}else if($iPage <=$kl){
	//目前頁-座落在頁頭範圍
		$k_start=1;
		$k_end=$kk;
	}else{
	  //目前頁-在其他範圍
		$k_start=$iPage-$kl;
		$k_end=$iPage+$kr;//要顯示的頁尾數
	}
		//首頁
		//如果目前頁看不道第一頁 開頭多顯示...
		if( $iPage > $kl+1 )
		{
		$data_txt .= "<a href='".$pageURL."page=1".$_to_a."'>第1頁</a>";//顯示頁數
		}
		//目前大於第1頁就顯示上一頁
		if( $iPage > 1 )
		{
			$data_txt .= "<a href='".$pageURL."page=".($iPage-1).$_to_a."' class='prev' >上一頁</a>";
			$last_url =$pageURL."page=".($iPage-1);//大的上一頁!!!!!!!
		}	
	//多少號碼換號
	for($i=$k_start;$i<=$k_end;$i++)
	{
    	if( ($i) == $iPage ){
 		$data_txt .= "<span class='PageButton2'>".($i)."</span>";//頁數在目前頁換樣式
    	}else{
 		$data_txt .= "<a href='".$pageURL."page=".($i).$_to_a."'>".($i)."</a>";//顯示頁數  
		}
	}	
//判斷目前的頁數是否是最末頁
//小於最末頁就顯示下一頁和最末頁
	if( $iPage < $ALL_PAGE )
	{
		$data_txt .= "<a href='".$pageURL."page=".($iPage+1).$_to_a."' class='next' >下一頁</a>";
		$next_url=$pageURL."page=".($iPage+1);//大的下一頁!!!!!!!
	}	
//頁尾要總頁數大於設定
	if($iPage < $ALL_PAGE && $ALL_PAGE >$kk){ $data_txt .= "<a href='".$pageURL."page=".$ALL_PAGE.$_to_a.")'>尾頁</a>";}
	//顯示頁數
	$data_txt .= "&nbsp; <font size=\"2\" > 共</font>";//2位數和1位數顯示距離差很大
	//總頁數大於3頁才出現
		if($ALL_PAGE>=4){
			if($pageCount){
			$data_txt .= $jump_menu."/".$ALL_PAGE;//跳頁
			}else{
			$data_txt .= $ALL_PAGE;//跳頁
			}
		}else{
		$data_txt .= " ".$ALL_PAGE;
		}
	$data_txt .="<font size=\"2\" > 頁</font></DIV>"; 
}
return $data_txt;
}
//顯示頁數END==================================================


//取代抓網頁函式-快很多 CURLOPT_TIMEOUT 5 
function curl_get01($url){
$ch = curl_init();//宣告
curl_setopt ($ch, CURLOPT_URL, $url);//網址
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE,false );   // 和php5.2有关的一个问题抓圖時會有
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); //是否抓取跳轉後的頁面 //一定要開否則會錯
//https
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);// 跳过host验证
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//沒這行抓無內容 代表可以盲目接受任何伺服器憑證。
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);//等代秒數
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); //抓圖要設2進制1

$page = curl_exec($ch);
curl_close($ch);//關閉cURL資源，並且釋放系統資源
return $page;
}

//取代抓網頁函式-快很多 CURLOPT_TIMEOUT 5 
function curl_file_get_contents($url,$referer='http://www.google.com.tw/webhp?hl=zh-TW'){
$ie_text[1]="Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36";//Chrome
$referer='https://www.youtube.com/?hl=zh-TW&gl=TW&tab=v1';
$ch = curl_init();//宣告
curl_setopt ($ch, CURLOPT_URL, $url);//網址
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE,false );   // 和php5.2有关的一个问题抓圖時會有
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); //是否抓取跳轉後的頁面 //一定要開否則會錯
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//沒這行抓無內容 代表可以盲目接受任何伺服器憑證。
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);//等代秒數
curl_setopt($ch, CURLOPT_REFERER,$referer); //偽造來路頁面
curl_setopt($ch, CURLOPT_USERAGENT,$ie_text[1]);//偽造-搜尋引擎
//curl_setopt($ch, CURLOPT_HTTPHEADER , $head );  //表頭//偽造IP
//touch($cookie_jar);//建立檔案
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);//将之前保存的cookie信息，一起发送到服务器端
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); //抓圖要設2進制1
$page = curl_exec($ch);
curl_close($ch);//關閉cURL資源，並且釋放系統資源
return $page;
}


//模擬IP
function curl_post($url, $data=array(), $timeout=30){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//沒這行抓無內容 代表可以盲目接受任何伺服器憑證。
    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    return $response;
}



//模擬IP
function do_curl($url, $data=array(), $header=array(), $referer='', $timeout=30){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//沒這行抓無內容 代表可以盲目接受任何伺服器憑證。
    // 模拟来源
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    return $response;
}

//UTF-8切字串
function utf8_strlen($string) {
	return mb_strlen($string);
}

function utf8_strpos($string, $needle, $offset = 0) {
	return mb_strpos($string, $needle, $offset);
}

function utf8_strrpos($string, $needle, $offset = 0) {
	return mb_strrpos($string, $needle, $offset);
}

function utf8_substr($string, $offset, $length = null) {
	if ($length === null) {
		return mb_substr($string, $offset, utf8_strlen($string));
	} else {
		return mb_substr($string, $offset, $length);
	}
}

function utf8_strtoupper($string) {
	return mb_strtoupper($string);
}

function utf8_strtolower($string) {
	return mb_strtolower($string);
}
//UTF-8切字串 END



    //由於mcrypt_get_block_size('des', 'ecb');//這PHP 7.2 已被棄用 要用又必須把php文件夾下的libmcrypt.dll文件拷貝到c:/windows/system32目錄下很麻煩
	//所以改用opensslen

    /**
     * 解密字符串
     * @param string $data 字符串
     * @param string $key 加密key
     * @return string
     */
    function opensslde($data,$key){
		$iv = "sdf3245678901237";//只能16位
        return openssl_decrypt(base64_decode($data),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
    }

    /**
     * 加密字符串
     * 参考网站： https://segmentfault.com/q/1010000009624263
     * @param string $data 字符串
     * @param string $key 加密key
     * @return string
     */
    function opensslen($data,$key){
		$iv = "sdf3245678901237";//只能16位
      	return base64_encode(openssl_encrypt($data,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
    }

    
    //加密用函式-$key碼任意30碼
    function encode_k30($string,$key) {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $j=0;
        $hash="";
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($string,$i,1));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
         }
        return $hash;
    }
    //解密用函式
    function decode_k30($string,$key) {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $hash="";
        $j=0;
        for ($i = 0; $i < $strLen; $i+=2) {
            $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= chr($ordStr - $ordKey);
        }
        return $hash;
	}

//不重複挑號 參數1=開始數字，參數2=結束數字，參數3=不重複挑幾個預設5個
function NoRand($begin=0,$end=20,$limit=5){ 
	$rand_array=range($begin,$end); 
	shuffle($rand_array);//調用現成的數組隨機排列函數 
	return array_slice($rand_array,0,$limit);//截取前$limit個 	
} 

//生成隨機亂數參數幾碼
function random_string($random=10){
	$randoma="";
	//FOR回圈以$random為判斷執行次數
	for ($i=1;$i<=$random;$i=$i+1)
	{
	//亂數$c設定三種亂數資料格式大寫、小寫、數字，隨機產生
	$c=rand(1,3);
	//在$c==1的情況下，設定$a亂數取值為97-122之間，並用chr()將數值轉變為對應英文，儲存在$b
	if($c==1){$a=rand(97,122);$b=chr($a);}
	//在$c==2的情況下，設定$a亂數取值為65-90之間，並用chr()將數值轉變為對應英文，儲存在$b
	if($c==2){$a=rand(65,90);$b=chr($a);}
	//在$c==3的情況下，設定$b亂數取值為0-9之間的數字
	if($c==3){$b=rand(0,9);}
	//使用$randoma連接$b
	$randoma=$randoma.$b;
	}
	return $randoma;
}

//rand_shuffle($array)給數組隨機取1個答案
function rand_shuffle($array){
	shuffle($array);
	return $array[0];
}




    //用GAMIL方式發EMAIL
    //收件人，標題，內容,參數4=過程訊息是否顯示,參數5=信件內容HTML,參數6=副本收信人,
    function email_gmail($to_email, $subjec, $body,$SMTPDebug = 0,$ihtml=true,$emailarray=array())
    {
        require DIR_INC . '/api_PHPMailer-master/PHPMailer.php'; // 載入真GAMEL發信
        require DIR_INC . '/api_PHPMailer-master/SMTP.php'; // 載入真GAMEL發信
        $mail = new PHPMailer(); //建立新物件
        $mail->setLanguage('zh'); // 設定語系 沒用的語言可刪掉
        $mail->CharSet = "utf-8"; //郵件編碼

        $mail->SMTPDebug = $SMTPDebug; //2會顯示訊息 0才會不顯示訊息
        $mail->IsSMTP(); //設定使用SMTP方式寄信
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $mail->Username = GMAIL_Username; //Gamil帳號
        $mail->Password = GMAIL_Password; //Gmail密碼
        $mail->From     = infoemail;//寄件者信箱
        $mail->FromName = GMAIL_Name; //寄件者姓名
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $mail->SMTPAuth   = true; //設定SMTP需要驗證
        $mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
        $mail->Host       = "smtp.gmail.com"; //Gamil的SMTP主機
        $mail->Port       = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $subjec; //郵件標題
        $mail->Body    = $body; //信的內容
        // 執行 $mail->AddAddress() 加入收件者，可以多個收件者
		//$mail->AddAddress("to@email.com","Josh Adams");
		
		$mail->AddAddress($to_email, "主要收信人"); //設定 收件者郵件及名稱
		//收件人
			for($i=0;$i<count($emailarray);$i++){
				$to_email2=$emailarray[$i];
				$mail->AddAddress($to_email2);//多收EMAIL人
				//$mail->AddBCC($to_email); //設定 密件副本收件者
			}
			
        try {
            $ok = $mail->send();
            return $ok;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }
    //發EMAIL出去
?>
