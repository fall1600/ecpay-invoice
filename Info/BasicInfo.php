<?php

namespace FbBuy\Package\Ecpay\Invoice\Info;

use FbBuy\Package\Ecpay\Invoice\Ecpay;

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

    protected function getRequestId()
    {
        return time().rand();
    }
}
