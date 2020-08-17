<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Constants\InvType;
use FbBuy\Package\Ecpay\Invoice\Constants\TaxType;
use FbBuy\Package\Ecpay\Invoice\Contracts\ItemInterface;
use FbBuy\Package\Ecpay\Invoice\Contracts\ItemWithTaxTypeInterface;

class BasicInfo extends Info
{
    protected $items = [];

    /**
     * InvType 07: 一般稅額, 08: 特種稅額(用不到)
     * @return array
     */
    public function getInfo()
    {
        $result = [
            'RelateNumber' => $this->order->getRelateNumber(),
            'TaxType' => $this->taxType,
            'SpecialTaxType' => $this->specialTaxType,
            'InvType' => InvType::GENERAL,
            'SalesAmount' => 0,
        ];

        // 當課稅類別[TaxType]=2(零稅率)時,為必填
        if (TaxType::ZERO == $this->taxType) {
            $result['ClearanceMark'] = $this->clearanceType;
        }

        $result += $this->countContact();

        /** @var ItemInterface $item */
        foreach ($this->items as $item) {
            $datum = [
                'ItemName' => $item->getName(),
                'ItemCount' => $item->getCount(),
                'ItemWord' => $item->getWord(),
                'ItemPrice' => $item->getPrice(),
                'ItemAmount' => $itemAmount = $this->countItemAmount($item),
                'ItemRemark' => $item->getRemark(),
            ];

            if ($item instanceof ItemWithTaxTypeInterface) {
                $datum['ItemTaxType'] = $item->getTaxType();
            }

            $result['Items'][]= $datum;

            $result['SalesAmount'] += $itemAmount;
        }

        return $result;
    }

    /**
     * @param  ItemInterface  $item
     * @return $this
     */
    public function appendItem(ItemInterface $item)
    {
        if (TaxType::MIX === $this->taxType && ! ($item instanceof ItemWithTaxTypeInterface)) {
            throw new \LogicException("Implements ItemWithTaxTypeInterface when you use Mix TaxType");
        }

        $this->items[]= $item;

        return $this;
    }

    /**
     * 品項小記
     * @param  ItemInterface  $item
     * @return float|int
     */
    protected function countItemAmount(ItemInterface $item)
    {
        if ($this->vatType === \EcpayVatType::Yes) {
            return $item->getCount() * $item->getPrice();
        }

        return $item->getCount() * $item->getPrice() * 1.05;
    }

    /**
     * @return array
     */
    protected function countContact()
    {
        $contact = $this->contact->getContact();
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return [
                'CustomerEmail' => $contact,
            ];
        }

        preg_match('/^09\d{8}$/', $contact, $matches);
        if (count($matches) > 0) {
            return [
                'CustomerPhone' => $matches[0],
            ];
        }

        throw new \LogicException('unsupport contact data');
    }
}
