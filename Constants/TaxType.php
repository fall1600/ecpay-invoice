<?php

namespace fall1600\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class TaxType extends Enum
{
    // 應稅
    public const DUTIABLE = '1';

    // 零稅率
    const ZERO = '2';

    // 免稅
    const FREE = '3';

    // 應稅(特種稅率)
    const SPECIAL = '4';

    // 混合應稅與免稅或零稅率時(限收銀機發票無法分辨時使用, 且需通過申請核可)
    const MIX = '9';
}
