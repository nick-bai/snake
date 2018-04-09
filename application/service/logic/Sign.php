<?php
namespace app\service\logic;

/**
 *
 */
class Sign
{
    const CPIC_AESKYE = 'yptotbwx';

    public static $error_msg;

    public static function cpicSign($sign, $data)
    {
        try {
            $tmp_sign = '';
            $tmp_sign .= $data['openid'];
            $tmp_sign .= $data['nickname'];
            $tmp_sign .= $data['isbind'];
            $tmp_sign .= self::CPIC_AESKYE;
        } catch (\Exception $e) {
            self::$error_msg = $e->getMessage();
            return false;
        }

        $tmp_sign = md5($tmp_sign);

        return $sign === $tmp_sign ? true :false;
    }
}
