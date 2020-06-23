<?php

namespace FbBuy\Package\Ecpay\Invoice\Contracts;

interface CustomerInterface
{
    /**
     * 手機或信箱的值
     * e.g. 0988123456 or fbbuy@fbbuy.com.tw
     * @return string
     */
    public function getContact();
}
