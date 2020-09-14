<?php

namespace fall1600\Package\Ecpay\Invoice\Info;

use fall1600\Package\Ecpay\Invoice\Constants\ClearanceType;
use fall1600\Package\Ecpay\Invoice\Constants\SpecialTaxType;
use fall1600\Package\Ecpay\Invoice\Constants\TaxType;
use fall1600\Package\Ecpay\Invoice\Constants\VatType;
use fall1600\Package\Ecpay\Invoice\Contracts\ContactInterface;
use fall1600\Package\Ecpay\Invoice\Contracts\OrderInterface;

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
     * 通關方式
     * @var string
     */
    protected $clearanceType;

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
     * @param string $clearanceType 通關方式(經海關, 非經海關)
     */
    public function __construct(
        OrderInterface $order,
        ContactInterface $contact,
        string $vatType = VatType::YES,
        string $taxType = TaxType::DUTIABLE,
        string $specialTaxType = '',
        string $clearanceType = ClearanceType::NOT_BY_CUSTOMS
    ) {
        $this->order = $order;

        $this->contact = $contact;

        $this->setVatType($vatType);

        $this->setTaxType($taxType, $specialTaxType, $clearanceType);
    }

    protected function setVatType(string $vatType)
    {
        if (! VatType::isValid($vatType)) {
            throw new \LogicException("unsupported vat type: $vatType");
        }

        $this->vatType = $vatType;
    }

    protected function setTaxType(string $taxType, string $specialTaxType, string $clearanceType)
    {
        $correctSpecial = SpecialTaxType::$rules[$taxType];
        if (! is_array($correctSpecial) && $correctSpecial !== $specialTaxType) {
            throw new \LogicException("wrong tax type:$taxType with special tax type:$specialTaxType");
        }

        if (is_array($correctSpecial) && ! in_array($specialTaxType, $correctSpecial)) {
            throw new \LogicException("wrong tax type:$taxType with special tax type:$specialTaxType");
        }

        if (! ClearanceType::isValid($clearanceType)) {
            throw new \LogicException("unsupported clearance type: $clearanceType");
        }

        $this->taxType = $taxType;

        $this->specialTaxType = $specialTaxType;
    }
}
