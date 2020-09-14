<?php

namespace fall1600\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

// 通關方式, doc: p5
class ClearanceType extends Enum
{
    // 非經海關出口
    public const NOT_BY_CUSTOMS = '1';
    // 經海關出口
    public const BY_CUSTOMS = '2';
}
