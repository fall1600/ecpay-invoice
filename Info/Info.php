<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Contracts\CustomerInterface;
use FbBuy\Package\Ecpay\Invoice\Contracts\OrderInterface;

abstract class Info
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var string
     */
    protected $vatType;

    /**
     * @return array
     */
    abstract public function getInfo();

    /**
     * Info constructor.
     * @param  OrderInterface  $order
     * @param  CustomerInterface  $customer
     * @param  string  $vatType 價格為含稅或未稅
     */
    public function __construct(OrderInterface $order, CustomerInterface $customer, string $vatType = \EcpayVatType::Yes)
    {
        $this->order = $order;

        $this->customer = $customer;

        $this->vatType = $vatType;
    }
}
