<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Constants\ClearanceType;
use FbBuy\Package\Ecpay\Invoice\Constants\InvType;
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
     * 通關方式
     * @var string
     */
    protected $clearanceType;

    /**
     * 字軌類別
     * @var string
     */
    protected $invType;

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
     * @param string $invType 字軌類別
     */
    public function __construct(
        OrderInterface $order,
        ContactInterface $contact,
        string $vatType = VatType::YES,
        string $taxType = TaxType::DUTIABLE,
        string $specialTaxType = '',
        string $clearanceType = ClearanceType::NOT_BY_CUSTOMS,
        string $invType = InvType::GENERAL
    ) {
        $this->order = $order;

        $this->contact = $contact;

        $this->invType = $invType;

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
        $mapped = SpecialTaxType::$rules[$taxType];
        if ($mapped !== $specialTaxType && ! in_array($specialTaxType, $mapped)) {
            throw new \LogicException("wrong tax type:$taxType with special tax type:$specialTaxType");
        }

        if (! ClearanceType::isValid($clearanceType)) {
            throw new \LogicException("unsupported clearance type: $clearanceType");
        }

        $this->taxType = $taxType;

        $this->specialTaxType = $specialTaxType;
    }
}
