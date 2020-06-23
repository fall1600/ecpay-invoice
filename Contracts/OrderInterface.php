<?php

namespace FbBuy\Package\Ecpay\Invoice\Contracts;

interface OrderInterface
{
    /**
     * 特店自訂編號
     * @return string
     */
    public function getMerchantOrderNo();
}
