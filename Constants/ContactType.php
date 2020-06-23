<?php

namespace FbBuy\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class ContactType extends Enum
{
    public const PHONE = 'phone';

    public const EMAIL = 'email';
}