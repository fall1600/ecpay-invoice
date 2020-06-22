<?php

namespace FbBuy\Package\Ecpay\Invoice;

abstract class Info
{
    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @return array
     */
    abstract public function getInfo();

    /**
     * Info constructor.
     * @param  string  $merchantId
     * @param  bool  $isPrint
     */
    public function __construct(string $merchantId, bool $isPrint = false)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }
}
