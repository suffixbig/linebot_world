<?php
    $admin_pass = substr(base64_encode(date("Y-m") . $_COOKIE['admin_dir']."s3dfg6gew"), 2, 10); //客戶密碼
    $adminpass2 = substr(base64_encode(date("Y-m") . "s3dfgdfgfdc6gew"), 2, 10); //管理員密碼
if (isset($_COOKIE['adminpass2'])) {
    //有管理員紀錄就不檢查客戶COOKIE
    if ($_COOKIE['adminpass2'] == $adminpass2) {
    //通過    
    }else{
    //不通過
        echo '<p><a href="/login2.php">管理員密碼檢查錯誤</a></p>';
        exit;
    }
}elseif(isset($_COOKIE['admin_dir'])){
            //1.驗證COOKIE無修改
            if( $_COOKIE['admin_pass']==$admin_pass){
            //2.驗證如果不是在自己的目錄
                if($_COOKIE['admin_dir']  !=  $im_dir){
                $to_dir	="/".$_COOKIE['admin_dir'] ;
                echo '<p><a href="'.$to_dir.'">到您的目錄</a></p>';
                echo '<p><a href="/loginok.php?type=del">登出</a></p>';
                exit;
                }  
            }else{
               	echo '<p><a href="/login.php">COOKIE驗算錯誤</a></p>';
                exit;
            }
}else{
	echo '<p><a href="/login.php">您沒有登入</a></p>';
	exit;
}
//有登入且有$_COOKIE['admin_dir']的才能往下走