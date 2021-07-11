<?php


namespace App\Manager;


class AppManager
{
    const CIPHERING = 'AES-128-CTR';
    const OPTION = 0;
    const ENCRYPT_IV = '1234567891011121';
    const ENCRYPT_KEY = 'GoProGoogleMax';

    /**
     * @return false|string
     */
    public function encrypt(string $plaintext)
    {
        return openssl_encrypt($plaintext, self::CIPHERING, self::ENCRYPT_KEY, self::OPTION, self::ENCRYPT_IV);
    }

    /**
     * @return false|string
     */
    public function decrypt(string $encryption)
    {
        return openssl_decrypt($encryption, self::CIPHERING, self::ENCRYPT_KEY, self::OPTION, self::ENCRYPT_IV);
    }
}