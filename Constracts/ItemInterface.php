<?php

namespace FbBuy\Package\Ecpay\Invoice;

interface ItemInterface
{
    public function getName();

    public function getCount();

    /**
     * 單位
     * @return string
     */
    public function getWord();

    public function getPrice();

    /**
     * 商品課稅別
     * @return string
     */
    public function getTaxType();

    /**
     * 商品合計
     * @return float
     */
    public function getAmount();
}
