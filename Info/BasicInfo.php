<?php

namespace FbBuy\Package\Ecpay\Invoice;

class BasicInfo extends Info
{
    public function getInfo()
    {
        return [
            'MerchantID' => $this->merchantId,
            'RqHeader' => [
                'Timestamp' => time(),
                'RqID' => $this->getRequestId(),
                'Revision' => Ecpay::VERSION,
            ],
            'Data' => [],
        ];
    }
}
