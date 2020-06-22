<?php

namespace FbBuy\Package\Ecpay\Invoice;

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

    public function issue(Info $info)
    {
        $url = $this->isProduction ? self::ISSUE_URL_PRODUCTION : self::ISSUE_URL_TEST;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($info->getInfo()));

        $resp = curl_exec($ch);
        curl_close($ch);

        var_dump($resp);
//        return $result;
    }

    public function setMerchant(Merchant $merchant)
    {
        $this->merchant = $merchant;

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
