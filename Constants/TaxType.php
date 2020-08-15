<?php

namespace FbBuy\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class TaxType extends Enum
{
    // 應稅
    public const DUTIABLE = '1';

    // 零稅率
    const ZERO = '2';

    // 免稅
    const FREE = '3';

    // 應稅(特種稅別)
    const SPECIAL = '4';

    // 應稅與免稅混合(限收銀機發票無法分辦時使用，且需通過申請核可)
    const MIX = '9';
}
