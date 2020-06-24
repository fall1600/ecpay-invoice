<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

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
     * @return array
     */
    abstract public function getInfo();

    /**
     * 一律走線上折讓, 需要掛returnUrl, 用來收買家同意後綠界的webhook
     * AllowanceInfo constructor.
     * @param  string  $invoiceNumber
     * @param  string  $returnUrl
     * @param  string  $vatType
     */
    public function __construct(string $invoiceNumber, string $returnUrl, string $vatType = \EcpayVatType::Yes)
    {
        $this->invoiceNumber = $invoiceNumber;

        $this->returnUrl = $returnUrl;

        $this->vatType = $vatType;
    }
}
