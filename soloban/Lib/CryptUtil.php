<?php
App::uses('Security', 'Utility');

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    // = 埋めしなくてもいける
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

/**
 * 暗号化・復号化ユーティリティ
 */
class CryptUtil {

    /**
     * 暗号化する。
     * 
     * @param type $text 暗号化対象文字列
     * @return type 暗号化文字列
     */
    public static function encrypt($text) {
        return base64url_encode(Security::cipher($text, Configure::read('AppSecurity.crypt_key')));
    }

    /**
     * 復号化する。
     *
     * @param type $text 復号化対象文字列
     * @return type 復号化文字列
     */
    public static function decrypt($text) {
        return Security::cipher(base64url_decode($text), Configure::read('AppSecurity.crypt_key'));
    }
}
