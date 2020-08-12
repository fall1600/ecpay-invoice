<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Contracts\ContactInterface;
use FbBuy\Package\Ecpay\Invoice\Contracts\OrderInterface;

abstract class Info
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var ContactInterface
     */
    protected $contact;

    /**
     * @var string
     */
    protected $vatType;

    /**
     * @var string
     */
    protected $taxType;

    /**
     * @return array
     */
    abstract public function getInfo();

    /**
     * Info constructor.
     * @param  OrderInterface  $order
     * @param  ContactInterface  $contact
     * @param  string  $vatType  價格為含稅或未稅
     * @param  string  $taxType  課稅類別
     */
    public function __construct(OrderInterface $order, ContactInterface $contact, string $vatType = \EcpayVatType::Yes, string $taxType = \EcpayTaxType::Dutiable)
    {
        $this->order = $order;

        $this->contact = $contact;

        $this->vatType = $vatType;

        $this->taxType = $taxType;
    }
}
