<?php

namespace fall1600\Package\Ecpay\Invoice\Contracts;

interface ContactInterface
{
    /**
     * 手機或信箱的值
     * e.g. 0988123456 or service@example.com
     * @return string
     */
    public function getContact();
}
