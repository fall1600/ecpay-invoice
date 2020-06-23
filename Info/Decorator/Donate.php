<?php

namespace FbBuy\Package\Ecpay\Invoice\Info\Decorator;

use FbBuy\Package\Ecpay\Invoice\Info\Info;
use FbBuy\Package\Ecpay\Invoice\Info\InfoDecorator;

class Donate extends InfoDecorator
{
    /**
     * @var Info
     */
    protected $info;

    /**
     * @var string
     */
    protected $loveCode;

    public function __construct(Info $info, string $loveCode = '168001')
    {
        $this->info = $info;

        $this->loveCode = $loveCode;
    }
    
    public function getInfo()
    {
        return $this->info->getInfo() +
            [
                'Print' => 0,
                'Donation' => 1,
                'LoveCode' => $this->loveCode,
            ];
    }
}