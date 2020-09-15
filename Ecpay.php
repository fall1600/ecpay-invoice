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

        $resp = $this->postData($url, $info->getInfo());

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
    }

    /**
     * 查詢發票明細
     * aha: 文件上發票號碼, 開立日期為必填, 實測為選填
     *
     * @param   OrderInterface  $order
     * @param   string|null     $invoiceNo 發票號碼
     * @param   string|null     $issuedAt 發票開立日期 (yyyy-MM-dd)
     * @return Response
     * @throws \JsonException
     */
    public function queryIssue(OrderInterface $order, string $invoiceNo = null, string $issuedAt = null)
    {
        $url = $this->isProduction ? self::QUERY_ISSUE_URL_PRODUCTION : self::QUERY_ISSUE_URL_TEST;

        $payload = [
            'RelateNumber' => $order->getRelateNumber(),
        ];

        if ($invoiceNo) {
            $payload['InvoiceNo'] = $invoiceNo;
        }

        if ($issuedAt) {
            $payload['InvoiceDate'] = $issuedAt;
        }

        $resp = $this->postData($url, $payload);

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
    }

    /**
     * 作廢發票
     * aha: 開立日期格式雖然要求到小時, 但實測僅需提供到yyyy-MM-dd 即可
     *
     * @param   string  $invoiceNo  發票號碼
     * @param   string  $issuedAt   發票開立日期 (yyyy-MM-dd HH)
     * @param   string  $reason     作廢原因
     *
     * @return Response
     * @throws \JsonException
     */
    public function invalid(string $invoiceNo, string $issuedAt, string $reason)
    {
        $url = $this->isProduction ? self::INVALID_URL_PRODUCTION : self::INVALID_URL_TEST;

        $payload = [
            'InvoiceNo' => $invoiceNo,
            'InvoiceDate' => $issuedAt,
            'Reason' => $reason,
        ];

        $resp = $this->postData($url, $payload);

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
    }

    /**
     * 查詢作廢發票明細
     *
     * @param   OrderInterface  $order
     *
     * @param   string          $invoiceNo
     * @param   string          $issuedAt
     *
     * @return Response
     * @throws \JsonException
     */
    public function queryInvalid(OrderInterface $order, string $invoiceNo, string $issuedAt)
    {
        $url = $this->isProduction ? self::QUERY_INVALID_URL_PRODUCTION : self::QUERY_INVALID_URL_TEST;

        $payload = [
            'RelateNumber' => $order->getRelateNumber(),
            'InvoiceNo' => $invoiceNo,
            'InvoiceDate' => $issuedAt,
        ];

        $resp = $this->postData($url, $payload);

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
    }

    /**
     * 折讓發票
     *
     * @param   AllowanceInfo  $info
     *
     * @return array
     * @throws \JsonException
     */
    public function allowance(AllowanceInfo $info)
    {
        $url = $this->isProduction ? self::ALLOWANCE_URL_PRODUCTION : self::ALLOWANCE_URL_TEST;

        $payload = [
        ];

        $resp = $this->postData($url, $payload);

        $resp['DecryptedData'] = $this->merchant->decrypt($resp['Data']);

        return new Response($resp);
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
     * @param string $url
     * @param array $payload
     * @return mixed
     * @throws \JsonException
     * @throws \Exception
     */
    protected function postData(string $url, array $payload)
    {
        $data = [
            'MerchantID' => $this->merchant->getId(),
            'RqHeader' => [
                'Timestamp' => $ts = time(),
                'RqID' => $ts.'-'.random_int(0, PHP_INT_MAX),
                'Revision' => self::VERSION,
            ],
            'Data' => $this->merchant->encrypt($payload),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }
}
