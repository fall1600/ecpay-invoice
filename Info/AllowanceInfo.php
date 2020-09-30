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
     * AllowanceInfo constructor.
     * @param string $invoiceNumber
     * @param string $invoiceDate
     * @param string $vatType
     * @param string|null $returnUrl
     */
    public function __construct(string $invoiceNumber, string $invoiceDate, string $vatType = VatType::YES, string $returnUrl = null)
    {
        $this->invoiceNumber = $invoiceNumber;

        $this->invoiceDate = $invoiceDate;

        $this->vatType = $vatType;

        $this->returnUrl = $returnUrl;
    }
}
