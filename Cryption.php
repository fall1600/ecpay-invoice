<?php

namespace FbBuy\Package\Ecpay\Invoice;

trait Cryption
{
    protected $cipher = 'aes-128-cbc';

    /**
     * @param  string  $input
     * @return string
     */
    public function countChecksum(string $input)
    {
        return strtolower(hash('sha256', $this->hashKey.$input.$this->hashIv));
    }
    
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
        return openssl_encrypt($encoded, $this->cipher, $this->hashKey, 0, $this->hashIv);
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

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->hashKey, 0, $this->hashIv);
        return json_decode(urldecode($decrypted), true);
    }

    protected function replaceSymbol(string $str)
    {
        if(! empty($str)) {
            $str = str_replace('%2D', '-', $str);
            $str = str_replace('%2d', '-', $str);
            $str = str_replace('%5F', '_', $str);
            $str = str_replace('%5f', '_', $str);
            $str = str_replace('%2E', '.', $str);
            $str = str_replace('%2e', '.', $str);
            $str = str_replace('%21', '!', $str);
            $str = str_replace('%2A', '*', $str);
            $str = str_replace('%2a', '*', $str);
            $str = str_replace('%28', '(', $str);
            $str = str_replace('%29', ')', $str);
        }

        return $str ;
    }
}
