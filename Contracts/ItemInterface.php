<?php

namespace FbBuy\Package\Ecpay\Invoice\Contracts;

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
     * 商品備註
     */
    public function getRemark();
}
