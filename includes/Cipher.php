<?php
class Cipher {
    private static $key = "CHANGE_THIS_TO_A_32_CHAR_KEY!!"; 
    private static $method = "AES-256-CBC";

    public static function encrypt($data) {
        $iv_length = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, self::$method, self::$key, 0, $iv);
        return base64_encode($encrypted . "::" . $iv);
    }

    public static function decrypt($data) {
        $data = base64_decode($data);
        if (strpos($data, "::") !== false) {
            list($encrypted_data, $iv) = explode("::", $data, 2);
            return openssl_decrypt($encrypted_data, self::$method, self::$key, 0, $iv);
        }
        return false;
    }
}
