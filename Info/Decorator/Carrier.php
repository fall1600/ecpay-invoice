<?php

namespace FbBuy\Package\Ecpay\Invoice\Info\Decorator;

use FbBuy\Package\Ecpay\Invoice\Info\Info;
use FbBuy\Package\Ecpay\Invoice\Info\InfoDecorator;

class Carrier extends InfoDecorator
{
    /**
     * @var Info
     */
    protected $info;

    /**
     * @var string
     */
    protected $carrierType;

    /**
     * @var string
     */
    protected $carrierVal;

    public function __construct(Info $info, string $carrierType = '1', string $carrierVal = '')
    {
        $this->info = $info;

        $this->carrierType = $carrierType;

        $this->carrierVal = $carrierVal;
    }

    public function getInfo()
    {
        return $this->info->getInfo() +
            [
                'Print' => 0,
                'CarrierType' => $this->carrierType,
                'CarrierNum' => $this->carrierVal,
            ];
    }
}