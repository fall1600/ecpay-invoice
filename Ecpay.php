<?php

namespace FbBuy\Package\Ecpay\Invoice;

use EcpayInvoice;
use FbBuy\Package\Ecpay\Invoice\Contracts\OrderInterface;
use FbBuy\Package\Ecpay\Invoice\Info\AllowanceInfo;
use FbBuy\Package\Ecpay\Invoice\Info\Info;

class Ecpay
{
    public const VERSION = '3.2.1';

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
     * 查詢發票明細-測試環境
     * @var string
     */
    public const QUERY_ISSUE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/GetIssue';

    /**
     * 查詢發票明細-正式環境
     * @var string
     */
    public const QUERY_ISSUE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/GetIssue';

    /**
     * 作廢-測試環境
     * @var string
     */
    public const INVALID_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/Invalid';

    /**
     * 作廢-正式環境
     * @var string
     */
    public const INVALID_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/Invalid';

    /**
     * 查詢作廢-測試環境
     * @var string
     */
    public const QUERY_INVALID_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/GetInvalid';

    /**
     * 查詢作廢-正式環境
     * @var string
     */
    public const QUERY_INVALID_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/GetInvalid';

    /**
     * 折讓-測試環境
     * @var string
     */
    public const ALLOWANCE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/AllowanceByCollegiate';

    /**
     * 折讓-正式環境
     * @var string
     */
    public const ALLOWANCE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/AllowanceByCollegiate';

    /**
     * 查詢折讓-測試環境
     * @var string
     */
    public const QUERY_ALLOWANCE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/GetAllowance';

    /**
     * 查詢折讓-正式環境
     * @var string
     */
    public const QUERY_ALLOWANCE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/GetAllowance';

    /**
     * 作廢折讓-測試環境
     * @var string
     */
    public const INVALID_ALLOWANCE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/AllowanceInvalid';

    /**
     * 作廢折讓-正式環境
     * @var string
     */
    public const INVALID_ALLOWANCE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/AllowanceInvalid';

    /**
     * 查詢作廢折讓-測試環境
     * @var string
     */
    public const QUERY_INVALID_ALLOWANCE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/GetAllowanceInvalid';

    /**
     * 查詢作廢折讓-正式環境
     * @var string
     */
    public const QUERY_INVALID_ALLOWANCE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/GetAllowanceInvalid';

    /**
     * 載具驗證-測試環境
     * @var string
     */
    public const VERIFY_CARRIER_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/CheckBarcode';

    /**
     * 載具驗證-正式環境
     * @var string
     */
    public const VERIFY_CARRIER_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/CheckBarcode';

    /**
     * 捐贈碼驗證-測試環境
     * @var string
     */
    public const VERIFY_LOVECODE_URL_TEST = 'https://einvoice-stage.ecpay.com.tw/B2CInvoice/CheckLoveCode';

    /**
     * 捐贈碼驗證-正式環境
     * @var string
     */
    public const VERIFY_LOVECODE_URL_PRODUCTION = 'https://einvoice.ecpay.com.tw/B2CInvoice/CheckLoveCode';

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
     * @return Response
     * @throws \Exception
     */
    public function issue(Info $info)
    {
        $url = $this->isProduction ? self::ISSUE_URL_PRODUCTION : self::ISSUE_URL_TEST;

        $resp = $this->postData(
            $url,
            $this->preparePayload($info)
        );

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
    }

    /**
     * 查詢發票明細
     * @param OrderInterface $order
     * @return array
     */
    public function queryIssue(OrderInterface $order)
    {
        $this->resetSdkParameters();

        $url = $this->isProduction ? self::QUERY_ISSUE_URL_PRODUCTION : self::QUERY_ISSUE_URL_TEST;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::INVOICE_SEARCH;
        $this->sdk->Invoice_Url = $url;

        $this->sdk->Send['RelateNumber'] = $order->getRelateNumber();
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
        $this->resetSdkParameters();

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
        $this->resetSdkParameters();

        $url = $this->isProduction ? self::QUERY_INVALID_URL_PRODUCTION : self::QUERY_INVALID_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::INVOICE_VOID_SEARCH;

        $this->sdk->Send['RelateNumber'] = $order->getRelateNumber();
        return $this->sdk->Check_Out();
    }

    /**
     * 折讓發票
     * @param AllowanceInfo $info
     * @return array
     */
    public function allowance(AllowanceInfo $info)
    {
        $this->resetSdkParameters();

        $url = $this->isProduction ? self::ALLOWANCE_URL_PRODUCTION : self::ALLOWANCE_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::ALLOWANCE;

        $this->sdk->Send = array_merge($this->sdk->Send, $info->getInfo());
        return $this->sdk->Check_Out();
    }

