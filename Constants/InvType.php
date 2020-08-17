<?php

namespace FbBuy\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

// 字軌類別, doc: p.7
class InvType extends Enum
{
    // 一般稅額
    public const GENERAL = '07';

    // 特種稅額
    public const SPECIAL = '08';
}
