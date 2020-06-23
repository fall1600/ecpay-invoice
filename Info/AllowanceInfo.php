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
    protected $vatType;

    /**
     * @return array
     */
    abstract public function getInfo();

    public function __construct(string $invoiceNumber, string $vatType = \EcpayVatType::Yes)
    {
        $this->invoiceNumber = $invoiceNumber;

        $this->vatType = $vatType;
    }
}
