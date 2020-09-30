<?php

namespace fall1600\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class AllowanceNotifyType extends Enum
{
    public const SMS = 'S';

    public const EMAIL = 'E';

    public const ALL = 'A';

    public const NONE = 'N';
}
