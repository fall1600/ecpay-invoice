<?php

namespace fall1600\Package\Ecpay\Invoice\Info;

use fall1600\Package\Ecpay\Invoice\Constants\TaxType;
use fall1600\Package\Ecpay\Invoice\Constants\VatType;
use fall1600\Package\Ecpay\Invoice\Contracts\ItemInterface;

class AllowanceBasicInfo extends AllowanceInfo
{
    /**
     * @var ItemInterface[]
     */
    protected $items;

    public function getInfo()
    {
        if (! $this->items) {
            throw new \LogicException("error by not appending items to allowance yet");
        }

        $result = [
            'InvoiceNo' => $this->invoiceNumber,
            'ReturnURL' => $this->returnUrl,
            'InvoiceDate' => $this->invoiceDate,
        ];

        $result['AllowanceAmount'] = 0;
        foreach ($this->items as $item) {
            $result['Items'][]= [
                'ItemName' => $item->getName(),
                'ItemCount' => $item->getCount(),
                'ItemWord' => $item->getWord(),
                'ItemPrice' => $item->getPrice(),
                // fixme: 拆掉寫死的稅別
                'ItemTaxType' => TaxType::DUTIABLE,
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
        if (VatType::YES === $this->vatType) {
            return $item->getCount() * $item->getPrice();
        }

        // fixme: 拆掉寫死的趴數
        return $item->getCount() * $item->getPrice() * 1.05;
    }
}