    /**
     * 查詢折讓明細
     * @param string $invoiceNumber
     * @param string $allowanceNumber
     * @return array
     */
    public function queryAllowance(string $invoiceNumber, string $allowanceNumber)
    {
        $this->resetSdkParameters();

        $url = $this->isProduction ? self::QUERY_ALLOWANCE_URL_PRODUCTION : self::QUERY_ALLOWANCE_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::ALLOWANCE_SEARCH;

        $this->sdk->Send['InvoiceNo'] = $invoiceNumber;
        $this->sdk->Send['AllowanceNo'] = $allowanceNumber;
        return $this->sdk->Check_Out();
    }

    public function invalidAllowance(string $invoiceNumber, string $allowanceNumber, string $reason = '')
    {
        $url = $this->isProduction ? self::INVALID_ALLOWANCE_URL_PRODUCTION : self::INVALID_ALLOWANCE_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::ALLOWANCE_VOID;

        $this->sdk->Send['InvoiceNo'] = $invoiceNumber;
        $this->sdk->Send['AllowanceNo'] = $allowanceNumber;
        $this->sdk->Send['Reason'] = $reason;
        return $this->sdk->Check_Out();
    }

    public function queryInvalidAllowance(string $invoiceNumber, string $allowanceNumber)
    {
        $url = $this->isProduction ? self::QUERY_INVALID_ALLOWANCE_URL_PRODUCTION : self::QUERY_INVALID_ALLOWANCE_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::ALLOWANCE_VOID_SEARCH;

        $this->sdk->Send['InvoiceNo'] = $invoiceNumber;
        $this->sdk->Send['AllowanceNo'] = $allowanceNumber;
        return $this->sdk->Check_Out();
    }

    /**
     * 驗證手機載具
     * @param  string  $carrier
     * @return array
     */
    public function verifyCarrier(string $carrier)
    {
        $url = $this->isProduction ? self::VERIFY_CARRIER_URL_PRODUCTION : self::VERIFY_CARRIER_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::CHECK_MOBILE_BARCODE;

        $this->sdk->Send['BarCode'] = $carrier;
        return $this->sdk->Check_Out();
    }

    /**
     * 驗證捐贈代號
     * @param  string  $carrier
     * @return array
     */
    public function verifyLovecode(string $lovecode)
    {
        $url = $this->isProduction ? self::VERIFY_LOVECODE_URL_PRODUCTION : self::VERIFY_LOVECODE_URL_TEST;
        $this->sdk->Invoice_Url = $url;
        $this->sdk->Invoice_Method = \EcpayInvoiceMethod::CHECK_LOVE_CODE;

        $this->sdk->Send['LoveCode'] = $lovecode;
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

    /**
     * @param Info $info
     * @return array
     * @throws \Exception
     */
    protected function preparePayload(Info $info)
    {
        $payload = [
            'MerchantID' => $this->merchant->getId(),
            'RqHeader' => [
                'Timestamp' => $ts = time(),
                'RqID' => $ts.'-'.random_int(0, PHP_INT_MAX),
                'Revision' => self::VERSION,
            ],
        ];

        $payload['Data'] = $this->merchant->encrypt($info->getInfo());
        return $payload;
    }

    /**
     * @param string $url
     * @param array $payload
     * @return mixed
     * @throws \JsonException
     * @throws \Exception
     */
    protected function postData(string $url, array $payload)
    {
        $payload = [
            'MerchantID' => $this->merchant->getId(),
            'RqHeader' => [
                'Timestamp' => $ts = time(),
                'RqID' => $ts.'-'.random_int(0, PHP_INT_MAX),
                'Revision' => self::VERSION,
            ],
        ];

        $payload['Data'] = $this->merchant->decrypt(json_encode($payload));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function resetSdkParameters()
    {
        $this->sdk->TimeStamp = time();
        $this->sdk->Send = [
            'RelateNumber' => '',
            'CustomerID' => '',
            'CustomerIdentifier' => '',
            'CustomerName' => '',
            'CustomerAddr' => '',
            'CustomerPhone' => '',
            'CustomerEmail' => '',
            'ClearanceMark' => '',
            'Print' => '0',
            'Donation' => '0',
            'LoveCode' => '',
            'CarruerType' => '',
            'CarruerNum' => '',
            'TaxType' => '',
            'SalesAmount' => '',
            'InvoiceRemark' => '',
            'Items' => [],
            'InvType' => '',
            'vat' => '1',
            'DelayFlag' => '',
            'DelayDay' => 0,
            'Tsr' => '',
            'PayType' => '',
            'PayAct' => '',
            'NotifyURL' => '',
            'InvoiceNo' => '',
            'AllowanceNotify' => '',
            'NotifyMail' => '',
            'NotifyPhone' => '',
            'AllowanceAmount' => '',
            'InvoiceNumber'  => '',
            'Reason'  => '',
            'AllowanceNo' => '',
            'Phone' => '',
            'Notify' => '',
            'InvoiceTag' => '',
            'Notified' => '',
            'BarCode' => '',
            'OnLine' => true
        ];
    }
}
