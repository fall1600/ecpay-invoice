<?php

namespace fall1600\Package\Ecpay\Invoice\Contracts;

interface OrderInterface
{
    /**
     * 特店自訂編號, 當年度需為唯一值不可重複使用
     * @return string
     */
    public function getRelateNumber();
}
