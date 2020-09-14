<?php

namespace fall1600\Package\Ecpay\Invoice;

use fall1600\Package\Ecpay\Invoice\Contracts\OrderInterface;
use fall1600\Package\Ecpay\Invoice\Info\AllowanceInfo;
use fall1600\Package\Ecpay\Invoice\Info\Info;

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
    }

    /**
     * 作廢發票
     * @param string $invoiceNumber 發票號碼
     * @param string $reason 作廢原因
     * @return array
     */
    public function invalid(string $invoiceNumber, string $reason = '')
    {
    }

    /**
     * 查詢作廢發票明細
     * @param OrderInterface $order
     * @return array
     */
    public function queryInvalid(OrderInterface $order)
    {
    }

    /**
     * 折讓發票
     * @param AllowanceInfo $info
     * @return array
     */
    public function allowance(AllowanceInfo $info)
    {
    }

    /**
     * 查詢折讓明細
     * @param string $invoiceNumber
     * @param string $allowanceNumber
     * @return array
     */
    public function queryAllowance(string $invoiceNumber, string $allowanceNumber)
    {
    }

    public function invalidAllowance(string $invoiceNumber, string $allowanceNumber, string $reason = '')
    {
    }

    public function queryInvalidAllowance(string $invoiceNumber, string $allowanceNumber)
    {
    }

    /**
     * 驗證手機載具
     * @param  string  $carrier
     * @return array
     */
    public function verifyCarrier(string $carrier)
    {
    }

    /**
     * 驗證捐贈代號
     * @param  string  $carrier
     * @return array
     */
    public function verifyLovecode(string $lovecode)
    {
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
}
