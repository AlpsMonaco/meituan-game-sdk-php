<?php
ini_set('display_errors', 1);


class meituan
{
    public static function createKey($password)
    {
        $key = sha1($password, true);
        $key = $key . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0);
        return base64_encode($key);
    }

    public static function encryptSHA1Str($data)
    {
        return sha1($data);
    }

    public static function stringToBytes($str)
    {
        return unpack('c*', $str);
    }

    public static function bytesToString($buf)
    {
        return  pack("c*", ...$buf);
    }

    public static function printArray(array &$a)
    {
        echo "[" . implode(" ", $a) . "]\r\n";
    }

    public static function decryptWithAESGCM256($key, $data)
    {  
        $key = self::base64DecodeJavaVersion($key);

        $data = self::base64DecodeJavaVersion($data);
        $bytes = unpack('c*', $data);

        $nonceArray = array();
        for ($i = 1; $i <= 16; $i++) array_push($nonceArray, $bytes[$i]);
        $nonce =  pack("c*", ...$nonceArray);

        $contentArray = array();
        for ($i = 17; $i <= count($bytes); $i++) array_push($contentArray, $bytes[$i]);

        $tagArr = array();
        $dataArr = array();

        for ($i = count($bytes) - 15; $i < count($bytes) + 1; $i++) array_push($tagArr, $bytes[$i]);
        for ($i = 17; $i < count($bytes) - 15; $i++) array_push($dataArr, $bytes[$i]);

        $data = pack("c*", ...$dataArr);
        $tag = pack("c*", ...$tagArr);

        return openssl_decrypt($data, "AES-256-GCM", $key, OPENSSL_NO_PADDING | OPENSSL_RAW_DATA, $nonce, $tag);
    }

    public static function base64DecodeJavaVersion($data)
    {
        $data = str_replace("-", "+", $data);
        $data = str_replace("_", "/", $data);
        return base64_decode($data);
    }

    public static function base64EncodeJavaVersion($data)
    {


        $data = base64_encode($data);
        $data = str_replace("+", "-", $data);
        $data = str_replace("/", "_", $data);
        return $data;
    }

    public static function bytesToStr($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }

    /**
     * 字符串转Byte数组
     * @param  string $string
     * @return array
     */
    public static function strToBytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }
}


$appId  = "appid here";
$appSecret = "app secret here";
$secretKey = meituan::createKey($appId . "&" . $appSecret);
$data = "miwen";
$sign = meituan::encryptSHA1Str($data . $secretKey); //useless

// key 是base64_encode 后的;
// key is a base64_encode value;
var_dump(meituan::decryptWithAESGCM256($secretKey, $data));
