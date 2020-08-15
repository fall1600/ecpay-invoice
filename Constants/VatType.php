<?php

namespace FbBuy\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class VatType extends Enum
{
    // 商品單價含稅價
    public const YES = '1';

    // 商品單價未稅價
    public const NO = '0';
}
