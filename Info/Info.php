<?php

namespace FbBuy\Package\Ecpay\Invoice;

abstract class Info
{
    /**
     * @var string
     */
    protected $merchantId;

    abstract public function getInfo();

    public function __construct(string $merchantId)
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
