<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Constants\SpecialTaxType;
use FbBuy\Package\Ecpay\Invoice\Constants\TaxType;
use FbBuy\Package\Ecpay\Invoice\Constants\VatType;
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
     * @var string
     */
    protected $specialTaxType;

    /**
     * @return array
     */
    abstract public function getInfo();

    /**
     * Info constructor.
     * @param OrderInterface $order
     * @param ContactInterface $contact
     * @param string $vatType 價格為含稅或未稅
     * @param string $taxType 課稅類別
     * @param string $specialTaxType 特種稅率
     */
    public function __construct(
        OrderInterface $order,
        ContactInterface $contact,
        string $vatType = VatType::YES,
        string $taxType = TaxType::DUTIABLE,
        string $specialTaxType = ''
    ) {
        $this->order = $order;

        $this->contact = $contact;

        $this->setVatType($vatType);

        $this->setTaxType($taxType, $specialTaxType);
    }

    protected function setVatType(string $vatType)
    {
        if (! VatType::isValid($vatType)) {
            throw new \LogicException("unsupported vat type: $vatType");
        }

        $this->vatType = $vatType;
    }

    protected function setTaxType(string $taxType, string $specialTaxType)
    {
        $mapped = SpecialTaxType::$rules[$taxType];
        if ($mapped !== $specialTaxType && ! in_array($specialTaxType, $mapped)) {
            throw new \LogicException("wrong tax type:$taxType with special tax type:$specialTaxType");
        }

        $this->taxType = $taxType;

        $this->specialTaxType = $specialTaxType;
    }
}
