<?php

namespace fall1600\Package\Ecpay\Invoice\Info;

use fall1600\Package\Ecpay\Invoice\Constants\VatType;

abstract class AllowanceInfo
{
    /**
     * @var string
     */
    protected $invoiceNumber;

    /**
     * @var string
     */
    protected $returnUrl;

    /**
     * @var string
     */
    protected $vatType;

    /**
     * 開立發票日期 (yyyy-MM-dd)
     * @var string
     */
    protected $invoiceDate;

    /**
     * @return array
     */
    abstract public function getInfo();

    /**
     * 一律走線上折讓, 需要掛returnUrl, 用來收買家同意後綠界的webhook
     * AllowanceInfo constructor.
     * @param string $invoiceNumber
     * @param string $returnUrl
     * @param string $invoiceDate
     * @param string $vatType
     */
    public function __construct(string $invoiceNumber, string $returnUrl, string $invoiceDate, string $vatType = VatType::YES)
    {
        $this->invoiceNumber = $invoiceNumber;

        $this->returnUrl = $returnUrl;

        $this->invoiceDate = $invoiceDate;

        $this->vatType = $vatType;
    }
}
