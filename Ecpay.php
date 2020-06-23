<?php

namespace FbBuy\Package\Ecpay\Invoice;

use EcpayInvoice;
use FbBuy\Package\Ecpay\Invoice\Info\Info;

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

    /**
     * @var bool
     */
    protected $isProduction = true;

    /**
     * @var Merchant
     */
    protected $merchant;

    /**
     * @var EcpayInvoice
     */
    protected $sdk;

    public function __construct()
    {
        $this->sdk = new \EcpayInvoice();
    }

    public function issue(Info $info)
    {
        $url = $this->isProduction ? self::ISSUE_URL_PRODUCTION : self::ISSUE_URL_TEST;
        $this->sdk->Invoice_Url = $url;

        $this->sdk->Send = $info->getInfo();

        $this->sdk->Check_Out();
    }

    public function setMerchant(Merchant $merchant)
    {
        if (! $this->sdk) {
            throw new \LogicException('sdk is not initialized');
        }

        $this->merchant = $merchant;

        $this->sdk->MerchantID = $this->merchant->getId();
        $this->sdk->HashKey = $this->merchant->getHashKey();
        $this->sdk->HashIV = $this->merchant->getHashIv();

        return $this;
    }

    /**
     * @param  bool  $isProduction
     * @return $this
     */
    public function setIsProduction(bool $isProduction)
    {
        $this->isProduction = $isProduction;

        return $this;
    }
}
