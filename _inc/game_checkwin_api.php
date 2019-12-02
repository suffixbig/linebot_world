<?php
//檢查輸贏判斷 參數買的類型,多少錢,骰出結果
class game_checkwin_api
{
    public $sicbo_type_Idx  = array("BETWEEN[4,10]", "BETWEEN[11,17]", "MATCH[ALL]", "ODDNUMBER", "DOUBLENUMBER", "MATCH[1]", "MATCH[2]", "MATCH[3]", "MATCH[4]", "MATCH[5]", "MATCH[6]");
    public $sicbo_type_Name = array("買小", "買大", "買全圍", "買單數", "買雙數", "買單骰1", "買單骰2", "買單骰3", "買單骰4", "買單骰5", "買單骰6");
    public $sicbo_name      = []; //英文名稱對應中文
    public $sicbo_name_r    = []; //中文名稱對應英文
    public function __construct()
    {
        $this->sicbo_name   = $this->GetIndexName($this->sicbo_type_Idx, $this->sicbo_type_Name); //代換陣列
        $this->sicbo_name_r = $this->GetIndexName($this->sicbo_type_Name, $this->sicbo_type_Idx); //代換陣列
    }
    //檢查輸贏判斷 參數買的類型,多少錢,骰出結果//錢不可以帶入負數
    //$m=$game_checkwin_api->sicbo_win($v['type'],$ok_answer);//檢查輸贏判斷 參數買的類型,多少錢,骰出結果返回值0以上表示贏錢倍數
    public function sicbo_win($type, $ok_answer2)
    {
        $is_leopard = 0; //是否豹子
        $ok_answer2 = (string) $ok_answer2;
        //骰子結果不能不帶
        if (empty($ok_answer2) || strlen($ok_answer2) != 3) {
            return false;
        }
        $ok_answer       = str_split($ok_answer2);
        $ok_answer_count = array_count_values($ok_answer);//統計
        $g               = array();
        $g['cofferdam']  = 0; //有幾個相同
        foreach ($ok_answer_count as $k => $v) {
            if ($v == 2) {
                $g['cofferdam']  = 2; //出現2個相同
                $g['cofferdam2'] = $k;
            } elseif ($v == 3) {
                $g['cofferdam']  = 3; //出現3個相同
                $g['cofferdam2'] = $k;
                $is_leopard      = 1; //是否豹子
            }
        }
        $sum = array_sum($ok_answer); //點數總和
        
        if ($type == 'BETWEEN[4,10]') {
            //買小
            if ($is_leopard == 0) {
                if ($sum < 11) {
                    return 2; //贏2倍
                }
            }
        } elseif ($type == 'BETWEEN[11,17]') {
            //買大
            if ($is_leopard == 0) {
                if ($sum > 10) {
                    return 2; //贏2倍
                }
            }
        } elseif ($type == 'MATCH[ALL]') {
            if ($is_leopard) {
                return 31; //贏幾倍
            }
        } elseif ($type == 'ODDNUMBER') {
            //總和點數為5, 7, 9, 11, 13, 15, 17 點則為單數
            if ($is_leopard == 0) {
                if ($sum == 5 || $sum == 7 || $sum == 9 || $sum == 11 || $sum == 13 || $sum == 15 || $sum == 17) {
                    return 2; //贏2倍
                }
            }
        } elseif ($type == 'DOUBLENUMBER') {
            //買雙數 總和點數為4, 6, 8, 10, 12, 14, 16
            if ($is_leopard == 0) {
                if ($sum == 4 || $sum == 6 || $sum == 8 || $sum == 10 || $sum == 12 || $sum == 14 || $sum == 16) {
                    return 2; //贏2倍
                }
            }
        } elseif (mb_substr($type, 0, 5) == 'MATCH') {
            //買單骰
            $match_arr = mb_substr($type, 6, 1); //切出買的數字
            $y         = 0;
            foreach ($ok_answer_count as $k => $v) {
                if ($match_arr == $k) {
                    $y = $v;
                }
            }
            if ($y == 1) {
                return 2;
            } elseif ($y == 2) {
                return 3;
            } elseif ($y == 3) {
                return 7;
            }
        }
        return 0; //輸
    }

    //陣列字串代換函式   A陣列為主 B陣列為轉換目標 2者數目要相同  若B陣列比數目較小也是會建立 預設的值為無
    public function GetIndexName($aryIndex, $aryName)
    {
        $sBuf = [];
        if (count($aryIndex) != count($aryName)) {echo "A欄位" . count($aryIndex) . "筆B欄位" . count($aryName) . "筆，有資料欄位數目不相同，無法轉換";exit;}
        for ($i = 0; $i < count($aryIndex); $i++) {
            $sBuf[$aryIndex[$i]] = $aryName[$i];
        }
        return $sBuf;
    }
}
