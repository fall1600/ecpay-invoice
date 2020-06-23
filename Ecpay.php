<?php

namespace FbBuy\Package\Ecpay\Invoice;

use EcpayInvoice;
use FbBuy\Package\Ecpay\Invoice\Contracts\OrderInterface;
use FbBuy\Package\Ecpay\Invoice\Info\Info;

class Ecpay
{
    /**
     * 開立-測試環境
     * @var string
     */
    public const ISSUE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/Invoice/Issue';

    /**
     * 開立-正式環境
     * @var string
     */
    public const ISSUE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/Invoice/Issue';

    /**
     * 查詢發票明細-測試環境
     * @var string
     */
    public const QUERY_ISSUE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/Query/Issue';

    /**
     * 查詢發票明細-正式環境
     * @var string
     */
    public const QUERY_ISSUE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/Query/Issue';

    /**
     * 作廢-測試環境
     * @var string
     */
    public const INVALID_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/Invoice/IssueInvalid';

    /**
     * 作廢-正式環境
     * @var string
     */
    public const INVALID_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/Invoice/IssueInvalid';

    /**
     * 查詢作廢-測試環境
     * @var string
     */
    public const QUERY_INVALID_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/Query/IssueInvalid';

    /**
     * 查詢作廢-正式環境
     * @var string
     */
    public const QUERY_INVALID_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/Query/IssueInvalid';

    /**
     * 折讓-測試環境
     * @var string
     */
    public const ALLOWANCE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/Invoice/Allowance';

    /**
     * 折讓-正式環境
     * @var string
     */
    public const ALLOWANCE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/Invoice/Allowance';


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
        $this->sdk = new EcpayInvoice();
    }

    /**
     * 開立發票
     * @param Info $info
     * @return array
     */
    public function issue(Info $info)
    {
        $url = $this->isProduction ? self::ISSUE_URL_PRODUCTION : self::ISSUE_URL_TEST;
        $this->sdk->Invoice_Url = $url;

        $payload = $info->getInfo();

        $this->sdk->Send = array_merge($this->sdk->Send, $payload);
        return $this->sdk->Check_Out();
    }

    /**
     * 查詢發票明細
     * @param OrderInterface $order
     * @return array
     */
    public function queryIssue(OrderInterface $order)
    {
        $url = $this->isProduction ? self::QUERY_ISSUE_URL_PRODUCTION : self::QUERY_ISSUE_URL_TEST;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::INVOICE_SEARCH;
        $this->sdk->Invoice_Url = $url;

        $this->sdk->Send['RelateNumber'] = $order->getMerchantOrderNo();
        return $this->sdk->Check_Out();
    }

    /**
     * 作廢發票
     * @param string $invoiceNumber 發票號碼
     * @param string $reason 作廢原因
     * @return array
     */
    public function invalid(string $invoiceNumber, string $reason = '')
    {
        $url = $this->isProduction ? self::INVALID_URL_PRODUCTION : self::INVALID_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::INVOICE_VOID;

        $this->sdk->Send['InvoiceNumber'] = $invoiceNumber;
        $this->sdk->Send['Reason'] = $reason;
        return $this->sdk->Check_Out();
    }

    /**
     * 查詢作廢發票明細
     * @param OrderInterface $order
     * @return array
     */
    public function queryInvalid(OrderInterface $order)
    {
        $url = $this->isProduction ? self::QUERY_INVALID_URL_PRODUCTION : self::QUERY_INVALID_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::INVOICE_VOID_SEARCH;

        $this->sdk->Send['RelateNumber'] = $order->getMerchantOrderNo();
        return $this->sdk->Check_Out();
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
