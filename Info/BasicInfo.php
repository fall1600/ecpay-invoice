<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Contracts\ItemInterface;

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
            'RelateNumber' => $this->order->getMerchantOrderNo(),
            'TaxType' => \EcpayTaxType::Dutiable,
            'InvType' => \EcpayInvType::General,
            'SalesAmount' => 0,
        ];

        $result += $this->countContact();

        /** @var ItemInterface $item */
        foreach ($this->items as $item) {
            $result['Items'][]= [
                'ItemName' => $item->getName(),
                'ItemCount' => $item->getCount(),
                'ItemWord' => $item->getWord(),
                'ItemPrice' => $item->getPrice(),
                'ItemTaxType' => \EcpayTaxType::Dutiable,
                'ItemAmount' => $itemAmount = $this->countItemAmount($item),
                'ItemRemark' => $item->getRemark(),
            ];

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
        $contact = $this->customer->getContact();
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
