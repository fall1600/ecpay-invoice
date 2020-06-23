<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Constants\ContactType;
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
            'InvType' => '07',
            'SalesAmount' => 0,
        ];

        switch ($this->customer->getContactType()) {
            case ContactType::PHONE:
                $result['CustomerPhone'] = $this->customer->getContact();
                break;
            case ContactType::EMAIL:
                $result['CustomerEmail'] = $this->customer->getContact();
                break;
            default:
                throw new \LogicException('unsupported contact type');
        }

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
}
