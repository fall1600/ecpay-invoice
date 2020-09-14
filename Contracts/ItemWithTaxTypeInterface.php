<?php

namespace fall1600\Package\Ecpay\Invoice\Contracts;

interface ItemWithTaxTypeInterface extends ItemInterface
{
    // 當課稅類別[TaxType] = 9 時,此欄位不可為空
    public function getTaxType();
}