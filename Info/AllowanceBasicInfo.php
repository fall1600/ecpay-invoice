<?php

namespace fall1600\Package\Ecpay\Invoice\Info;

use fall1600\Package\Ecpay\Invoice\Contracts\ItemInterface;

class AllowanceBasicInfo extends AllowanceInfo
{
    /**
     * @var ItemInterface[]
     */
    protected $items;

    public function getInfo()
    {
        $result = [
            'InvoiceNo' => $this->invoiceNumber,
            'ReturnURL' => $this->returnUrl,
        ];

        $result['AllowanceAmount'] = 0;
        foreach ($this->items as $item) {
            $result['Items'][]= [
                'ItemName' => $item->getName(),
                'ItemCount' => $item->getCount(),
                'ItemWord' => $item->getWord(),
                'ItemPrice' => $item->getPrice(),
                'ItemTaxType' => \EcpayTaxType::Dutiable,
                'ItemAmount' => $itemAmount = $this->countItemAmount($item),
            ];

            $result['AllowanceAmount'] += $itemAmount;
        }
        return $result;
    }

    /**
     * @param ItemInterface $item
     * @return $this
     */
    public function appendItem(ItemInterface $item)
    {
        $this->items[]= $item;

        return $this;
    }

    protected function countItemAmount(ItemInterface $item)
    {
        if ($this->vatType === \EcpayVatType::Yes) {
            return $item->getCount() * $item->getPrice();
        }

        return $item->getCount() * $item->getPrice() * 1.05;
    }
}
