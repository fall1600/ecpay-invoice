<?php

namespace FbBuy\Package\Ecpay\Invoice;

use FbBuy\Package\Ecpay\Invoice\Info\Info;

trait Cryption
{
    protected $cipher = 'aes-128-cbc';

    /**
     * 綠界說怎麼算就怎麼算
     * @param  Info  $info
     * @return string
     */
    public function encrypt(Info $info)
    {
        $encoded = urlencode(json_encode($info->getInfo()));
        return openssl_encrypt($encoded, $this->cipher, $this->hashKey, 0, $this->hashIv);
    }

    /**
     * 綠界說怎麼算就怎麼算
     * @param  string  $encrypted
     * @return array
     */
    public function decrypt(string $encrypted)
    {
        $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->hashKey, 0, $this->hashIv);
        return json_decode(urldecode($decrypted), true);
    }
}
