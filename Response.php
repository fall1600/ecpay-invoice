<?php

namespace FbBuy\Package\Ecpay\Invoice;

class Response
{
    /**
     * @var array
     */
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->payload['Message'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getDecryptedData()
    {
        return $this->payload['DecryptedData'] ?? null;
    }

    /**
     * 綠界encrypted 過的值, getDecryptedData 可直接拿到decrypted 結果
     * @return string|null
     */
    public function getOriginData()
    {
        return $this->payload['Data'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getPlatformId()
    {
        return $this->payload['PlatformID'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMerchantId()
    {
        return $this->payload['MerchantID'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getRqHeader()
    {
        return $this->payload['RqHeader'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getTransCode()
    {
        return $this->payload['TransCode'] ?? null;
    }
}
