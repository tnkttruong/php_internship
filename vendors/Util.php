<?php
App::uses('CryptUtil', 'Lib');

class Util {
    
    /**
     * 暗号化（URL）関数
     * @param string $str
     * @return string $urlEncode
     */
    public static function urlEncode($str) {
        $encodeData = CryptUtil::encrypt($str);
        $urlEncode = urlencode($encodeData);
        return $urlEncode;
    }
    
    /**
     * 複合化（URL）関数
     * @param string $str
     * @return mixed $decodeData
     * もしユーザーはurlでパラメーターに数字を入力する場合が、urlDecode関数を呼ぶの結果は文字化けです。
     * しかし文字化けの始めは数字なら、sql queryは直接的この数字が条件を検索します。
     * これを防ぐために、urlDecode関数の戻る値のチェックが必要です。
     * 戻る値の正しい形は数字の場合に、flg=1(今は初期値)が設定して。戻る値は数字じゃないなら、falseを戻ります。
     */
    public static function urlDecode($str, $intFlg = 1) {
        $urlDecode = urldecode($str);
        $decodeData = CryptUtil::decrypt($urlDecode);
        if ($intFlg) {
            if (!is_numeric($decodeData)) {
                $decodeData = false;
            } 
        }
        return $decodeData;
    }

}