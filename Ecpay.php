<?php

namespace FbBuy\Package\Ecpay\Invoice;

class Ecpay
{
    public const VERSION = '3.0.0';

    /**
     * 開立-測試環境
     * @var string
     */
    public const ISSUE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/Issue';

    /**
     * 開立-正式環境
     * @var string
     */
    public const ISSUE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/Issue';

    public function issue(Info $info)
    {

    }
}
