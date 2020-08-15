<?php

namespace FbBuy\Package\Ecpay\Invoice;

trait Cryption
{
    protected $cipher = 'aes-128-cbc';

    /**
     * 綠界說怎麼算就怎麼算
     * @param  array $data
     * @return string
     */
    public function encrypt(array $data)
    {
        if (openssl_cipher_iv_length($this->cipher) !== strlen($this->hashIv)) {
            throw new \LogicException('hash iv is not valid');
        }

        $encoded = urlencode(json_encode($data));
        return openssl_encrypt(
            $this->addPadding($encoded),
            $this->cipher,
            $this->hashKey,
            OPENSSL_ZERO_PADDING,
            $this->hashIv
        );
    }

    /**
     * 綠界說怎麼算就怎麼算
     * @param  string  $encrypted
     * @return array
     */
    public function decrypt(string $encrypted)
    {
        if (openssl_cipher_iv_length($this->cipher) !== strlen($this->hashIv)) {
            throw new \LogicException('hash iv is not valid');
        }

        $decrypted = $this->stripPadding(
            openssl_decrypt(
                $encrypted,
                $this->cipher,
                $this->hashKey,
                OPENSSL_ZERO_PADDING,
                $this->hashIv
            )
        );
        return json_decode(urldecode($decrypted), true);
    }

    /**
     * 強度 128/8 = 16bytes
     * @param string $str
     * @param int $size
     * @return string
     */
    protected function addPadding(string $str, int $size = 16)
    {
        $len = strlen($str);
        $pad = $size - ($len % $size);
        $str .= str_repeat(chr($pad), $pad);
        return $str;
    }

    /**
     * @param $string
     * @return string
     * @throws \Exception
     */
    protected function stripPadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        }

        throw new \Exception("bad hashed string $string");
    }
}
